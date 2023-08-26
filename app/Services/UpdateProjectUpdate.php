<?php

namespace App\Services;

use App\Exceptions\NotEnoughPermissionException;
use App\Models\Project;
use App\Models\ProjectUpdate;
use App\Models\User;

class UpdateProjectUpdate extends BaseService
{
    private ProjectUpdate $projectUpdate;
    private User $user;
    private Project $project;
    private array $data;

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'project_update_id' => 'required|integer|exists:project_updates,id',
            'content' => 'required|string|max:65535',
        ];
    }

    public function execute(array $data): ProjectUpdate
    {
        $this->data = $data;
        $this->validate();
        $this->edit();

        return $this->projectUpdate;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->user = User::findOrFail($this->data['user_id']);

        $this->projectUpdate = ProjectUpdate::findOrFail($this->data['project_update_id']);

        $this->project = Project::where('organization_id', $this->user->organization_id)
            ->findOrFail($this->projectUpdate->project_id);

        if ($this->project->users()->where('user_id', $this->user->id)->doesntExist() && ! $this->project->is_public) {
            throw new NotEnoughPermissionException;
        }
    }

    private function edit(): void
    {
        $this->projectUpdate->content = $this->data['content'];
        $this->projectUpdate->save();

        $this->project->updated_at = now();
        $this->project->save();
    }
}
