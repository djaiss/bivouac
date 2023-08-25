<?php

namespace App\Services;

use App\Exceptions\NotEnoughPermissionException;
use App\Models\Project;
use App\Models\ProjectUpdate;
use App\Models\User;

class CreateProjectUpdate extends BaseService
{
    private Project $project;
    private ProjectUpdate $projectUpdate;
    private User $user;
    private array $data;

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'project_id' => 'required|integer|exists:projects,id',
            'content' => 'required|string|max:65535',
        ];
    }

    public function execute(array $data): ProjectUpdate
    {
        $this->data = $data;
        $this->validate();
        $this->create();

        return $this->projectUpdate;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->user = User::findOrFail($this->data['user_id']);

        $this->project = Project::where('organization_id', $this->user->organization_id)
            ->findOrFail($this->data['project_id']);

        if ($this->project->users()->where('user_id', $this->user->id)->doesntExist() && ! $this->project->is_public) {
            throw new NotEnoughPermissionException;
        }
    }

    private function create(): void
    {
        $this->projectUpdate = ProjectUpdate::create([
            'project_id' => $this->project->id,
            'author_id' => $this->user->id,
            'author_name' => $this->user->name,
            'content' => $this->data['content'],
        ]);
    }
}
