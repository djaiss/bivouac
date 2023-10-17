<?php

namespace App\Services;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

class DestroyFile extends BaseService
{
    private Media $media;
    private array $data;

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'media_id' => 'required|integer|exists:media,id',
        ];
    }

    public function execute(array $data): void
    {
        $this->data = $data;
        $this->validate();

        $this->media->delete();
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->media = Media::findOrFail($this->data['media_id']);
    }
}
