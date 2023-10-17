<?php

namespace App\Http\Controllers\Files;

use App\Http\Controllers\Controller;
use App\Services\DestroyFile;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class FileController extends Controller
{
    public function destroy(Media $media)
    {
        (new DestroyFile)->execute([
            'user_id' => auth()->user()->id,
            'media_id' => $media->id,
        ]);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
