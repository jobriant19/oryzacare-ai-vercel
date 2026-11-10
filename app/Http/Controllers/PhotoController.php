<?php

namespace App\Http\Controllers;

use App\Models\AnalysisImage;

class PhotoController extends Controller
{
    public function __invoke(string $name)
    {
        $image = AnalysisImage::findOrFail($name);

        return response(base64_decode($image->data), 200, [
            'Content-Type'  => $image->mime,
            'Cache-Control' => 'public, max-age=31536000, s-maxage=31536000, immutable',
        ]);
    }
}
