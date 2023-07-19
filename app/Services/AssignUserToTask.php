<?php

namespace App\Services;

use App\Exceptions\NotEnoughPermissionException;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class AssignUserToTask extends BaseService
{
    private Task $task;
    private User $assignee;
    private User $user;
    private array $data;

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'assignee_id' => 'required|integer|exists:users,id',
            'task_id' => 'required|integer|exists:tasks,id',
        ];
    }

    public function execute(array $data): User
    {
        $this->data = $data;

        $this->validate();
        $this->assign();

        return $this->assignee;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->user = User::findOrFail($this->data['user_id']);
        $this->assignee = User::where('organization_id', $this->user->organization_id)
            ->findOrFail($this->data['assignee_id']);

        $this->task = Task::findOrFail($this->data['task_id']);

        $project = Project::where('organization_id', $this->user->organization_id)
            ->findOrFail($this->task->taskList->project_id);

        if ($project->users()->where('user_id', $this->user->id)->doesntExist() && ! $project->is_public) {
            throw new NotEnoughPermissionException;
        }

        if ($project->users()->where('user_id', $this->assignee->id)->doesntExist() && ! $project->is_public) {
            throw new NotEnoughPermissionException;
        }
    }

    private function assign(): void
    {
        $this->assignee->tasks()->save($this->task);
    }
}
