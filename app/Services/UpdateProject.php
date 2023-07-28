<?php

namespace App\Services;

use App\Models\Project;
use App\Models\User;

class UpdateProject extends BaseService
{
    private Project $project;
    private User $user;
    private array $data;

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'project_id' => 'required|integer|exists:projects,id',
            'name' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:65535',
            'is_public' => 'required|boolean',
        ];
    }

    public function execute(array $data): Project
    {
        $this->data = $data;
        $this->validate();

        $this->edit();

        return $this->project;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->user = User::findOrFail($this->data['user_id']);
        $this->project = Project::where('organization_id', $this->user->organization_id)
            ->findOrFail($this->data['project_id']);
    }

    private function edit(): void
    {
        $this->project->name = $this->data['name'];
        $this->project->description = $this->valueOrNull($this->data, 'description');
        $this->project->short_description = $this->valueOrNull($this->data, 'short_description');
        $this->project->is_public = $this->data['is_public'];
        $this->project->save();
    }
}
