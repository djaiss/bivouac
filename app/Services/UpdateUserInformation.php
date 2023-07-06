<?php

namespace App\Services;

use App\Exceptions\NotEnoughPermissionException;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class UpdateUserInformation extends BaseService
{
    private array $data;
    private User $user;
    private User $author;

    public function rules(): array
    {
        return [
            'author_id' => 'required|integer|exists:users,id',
            'user_id' => 'required|integer|exists:users,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
        ];
    }

    /**
     * Update the information about the user.
     */
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
        $this->user->first_name = $this->data['first_name'];
        $this->user->last_name = $this->data['last_name'];
        $this->user->save();

        if ($this->user->email !== $this->data['email']) {
            $this->user->email_verified_at = null;
            $this->user->email = $this->data['email'];
            $this->user->save();

            event(new Registered($this->user instanceof MustVerifyEmail));
        }
    }
}
