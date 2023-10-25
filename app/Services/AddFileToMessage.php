<?php

namespace App\Services;

use App\Exceptions\NotEnoughPermissionException;
use App\Models\File;
use App\Models\Message;
use App\Models\Project;
use App\Models\User;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AddFileToMessage extends BaseService
{
    private Media $media;
    private Message $message;
    private Project $project;
    private User $user;
    private array $data;

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'message_id' => 'required|integer|exists:messages,id',
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

        $this->message = Message::findOrFail($this->data['message_id']);

        $this->project = Project::where('organization_id', $this->user->organization_id)
            ->findOrFail($this->message->project_id);

        if ($this->project->users()->where('user_id', $this->user->id)->doesntExist() && ! $this->project->is_public) {
            throw new NotEnoughPermissionException;
        }
    }

    private function move(): void
    {
        $this->media = $this->message
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
            'project_id' => $this->project->id,
            'media_id' => $this->media->id,
        ]);
    }
}
