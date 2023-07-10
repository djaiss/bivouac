<?php

namespace App\Services;

use App\Models\Reaction;
use App\Models\User;

class DestroyReaction extends BaseService
{
    private Reaction $reaction;
    private User $user;
    private array $data;

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'reaction_id' => 'required|integer|exists:reactions,id',
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

        $this->reaction = Reaction::where('user_id', $this->user->id)
            ->findOrFail($this->data['reaction_id']);
    }

    private function destroy(): void
    {
        $this->reaction->delete();
    }
}
