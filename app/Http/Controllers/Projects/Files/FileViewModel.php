<?php

namespace App\Http\Controllers\Projects\Files;

use App\Models\File;
use App\Models\Project;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class FileViewModel
{
    public static function index(Project $project): array
    {
        $files = $project->files()->get()
            ->map(fn (File $file) => self::dto($file->media));

        return [
            'project' => [
                'id' => $project->id,
                'name' => $project->name,
                'short_description' => $project->short_description,
                'description' => $project->description,
                'is_public' => $project->is_public,
            ],
            'files' => $files,
        ];
    }

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
