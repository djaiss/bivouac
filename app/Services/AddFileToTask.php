<?php

namespace App\Services;

use App\Models\File;
use App\Models\Task;
use App\Models\User;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AddFileToTask extends BaseService
{
    private Media $media;
    private Task $task;
    private array $data;
    private User $user;

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
        $this->createFile();

        return $this->media;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->user = User::findOrFail($this->data['user_id']);

        $this->task = Task::findOrFail($this->data['task_id']);
    }

    private function move(): void
    {
        $this->media = $this->task
            ->addMedia($this->data['file_path'])
            ->toMediaCollection('files');
    }

    /**
     * The Spatie media library package, which we use to store the files, doesn't
     * let us store the project id or the organization id. We need to have
     * an intermediate table that will store those information for us.
     */
    private function createFile(): void
    {
        File::create([
            'organization_id' => $this->user->organization_id,
            'project_id' => $this->task->taskList->project_id,
            'media_id' => $this->media->id,
        ]);
    }
}
