<?php

namespace App\Services;

use App\Models\Office;
use App\Models\Organization;
use App\Models\User;

class CreateOffice extends BaseService
{
    private Office $office;

    private User $user;

    private array $data;

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'name' => 'required|string|max:255',
            'is_main_office' => 'required|boolean',
        ];
    }

    public function execute(array $data): Office
    {
        $this->data = $data;
        $this->validate();

        $this->office = Office::create([
            'organization_id' => $this->user->organization_id,
            'name' => $data['name'],
            'is_main_office' => $data['is_main_office'],
        ]);

        $this->toggleMainOfficeForAllTheOtherOffices();

        return $this->office;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->user = User::findOrFail($this->data['user_id']);
    }

    private function toggleMainOfficeForAllTheOtherOffices(): void
    {
        if ($this->data['is_main_office']) {
            Office::where('organization_id', $this->user->organization_id)
                ->whereNot('id', $this->office->id)
                ->update([
                    'is_main_office' => false,
                ]);
        }
    }
}
