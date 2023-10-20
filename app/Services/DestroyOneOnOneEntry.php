<?php

namespace App\Services;

use App\Models\OneOnOne;
use App\Models\OneOnOneEntry;
use App\Models\User;

class DestroyOneOnOneEntry extends BaseService
{
    private User $user;
    private OneOnOneEntry $oneOnOneEntry;
    private array $data;

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'one_on_one_entry_id' => 'required|integer|exists:one_on_one_entries,id',
        ];
    }

    public function execute(array $data): void
    {
        $this->data = $data;
        $this->validate();
        $this->destroy();
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->user = User::findOrFail($this->data['user_id']);

        $this->oneOnOneEntry = OneOnOneEntry::findOrFail($this->data['one_on_one_entry_id']);

        OneOnOne::where('user_id', $this->user->id)
            ->orWhere('other_user_id', $this->user->id)
            ->findOrFail($this->oneOnOneEntry->oneOnOne->id);
    }

    private function destroy(): void
    {
        $this->oneOnOneEntry->delete();
    }
}
