<?php

namespace App\Services;

use App\Models\Task;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AddFileToTask extends BaseService
{
    private Media $media;
    private Task $task;
    private array $data;

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'task_id' => 'required|integer|exists:tasks,id',
            'file_path' => 'required|string',
        ];
    }

    public function execute(array $data): Media
    {
        $this->data = $data;

        $this->validate();
        $this->move();

        return $this->media;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->task = Task::findOrFail($this->data['task_id']);
    }

    private function move(): void
    {
        $this->media = $this->task
            ->addMedia($this->data['file_path'])
            ->toMediaCollection('files');
    }
}
