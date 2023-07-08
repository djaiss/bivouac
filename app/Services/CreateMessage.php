<?php

namespace App\Services;

use App\Exceptions\NotEnoughPermissionException;
use App\Models\Message;
use App\Models\Project;
use App\Models\User;

class CreateMessage extends BaseService
{
    private Message $message;
    private User $user;
    private array $data;

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'project_id' => 'required|integer|exists:projects,id',
            'title' => 'required|string|max:255',
            'body' => 'nullable|string|max:65535',
        ];
    }

    public function execute(array $data): Message
    {
        $this->data = $data;
        $this->validate();
        $this->create();

        return $this->message;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->user = User::findOrFail($this->data['user_id']);
        $project = Project::where('organization_id', $this->user->organization_id)
            ->findOrFail($this->data['project_id']);

        if ($project->users()->where('user_id', $this->user->id)->doesntExist()) {
            throw new NotEnoughPermissionException;
        }
    }

    private function create(): void
    {
        $this->message = Message::create([
            'project_id' => $this->data['project_id'],
            'author_id' => $this->user->id,
            'author_name' => $this->user->name,
            'title' => $this->data['title'],
            'body' => $this->valueOrNull($this->data, 'body'),
        ]);
    }
}
