<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class PdfMergeService
{
    /**
     * Merge multiple PDFs using qpdf
     *
     * @param array $pdfPaths
     * @param string $outputPath
     * @return string
     *
     * @throws \Exception
     */
    public function merge(array $pdfPaths, string $outputPath): string
    {
        if (empty($pdfPaths)) {
            throw new \Exception('No PDF files provided for merging.');
        }

        // Ensure output directory exists
        $outputDir = dirname($outputPath);

        if (!File::exists($outputDir)) {
            File::makeDirectory($outputDir, 0755, true);
        }

        // Validate files
        $validFiles = [];

        foreach ($pdfPaths as $path) {
            if (File::exists($path)) {
                $validFiles[] = $path;
            } else {
                Log::warning('PDF file not found: ' . $path);
            }
        }

        if (empty($validFiles)) {
            throw new \Exception('No valid PDF files found.');
        }

        /*
         * qpdf --empty --pages file1.pdf file2.pdf -- output.pdf
         */
        $command = array_merge(
            ['qpdf', '--empty', '--pages'],
            $validFiles,
            ['--', $outputPath]
        );

        $process = new Process($command);
        $process->setTimeout(300);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \Exception(
                'PDF merge failed: ' . $process->getErrorOutput()
            );
        }

        return $outputPath;
    }

    /**
     * Merge order PDFs from public/uploads
     *
     * @param array $orders
     * @param string|null $outputFileName
     * @return string
     *
     * @throws \Exception
     */
    public function mergeOrderPdfs($orders, $outputFileName = null)
    {
        pr($orders); die;
        $pdfPaths = [];

        foreach ($orders as $page) {
            $path = public_path(
                'uploads/Order-' . $page->prefix_id . '.pdf'
            );

            $pdfPaths[] = $path;
        }

        $outputFileName = $outputFileName
            ?? 'merged-orders-' . time() . '.pdf';

        $outputPath = public_path('uploads/' . $outputFileName);

        return $this->merge($pdfPaths, $outputPath);
    }
}