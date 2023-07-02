<?php

namespace App\Services;

use App\Models\Project;
use App\Models\User;

class AddProjectMember extends BaseService
{
    private Project $project;
    private User $user;
    private array $data;

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'project_id' => 'required|integer|exists:projects,id',
        ];
    }

    public function execute(array $data): Project
    {
        $this->data = $data;
        $this->validate();
        $this->associate();

        return $this->project;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->user = User::findOrFail($this->data['user_id']);
        $this->project = Project::where('organization_id', $this->user->organization_id)
            ->findOrFail($this->data['project_id']);
    }

    private function associate(): void
    {
        $this->project->users()->syncWithoutDetaching($this->user);
    }
}
