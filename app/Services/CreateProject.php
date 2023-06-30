<?php

namespace App\Services;

use App\Models\Project;
use App\Models\User;

class CreateProject extends BaseService
{
    private Project $project;

    private User $user;

    private array $data;

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:65535',
            'is_public' => 'required|boolean',
        ];
    }

    public function execute(array $data): Project
    {
        $this->data = $data;
        $this->validate();
        $this->create();
        $this->associateUser();

        return $this->project;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->user = User::findOrFail($this->data['user_id']);
    }

    private function create(): void
    {
        $this->project = Project::create([
            'organization_id' => $this->user->organization_id,
            'created_by_user_id' => $this->user->id,
            'created_by_user_name' => $this->user->name,
            'name' => $this->data['name'],
            'description' => $this->valueOrNull($this->data, 'description'),
            'is_public' => $this->data['is_public'],
        ]);
    }

    private function associateUser(): void
    {
        $this->project->users()->syncWithoutDetaching($this->user);
    }
}
