<?php

namespace App\Services;

use App\Exceptions\NotEnoughPermissionException;
use App\Models\Message;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UpdateMessage extends BaseService
{
    private Message $message;
    private User $user;
    private array $data;

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'message_id' => 'required|integer|exists:messages,id',
            'title' => 'required|string|max:255',
            'body' => 'nullable|string|max:65535',
        ];
    }

    public function execute(array $data): Message
    {
        $this->data = $data;
        $this->validate();
        $this->edit();
        $this->resetReadStatus();
        $this->markAsRead();

        return $this->message;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->user = User::findOrFail($this->data['user_id']);

        $this->message = Message::findOrFail($this->data['message_id']);

        $project = Project::where('organization_id', $this->user->organization_id)
            ->findOrFail($this->message->project_id);

        if ($project->users()->where('user_id', $this->user->id)->doesntExist()) {
            throw new NotEnoughPermissionException;
        }
    }

    private function edit(): void
    {
        $this->message->title = $this->data['title'];
        $this->message->body = $this->valueOrNull($this->data, 'body');
        $this->message->save();
    }

    private function resetReadStatus(): void
    {
        DB::table('message_read_status')
            ->where('message_id', $this->message->id)
            ->delete();
    }

    private function markAsRead(): void
    {
        (new MarkMessageAsRead)->execute([
            'user_id' => $this->user->id,
            'message_id' => $this->message->id,
        ]);
    }
}
