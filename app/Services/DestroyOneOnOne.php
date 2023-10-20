<?php

namespace App\Services;

use App\Models\OneOnOne;
use App\Models\User;

class DestroyOneOnOne extends BaseService
{
    private OneOnOne $oneOnOne;
    private array $data;

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'one_on_one_id' => 'required|integer|exists:one_on_ones,id',
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

        $user = User::findOrFail($this->data['user_id']);

        $this->oneOnOne = OneOnOne::where('user_id', $user->id)
            ->orWhere('other_user_id', $user->id)
            ->findOrFail($this->data['one_on_one_id']);
    }

    private function destroy(): void
    {
        $this->oneOnOne->delete();
    }
}
