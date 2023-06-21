<?php

namespace App\Services;

use App\Exceptions\CantDeleteHimselfException;
use App\Exceptions\NotEnoughPermissionException;
use App\Models\User;

class DestroyUser extends BaseService
{
    private array $data;

    private User $user;
    private User $author;

    public function rules(): array
    {
        return [
            'author_id' => 'required|integer|exists:users,id',
            'user_id' => 'required|integer|exists:users,id',
        ];
    }

    /**
     * Delete a user.
     * A user can only be deleted by an administrator or account manager.
     * A user can delete himself.
     */
    public function execute(array $data): void
    {
        $this->data = $data;
        $this->validate();
        $this->destroy();
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        if ($this->data['author_id'] === $this->data['user_id']) {
            throw new CantDeleteHimselfException;
        }

        $this->author = User::findOrFail($this->data['author_id']);

        $this->user = User::where('organization_id', $this->author->organization_id)
            ->findOrFail($this->data['user_id']);

        if ($this->author->permissions === User::ROLE_USER) {
            throw new NotEnoughPermissionException;
        }
    }

    private function destroy(): void
    {
        $this->user->delete();
    }
}
