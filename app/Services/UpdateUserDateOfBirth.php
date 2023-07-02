<?php

namespace App\Services;

use App\Exceptions\NotEnoughPermissionException;
use App\Models\User;

class UpdateUserDateOfBirth extends BaseService
{
    private array $data;
    private User $user;
    private User $author;

    public function rules(): array
    {
        return [
            'author_id' => 'required|integer|exists:users,id',
            'user_id' => 'required|integer|exists:users,id',
            'born_at' => 'required|date_format:Y-m-d',
            'age_preferences' => 'required|string',
        ];
    }

    /**
     * Update the user's date of birth.
     */
    public function execute(array $data): User
    {
        $this->data = $data;
        $this->validate();

        $this->user->born_at = $data['born_at'];
        $this->user->age_preferences = $data['age_preferences'];
        $this->user->save();

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
}
