<?php

namespace App\Services;

use App\Exceptions\NotEnoughPermissionException;
use App\Models\Project;
use App\Models\ProjectResource;
use App\Models\User;

class UpdateProjectResource extends BaseService
{
    private ProjectResource $projectResource;
    private User $user;
    private Project $project;
    private array $data;

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'project_resource_id' => 'required|integer|exists:project_resources,id',
            'name' => 'nullable|string|max:255',
            'link' => 'required|string|max:255',
        ];
    }

    public function execute(array $data): ProjectResource
    {
        $this->data = $data;
        $this->validate();
        $this->edit();

        return $this->projectResource;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->user = User::findOrFail($this->data['user_id']);

        $this->projectResource = ProjectResource::findOrFail($this->data['project_resource_id']);

        $this->project = Project::where('organization_id', $this->user->organization_id)
            ->findOrFail($this->projectResource->project_id);

        if ($this->project->users()->where('user_id', $this->user->id)->doesntExist() && ! $this->project->is_public) {
            throw new NotEnoughPermissionException;
        }
    }

    private function edit(): void
    {
        $this->projectResource->link = $this->data['link'];
        $this->projectResource->name = $this->valueOrNull($this->data, 'name');
        $this->projectResource->save();

        $this->project->updated_at = now();
        $this->project->save();
    }
}
