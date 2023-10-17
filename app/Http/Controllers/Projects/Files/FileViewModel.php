<?php

namespace App\Http\Controllers\Projects\Files;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

class FileViewModel
{
    public static function dto(Media $file): array
    {
        $extension = pathinfo($file->file_name, PATHINFO_EXTENSION);

        return [
            'id' => $file->id,
            'name' => $file->name,
            'size' => $file->human_readable_size,
            'extension' => $extension,
            'uploaded_at' => $file->created_at->format('Y-m-d'),
            'url' => [
                'download' => route('files.download.show', [
                    'media' => $file->id,
                ]),
                'destroy' => route('files.destroy', [
                    'media' => $file->id,
                ]),
            ],
        ];
    }
}
