<?php

namespace App\Services;

use App\Models\OneOnOne;
use App\Models\OneOnOneEntry;
use App\Models\User;
use Carbon\Carbon;

class ToggleOneOnOneEntry extends BaseService
{
    private OneOnOneEntry $oneOnOneEntry;
    private User $user;
    private array $data;

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'one_on_one_entry_id' => 'required|integer|exists:one_on_one_entries,id',
        ];
    }

    public function execute(array $data): OneOnOneEntry
    {
        $this->data = $data;
        $this->validate();
        $this->toggle();

        return $this->oneOnOneEntry;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->user = User::findOrFail($this->data['user_id']);

        $this->oneOnOneEntry = OneOnOneEntry::findOrFail($this->data['one_on_one_entry_id']);

        OneOnOne::where('user_id', $this->user->id)
            ->orWhere('other_user_id', $this->user->id)
            ->findOrFail($this->oneOnOneEntry->one_on_one_id);
    }

    private function toggle(): void
    {
        if ($this->oneOnOneEntry->checked_at) {
            $this->oneOnOneEntry->checked_at = null;
        } else {
            $this->oneOnOneEntry->checked_at = Carbon::now();
        }

        $this->oneOnOneEntry->save();
    }
}
