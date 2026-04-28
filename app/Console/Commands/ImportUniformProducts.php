<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ImportUniformProducts extends Command
{
    protected $signature = 'products:import-uniforms {file}';
    protected $description = 'Production import with full mapping + size & color cloning';

    protected int $batchSize = 200;

    public function handle()
    {
        $filePath = $this->argument('file');

        if (!file_exists($filePath)) {
            $this->error("File not found");
            return 1;
        }

        $handle = fopen($filePath, 'r');
        $header = fgetcsv($handle);

        $failedPath = storage_path('app/import_failed.csv');
        $failedHandle = fopen($failedPath, 'w');
        fputcsv($failedHandle, array_merge($header, ['error']));

        // ---------------------------
        // ⚡ PRELOAD MAPS
        // ---------------------------
        $schoolMap = [];
        foreach (DB::table('schools')->select('id','name')->get() as $s) {
            $schoolMap[$this->normalize($s->name)] = $s->id;
        }

        $findProducts = DB::table('products')->whereNull('parent_id')->select([
            'id',
            'sku_number',
            'gender',
            'short_description',
            'description',
            'size_file',
            'size_guide_video',
            'image',
            'color_images'
        ])->get();
        $productMap = [];
        foreach ($findProducts as $p) {
            $productMap[$this->normalize($p->sku_number)] = [
                'id' => $p->id,
                'gender' => $p->gender,
                'short_description' => $p->short_description,
                'description' => $p->description,
                'size_file' => $p->size_file,
                'size_guide_video' => $p->size_guide_video,
                'image' => $p->image,
                'color_images' => $p->color_images,
            ];
        }

        $sizeMap = DB::table('product_sizes')->get()->groupBy('product_id');
        $colorMap = DB::table('product_colors')->get()->groupBy('product_id');

        DB::beginTransaction();

        $batch = [];
        $meta = [];
        $count = 0;
        $skipped = 0;

        try {

            while (($row = fgetcsv($handle)) !== false) {

                $csv = $this->cleanRow(array_combine($header, $row));

                // SKU
                $skuRaw = $csv['skucode'] ?? '';
                $skuNorm = $this->normalize($skuRaw);

                if (!$skuNorm) {
                    $this->fail($failedHandle, $csv, 'SKU missing');
                    $skipped++;
                    continue;
                }

                // SCHOOL
                $schoolRaw = $csv['school_name'] ?? '';
                $schoolNorm = $this->normalize($schoolRaw);

                if (!$schoolNorm) {
                    $this->fail($failedHandle, $csv, 'School missing');
                    $skipped++;
                    continue;
                }

                if (!isset($schoolMap[$schoolNorm])) {
                    $schoolId = DB::table('schools')->insertGetId([
                        'name' => $schoolRaw,
                        'status' => 1,
                        'schooltype' => 'Junior',
                        'house_names' => '',
                        'shipping' => 0,
                        'collect_from_school' => 0,
                        'show_extra_products' => 0,
                        'shops' => json_encode([]),
                        'created' => now(),
                    ]);
                    $schoolMap[$schoolNorm] = $schoolId;
                }

                $schoolId = $schoolMap[$schoolNorm];

                // PARENT
                $parent = $productMap[$skuNorm] ?? null;

                if (!$parent) {
                    $this->fail($failedHandle, $csv, 'Parent SKU not found');
                    $skipped++;
                    continue;
                }

                // PRICE
                $price = $this->num($csv['price'] ?? null);
                $maxPrice = $this->num($csv['maxprice'] ?? null);
                if ($maxPrice == 0) $maxPrice = null;

                // BUILD
                $batch[] = [
                    'school_id' => $schoolId,
                    'parent_id' => $parent['id'],
                    'title' => $csv['name'],
                    'slug' => Str::slug($csv['name']),
                    'price' => $price,
                    'max_price' => $maxPrice,
                    'sku_number' => $skuRaw,
                    'gender' => $parent['gender'],
                    'short_description' => $parent['short_description'],
                    'description' => $parent['description'],
                    'size_file' => $parent['size_file'],
                    'size_guide_video' => $parent['size_guide_video'],
                    'image' => $parent['image'],
                    'color_images' => $parent['color_images'],
                    'is_uniform' => 1,
                    'status' => 1,
                    'created' => now(),
                    'modified' => now(),
                ];

                $meta[] = [
                    'sku' => $skuRaw,
                    'parent_id' => $parent['id']
                ];

                $count++;

                if (count($batch) >= $this->batchSize) {
                    DB::table('products')->insert($batch);
                    $this->afterInsert($meta, $sizeMap, $colorMap);

                    $batch = [];
                    $meta = [];

                    $this->info("Inserted: $count");
                }
            }

            if ($batch) {
                DB::table('products')->insert($batch);
                $this->afterInsert($meta, $sizeMap, $colorMap);
            }

            DB::commit();

            fclose($handle);
            fclose($failedHandle);

            $this->info("✅ Done | Inserted: $count | Skipped: $skipped");

        } catch (\Throwable $e) {

            DB::rollBack();

            fclose($handle);
            fclose($failedHandle);

            Log::error("Import failed", ['error' => $e->getMessage()]);
            $this->error($e->getMessage());

            return 1;
        }

        return 0;
    }

    // ---------------------------
    // AFTER INSERT
    // ---------------------------
    private function afterInsert($meta, $sizeMap, $colorMap)
    {
        $skus = array_column($meta, 'sku');

        $products = DB::table('products')
            ->whereIn('sku_number', $skus)
            ->orderByDesc('id')
            ->limit(count($skus))
            ->get();

        foreach ($products as $p) {

            $m = collect($meta)->firstWhere('sku', $p->sku_number);
            if (!$m) continue;

            $parentId = $m['parent_id'];

            // ---------- SIZES ----------
            if (isset($sizeMap[$parentId])) {
                $rows = [];

                foreach ($sizeMap[$parentId] as $s) {
                    $rows[] = [
                        'size_title' => $s->size_title,
                        'from_cm' => $s->from_cm,
                        'to_cm' => $s->to_cm,
                        'chest' => $s->chest,
                        'waist' => $s->waist,
                        'hip' => $s->hip,
                        'length' => $s->length,
                        'product_id' => $p->id,
                        'size_id' => $s->size_id,
                        'color_id' => $s->color_id,
                        'price' => $s->price,
                        'sale_price' => $s->sale_price,
                        'vat' => $s->vat,
                        'non_exchange' => $s->non_exchange,
                        'status' => $s->status,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                DB::table('product_sizes')->insert($rows);
            }

            // ---------- COLORS ----------
            if (isset($colorMap[$parentId])) {
                $rows = [];

                foreach ($colorMap[$parentId] as $c) {
                    $rows[] = [
                        'product_id' => $p->id,
                        'color_id' => $c->color_id,
                        'color_title' => $c->color_title,
                        'color_code' => $c->color_code,
                        'created_by' => $c->created_by,
                        'created' => now(),
                        'modified' => now(),
                    ];
                }

                DB::table('product_colors')->insert($rows);
            }
        }
    }

    // ---------------------------
    // HELPERS
    // ---------------------------
    private function normalize($v)
    {
        if (!$v) return null;
        return mb_strtolower(preg_replace('/[^\p{L}\p{N}]+/u', '', $v));
    }

    private function cleanRow($row)
    {
        foreach ($row as $k => $v) {
            if (is_string($v)) {
                $v = trim($v);
                $v = preg_replace('/[\x00-\x1F\x7F]/u', '', $v);
                $row[$k] = $v;
            }
        }
        return $row;
    }

    private function num($v)
    {
        return is_numeric($v) ? (float)$v : null;
    }

    private function fail($handle, $row, $msg)
    {
        Log::warning($msg, $row);
        fputcsv($handle, array_merge($row, [$msg]));
    }
}