<?php

namespace App\Services;

use App\Models\Project;
use App\Models\User;

class DestroyProject extends BaseService
{
    private Project $project;

    private array $data;

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'project_id' => 'required|integer|exists:projects,id',
        ];
    }

    public function execute(array $data): void
    {
        $this->data = $data;
        $this->validate();

        $this->project->delete();
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $user = User::findOrFail($this->data['user_id']);
        $this->project = Project::where('organization_id', $user->organization_id)
            ->findOrFail($this->data['project_id']);
    }
}
