<?php

namespace App\Services;

use App\Models\User;

class RegenerateAvatar extends BaseService
{
    private array $data;
    private User $user;

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
        ];
    }

    /**
     * Update the user's default avatar.
     * The default avatar is based on the username.
     * This method creates a new random username so the avatar looks different.
     */
    public function execute(array $data): User
    {
        $this->data = $data;
        $this->validate();
        $this->generate();

        return $this->user;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->user = User::findOrFail($this->data['user_id']);
    }

    private function generate(): void
    {
        $this->user->name_for_avatar = fake()->name;
        $this->user->save();
    }
}
