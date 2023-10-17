<?php

namespace App\Http\Controllers\Files;

use App\Http\Controllers\Controller;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class FileDownloadController extends Controller
{
    public function show(Media $media): Media
    {
        return $media;
    }
}
