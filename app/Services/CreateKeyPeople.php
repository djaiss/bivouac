<?php

namespace App\Services;

use App\Models\KeyPeople;
use App\Models\Project;
use App\Models\User;

class CreateKeyPeople extends BaseService
{
    private KeyPeople $keyPeople;
    private User $user;
    private Project $project;
    private array $data;

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'project_id' => 'required|integer|exists:projects,id',
            'role' => 'required|string|max:255',
        ];
    }

    public function execute(array $data): KeyPeople
    {
        $this->data = $data;
        $this->validate();
        $this->create();

        return $this->keyPeople;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->user = User::findOrFail($this->data['user_id']);
        $this->project = Project::where('organization_id', $this->user->organization_id)
            ->findOrFail($this->data['project_id']);
    }

    private function create(): void
    {
        $this->keyPeople = KeyPeople::create([
            'user_id' => $this->data['user_id'],
            'project_id' => $this->data['project_id'],
            'role' => $this->data['role'],
        ]);

        $this->project->updated_at = now();
        $this->project->save();
    }
}
