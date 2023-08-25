<?php

namespace App\Services;

use App\Exceptions\NotEnoughPermissionException;
use App\Models\Project;
use App\Models\ProjectResource;
use App\Models\User;

class CreateProjectResource extends BaseService
{
    private ProjectResource $projectResource;
    private User $user;
    private array $data;

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'project_id' => 'required|integer|exists:projects,id',
            'name' => 'nullable|string|max:255',
            'link' => 'required|string|max:255',
        ];
    }

    public function execute(array $data): ProjectResource
    {
        $this->data = $data;
        $this->validate();
        $this->create();

        return $this->projectResource;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->user = User::findOrFail($this->data['user_id']);
        $project = Project::where('organization_id', $this->user->organization_id)
            ->findOrFail($this->data['project_id']);

        if ($project->users()->where('user_id', $this->user->id)->doesntExist() && ! $project->is_public) {
            throw new NotEnoughPermissionException;
        }
    }

    private function create(): void
    {
        $this->projectResource = ProjectResource::create([
            'project_id' => $this->data['project_id'],
            'name' => $this->valueOrNull($this->data, 'name'),
            'link' => $this->data['link'],
        ]);
    }
}
