<?php

namespace App\Services;

use App\Exceptions\NotEnoughPermissionException;
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
}
