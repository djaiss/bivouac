<?php

namespace App\Services;

use App\Exceptions\NotEnoughPermissionException;
use App\Models\Message;
use App\Models\Project;
use App\Models\User;

class DestroyMessage extends BaseService
{
    private Message $message;
    private array $data;

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'message_id' => 'required|integer|exists:messages,id',
        ];
    }

    public function execute(array $data): void
    {
        $this->data = $data;
        $this->validate();

        $this->message->comments()->delete();
        $this->message->reactions()->delete();
        $this->message->taskLists()->delete();
        $this->message->delete();
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $user = User::findOrFail($this->data['user_id']);

        $this->message = Message::findOrFail($this->data['message_id']);

        $project = Project::where('organization_id', $user->organization_id)
            ->findOrFail($this->message->project_id);

        if ($project->users()->where('user_id', $user->id)->doesntExist()) {
            throw new NotEnoughPermissionException;
        }
    }
}
