<?php

namespace App\Services;

use App\Exceptions\NotEnoughPermissionException;
use App\Models\Message;
use App\Models\Project;
use App\Models\Reaction;
use App\Models\User;

class AddReactionToMessage extends BaseService
{
    private Reaction $reaction;
    private Message $message;
    private User $user;
    private array $data;

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'message_id' => 'required|integer|exists:messages,id',
            'emoji' => 'nullable|string|max:255',
        ];
    }

    public function execute(array $data): Reaction
    {
        $this->data = $data;

        $this->validate();
        $this->create();
        $this->associate();

        return $this->reaction;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->user = User::findOrFail($this->data['user_id']);

        $this->message = Message::findOrFail($this->data['message_id']);

        $project = Project::where('organization_id', $this->user->organization_id)
            ->findOrFail($this->message->project_id);

        if ($project->users()->where('user_id', $this->user->id)->doesntExist() && ! $project->is_public) {
            throw new NotEnoughPermissionException;
        }
    }

    private function create(): void
    {
        $this->reaction = Reaction::create([
            'message_id' => $this->data['message_id'],
            'organization_id' => $this->user->organization_id,
            'author_id' => $this->user->id,
            'author_name' => $this->user->name,
            'emoji' => $this->data['emoji'],
        ]);
    }

    private function associate(): void
    {
        $this->message->reactions()->save($this->reaction);
    }
}
