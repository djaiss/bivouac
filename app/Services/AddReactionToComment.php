<?php

namespace App\Services;

use App\Models\Comment;
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

        $this->comment = Comment::where('organization_id', $this->user->organization_id)
            ->findOrFail($this->data['comment_id']);
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
