<?php

namespace App\Services;

use App\Models\AnalysisImage;

/** Penyimpanan foto analisis di database (tabel analysis_images). */
class ImageStore
{
    public function put(string $sourcePath, string $name, string $mime): void
    {
        AnalysisImage::updateOrCreate(
            ['name' => $name],
            ['mime' => $mime, 'data' => base64_encode(file_get_contents($sourcePath))]
        );
    }

    public function url(string $name): string
    {
        return route('foto', ['name' => $name]);
    }

    public function delete(string $name): void
    {
        AnalysisImage::whereKey($name)->delete();
    }
}
