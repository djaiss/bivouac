<?php

namespace App\Services;

use App\Exceptions\NotEnoughPermissionException;
use App\Models\Comment;
use App\Models\Message;
use App\Models\Project;
use App\Models\Reaction;
use App\Models\User;

class AddReactionToComment extends BaseService
{
    private Reaction $reaction;
    private Comment $comment;
    private User $user;
    private array $data;

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'comment_id' => 'required|integer|exists:comments,id',
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

        $this->comment = Comment::where('commentable_type', Message::class)
            ->findOrFail($this->data['comment_id']);

        $message = Message::findOrFail($this->comment->commentable_id);

        $project = Project::where('organization_id', $this->user->organization_id)
            ->findOrFail($message->project_id);

        if ($project->users()->where('user_id', $this->user->id)->doesntExist() && ! $project->is_public) {
            throw new NotEnoughPermissionException;
        }
    }

    private function create(): void
    {
        $this->reaction = Reaction::create([
            'organization_id' => $this->user->organization_id,
            'user_id' => $this->user->id,
            'emoji' => $this->data['emoji'],
        ]);
    }

    private function associate(): void
    {
        $this->comment->reactions()->save($this->reaction);
    }
}
