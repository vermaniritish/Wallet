<?php
namespace App\Models\Admin;

use App\Models\AppModel;
use Illuminate\Http\Request;

class OrderProductRelation extends AppModel
{
    protected $table = 'order_products';
    protected $primaryKey = 'id';
    public $timestamps = true;

    public function product()
    {
        return $this->hasOne(Products::class, 'id','product_id');
    }


    /**
    * To search and get pagination listing
    * @param Request $request
    * @param $limit
    */

    public static function getListing(Request $request, $where = [])
    {
        $orderBy = $request->get('sort') ? $request->get('sort') : 'order_products.id';
        $direction = $request->get('direction') ? $request->get('direction') : 'desc';
        $page = $request->get('page') ? $request->get('page') : 1;
        $limit = self::$paginationLimit;
        $offset = ($page - 1) * $limit;
        
        $listing = OrderProductRelation::select([
                'order_products.*',
            ])
            ->orderBy($orderBy, $direction);

        if(!empty($where))
        {
            foreach($where as $query => $values)
            {
                if(is_array($values))
                    $listing->whereRaw($query, $values);
                elseif(!is_numeric($query))
                    $listing->where($query, $values);
                else
                    $listing->whereRaw($values);
            }
        }

        // Put offset and limit in case of pagination
        if($page !== null && $page !== "" && $limit !== null && $limit !== "")
        {
            $listing->offset($offset);
            $listing->limit($limit);
        }

        $listing = $listing->paginate($limit);

        // Match color image
        $listing->getCollection()->transform(function ($item) {

            $item->image = null;

            // order_products.color = Black|BLK
            $color = explode('|', $item->color);

            $colorTitle = trim($color[0] ?? '');
            $colorCode  = trim($color[1] ?? '');

            // Find matching product_colors row
            $productColor = DB::table('product_colors')
                ->where('product_id', $item->product_id)
                ->where(function ($q) use ($colorTitle, $colorCode) {
                    $q->where('color_title', $colorTitle)
                    ->orWhere('color_code', $colorCode);
                })
                ->first();

            if ($productColor && !empty($item->color_images)) {

                $images = json_decode($item->color_images, true);

                if (
                    is_array($images) &&
                    isset($images[$productColor->id]['path'])
                ) {
                    $item->image = $images[$productColor->id]['path'];
                }
            }

            return $item;
        });

        return $listing;
    }

}
