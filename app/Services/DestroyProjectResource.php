<?php

namespace App\Services;

use App\Exceptions\NotEnoughPermissionException;
use App\Models\Project;
use App\Models\ProjectResource;
use App\Models\User;

class DestroyProjectResource extends BaseService
{
    private ProjectResource $projectResource;
    private Project $project;
    private array $data;

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'project_resource_id' => 'required|integer|exists:project_resources,id',
        ];
    }

    public function execute(array $data): void
    {
        $this->data = $data;
        $this->validate();

        $this->projectResource->delete();

        $this->project->updated_at = now();
        $this->project->save();
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $user = User::findOrFail($this->data['user_id']);

        $this->projectResource = ProjectResource::findOrFail($this->data['project_resource_id']);

        $this->project = Project::where('organization_id', $user->organization_id)
            ->findOrFail($this->projectResource->project_id);

        if ($this->project->users()->where('user_id', $user->id)->doesntExist() && ! $this->project->is_public) {
            throw new NotEnoughPermissionException;
        }
    }
}
