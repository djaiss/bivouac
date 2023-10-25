<?php

namespace App\Services;

use App\Exceptions\OneOnOneAlreadyExistsException;
use App\Models\OneOnOne;
use App\Models\User;

class CreateOneOnOne extends BaseService
{
    private User $user;
    private User $otherUser;
    private OneOnOne $oneOnOne;
    private array $data;

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'other_user_id' => 'required|integer|exists:users,id',
        ];
    }

    public function execute(array $data): OneOnOne
    {
        $this->data = $data;
        $this->validate();
        $this->create();

        return $this->oneOnOne;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->user = User::findOrFail($this->data['user_id']);

        $this->otherUser = User::where('organization_id', $this->user->organization_id)
            ->findOrFail($this->data['other_user_id']);

        $exists = OneOnOne::where('user_id', $this->user->id)
            ->where('other_user_id', $this->otherUser->id)
            ->exists();

        if ($exists) {
            throw new OneOnOneAlreadyExistsException;
        }

        $exists = OneOnOne::where('user_id', $this->otherUser->id)
            ->where('other_user_id', $this->user->id)
            ->exists();

        if ($exists) {
            throw new OneOnOneAlreadyExistsException;
        }
    }

    private function create(): void
    {
        $this->oneOnOne = OneOnOne::create([
            'user_id' => $this->user->id,
            'other_user_id' => $this->otherUser->id,
        ]);
    }
}
