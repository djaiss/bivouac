<?php

namespace App\Services;

use App\Models\OneOnOne;
use App\Models\OneOnOneEntry;
use App\Models\User;

class CreateOneOnOneEntry extends BaseService
{
    private User $user;
    private OneOnOne $oneOnOne;
    private OneOnOneEntry $oneOnOneEntry;
    private array $data;

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'one_on_one_id' => 'required|integer|exists:one_on_ones,id',
            'body' => 'required|string',
        ];
    }

    public function execute(array $data): OneOnOneEntry
    {
        $this->data = $data;
        $this->validate();
        $this->create();

        return $this->oneOnOneEntry;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->user = User::findOrFail($this->data['user_id']);

        $this->oneOnOne = OneOnOne::where('user_id', $this->user->id)
            ->orWhere('other_user_id', $this->user->id)
            ->findOrFail($this->data['one_on_one_id']);
    }

    private function create(): void
    {
        $this->oneOnOneEntry = OneOnOneEntry::create([
            'one_on_one_id' => $this->data['one_on_one_id'],
            'user_id' => $this->user->id,
            'body' => $this->data['body'],
        ]);
    }
}
