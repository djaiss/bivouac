<?php

namespace App\Services;

use App\Exceptions\NotEnoughPermissionException;
use App\Models\Project;
use App\Models\ProjectUpdate;
use App\Models\User;

class DestroyProjectUpdate extends BaseService
{
    private ProjectUpdate $projectUpdate;
    private Project $project;
    private array $data;

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'project_update_id' => 'required|integer|exists:project_updates,id',
        ];
    }

    public function execute(array $data): void
    {
        $this->data = $data;
        $this->validate();

        $this->projectUpdate->delete();

        $this->project->updated_at = now();
        $this->project->save();
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $user = User::findOrFail($this->data['user_id']);

        $this->projectUpdate = ProjectUpdate::findOrFail($this->data['project_update_id']);

        $this->project = Project::where('organization_id', $user->organization_id)
            ->findOrFail($this->projectUpdate->project_id);

        if ($this->project->users()->where('user_id', $user->id)->doesntExist() && ! $this->project->is_public) {
            throw new NotEnoughPermissionException;
        }
    }
}
