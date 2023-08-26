<?php

namespace App\Services;

use App\Exceptions\NotEnoughPermissionException;
use App\Models\Comment;
use App\Models\Message;
use App\Models\Project;
use App\Models\User;

class DestroyCommentOfMessage extends BaseService
{
    private Comment $comment;
    private Message $message;
    private User $user;
    private Project $project;
    private array $data;

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'message_id' => 'required|integer|exists:messages,id',
            'comment_id' => 'required|integer|exists:comments,id',
        ];
    }

    public function execute(array $data): void
    {
        $this->data = $data;
        $this->validate();

        $this->destroy();
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

        $this->comment = Comment::where('commentable_id', $this->message->id)
            ->findOrFail($this->data['comment_id']);
    }

    private function destroy(): void
    {
        $this->comment->delete();

        $this->project->updated_at = now();
        $this->project->save();
    }
}
