<?php

namespace App\Services;

use App\Exceptions\NotEnoughPermissionException;
use App\Models\User;

class UpdateUserPermission extends BaseService
{
    private array $data;
    private User $user;
    private User $author;

    public function rules(): array
    {
        return [
            'author_id' => 'required|integer|exists:users,id',
            'user_id' => 'required|integer|exists:users,id',
            'permissions' => 'required|string|max:255',
        ];
    }

    public function execute(array $data): User
    {
        $this->data = $data;
        $this->validate();
        $this->update();

        return $this->user;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->user = User::findOrFail($this->data['user_id']);
        $this->author = User::findOrFail($this->data['user_id']);

        if ($this->user->organization_id !== $this->author->organization_id) {
            throw new NotEnoughPermissionException;
        }
    }

    private function update(): void
    {
        $this->user->permissions = $this->data['permissions'];
        $this->user->save();
    }
}
