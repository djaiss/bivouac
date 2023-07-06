<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;

class CreateRole extends BaseService
{
    private Role $role;
    private User $user;
    private array $data;

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'label' => 'required|string|max:255',
        ];
    }

    public function execute(array $data): Role
    {
        $this->data = $data;
        $this->validate();
        $this->create();

        return $this->role;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->user = User::findOrFail($this->data['user_id']);
    }

    private function create(): void
    {
        // determine the new position
        $newPosition = $this->user->organization->roles()
            ->max('position');
        $newPosition++;

        $this->role = Role::create([
            'organization_id' => $this->user->organization_id,
            'label' => $this->data['label'],
            'position' => $newPosition,
        ]);
    }
}
