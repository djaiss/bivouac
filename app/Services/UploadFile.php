<?php

namespace App\Services;

use App\Exceptions\EnvVariablesNotSetException;
use App\Models\File;
use App\Models\User;
use ImageKit\ImageKit;

class UploadFile extends BaseService
{
    private array $data;
    private User $user;
    private File $file;

    /**
     * Upload a file.
     *
     * This doesn't really upload a file though. Upload is handled by Uploadcare.
     * However, we abstract uploads by the File object. This service here takes
     * the payload that Uploadcare sends us back, and maps it into a File object
     * that the clients will consume.
     */
    public function execute(array $data): File
    {
        $this->data = $data;

        $imageKit = new ImageKit(
            'public_EzDvkvJ9MjIa1LGQZmdWeUJH6Q8=',
            'private_h9oiG6J5VFVCIh7erNONoneHAtw=',
            'https://ik.imagekit.io/8dfyonadf',
        );
        // For File Upload
        $uploadFile = $imageKit->uploadFile([
            'file' => $data['file']->path(),
            'fileName' => 'new-file',
        ]);
        dd($uploadFile);

        //$this->save();

        //return $this->file;
    }

    private function validate(): void
    {
        if (is_null(config('app.uploadcare_private_key'))) {
            throw new EnvVariablesNotSetException;
        }

        if (is_null(config('app.uploadcare_public_key'))) {
            throw new EnvVariablesNotSetException;
        }

        $this->validateRules($this->data);
        $this->user = User::where('organization_id', $this->data['organization_id'])
            ->findOrFail($this->data['user_id']);
    }

    private function save(): void
    {
        $this->file = File::create([
            'organization_id' => $this->data['organization_id'],
            'uploader_id' => $this->data['user_id'],
            'uploader_name' => $this->user->name,
            'uuid' => $this->data['uuid'],
            'name' => $this->data['name'],
            'original_url' => $this->data['original_url'],
            'cdn_url' => $this->data['cdn_url'],
            'mime_type' => $this->data['mime_type'],
            'size' => $this->data['size'],
            'type' => $this->data['type'],
        ]);
    }
}
