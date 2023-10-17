<?php

namespace App\Services;

use App\Models\File;
use Illuminate\Support\Facades\Storage;

class UploadFile extends BaseService
{
    /**
     * Upload a file.
     */
    public function execute(array $data): string
    {
        $file = $data['file'];

        $path = Storage::putFileAs(
            'uploads',
            $file,
            $file->getClientOriginalName()
        );

        // returns the full path of the file
        return Storage::path($path);
    }
}
