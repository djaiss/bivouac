<?php

namespace App\Services;

use App\Exceptions\NotEnoughPermissionException;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RemoveMemberFromProject extends BaseService
{
    private Project $project;
    private User $user;
    private User $member;
    private array $data;

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'member_id' => 'required|integer|exists:users,id',
            'project_id' => 'required|integer|exists:projects,id',
        ];
    }

    public function execute(array $data): void
    {
        $this->data = $data;

        $this->validate();
        $this->remove();
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->user = User::findOrFail($this->data['user_id']);
        $this->member = User::where('organization_id', $this->user->organization_id)
            ->findOrFail($this->data['member_id']);

        $this->project = Project::where('organization_id', $this->user->organization_id)
            ->findOrFail($this->data['project_id']);

        if ($this->project->users()->where('user_id', $this->member->id)->doesntExist()) {
            throw new ModelNotFoundException;
        }

        if ($this->project->users()->where('user_id', $this->user->id)->doesntExist()) {
            throw new NotEnoughPermissionException;
        }
    }

    private function remove(): void
    {
        $this->member->projects()->detach($this->project);

        $this->project->updated_at = now();
        $this->project->save();
    }
}
