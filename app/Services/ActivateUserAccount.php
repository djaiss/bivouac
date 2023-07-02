<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ActivateUserAccount extends BaseService
{
    private array $data;
    private User $author;

    public function rules(): array
    {
        return [
            'invitation_code' => 'required|string|exists:users,invitation_code',
            'password' => 'required|min:6|max:60',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
        ];
    }

    public function execute(array $data): User
    {
        $this->data = $data;
        $this->validate();
        $this->create();

        return $this->author;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author = User::where('invitation_code', $this->data['invitation_code'])->firstOrFail();
    }

    private function create(): void
    {
        $this->author->first_name = $this->data['first_name'];
        $this->author->last_name = $this->data['last_name'];
        $this->author->password = Hash::make($this->data['password']);
        $this->author->email_verified_at = now();
        $this->author->save();
    }
}
