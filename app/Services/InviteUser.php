<?php

namespace App\Services;

use App\Exceptions\NotEnoughPermissionException;
use App\Mail\UserInvited;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class InviteUser extends BaseService
{
    private array $data;

    private User $user;
    private User $author;

    public function rules(): array
    {
        return [
            'author_id' => 'required|integer|exists:users,id',
            'email' => 'required|string|email|max:255|unique:' . User::class,
        ];
    }

    /**
     * Invite someone to the organization using the email address provided.
     */
    public function execute(array $data): User
    {
        $this->data = $data;
        $this->validate();
        $this->create();
        $this->sendEmail();

        return $this->user;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author = User::findOrFail($this->data['author_id']);

        if ($this->author->permissions === User::ROLE_USER) {
            throw new NotEnoughPermissionException;
        }
    }

    private function create(): void
    {
        $this->user = User::create([
            'email' => $this->data['email'],
            'permissions' => User::ROLE_USER,
            'name_for_avatar' => fake()->name,
            'organization_id' => $this->author->organization_id,
            'invitation_code' => (string) Str::uuid(),
        ]);
    }

    private function sendEmail(): void
    {
        Mail::to($this->user->email)
            ->queue(new UserInvited($this->user, $this->author));
    }
}
