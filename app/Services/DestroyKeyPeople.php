<?php

namespace App\Services;

use App\Models\KeyPeople;
use App\Models\Project;
use App\Models\User;

class DestroyKeyPeople extends BaseService
{
    private KeyPeople $keyPeople;
    private Project $project;
    private array $data;

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'key_people_id' => 'required|integer|exists:key_people,id',
        ];
    }

    public function execute(array $data): void
    {
        $this->data = $data;
        $this->validate();

        $this->keyPeople->delete();

        $this->project->updated_at = now();
        $this->project->save();
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        User::findOrFail($this->data['user_id']);

        $this->keyPeople = KeyPeople::findOrFail($this->data['key_people_id']);
        $this->project = $this->keyPeople->project;
    }
}
