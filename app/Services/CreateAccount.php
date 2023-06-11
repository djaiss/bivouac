<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAccount extends BaseService
{
    private User $user;

    private array $data;

    private Organization $organization;

    public function rules(): array
    {
        return [
            'email' => 'required|unique:users,email|email|max:255',
            'password' => 'required|min:6|max:60',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'organization_name' => 'required|string|max:255',
        ];
    }

    /**
     * Create an account for the user.
     */
    public function execute(array $data): User
    {
        $this->data = $data;

        $this->validate();
        $this->createOrganization();
        $this->createUser();

        return $this->user;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);
    }

    private function createUser(): void
    {
        $this->user = User::create([
            'first_name' => $this->data['first_name'],
            'last_name' => $this->data['last_name'],
            'email' => $this->data['email'],
            'password' => Hash::make($this->data['password']),
            'organization_id' => $this->organization->id,
        ]);
    }

    private function createOrganization(): void
    {
        $this->organization = Organization::create([
            'name' => $this->data['organization_name'],
        ]);
    }
}
