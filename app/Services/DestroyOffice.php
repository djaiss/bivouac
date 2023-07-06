<?php

namespace App\Services;

use App\Models\Office;
use App\Models\User;

class DestroyOffice extends BaseService
{
    private Office $office;
    private array $data;

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'office_id' => 'required|integer|exists:offices,id',
        ];
    }

    public function execute(array $data): void
    {
        $this->data = $data;
        $this->validate();

        $this->office->delete();
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $user = User::findOrFail($this->data['user_id']);
        $this->office = Office::where('organization_id', $user->organization_id)
            ->findOrFail($this->data['office_id']);
    }
}
