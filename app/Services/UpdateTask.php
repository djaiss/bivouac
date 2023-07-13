<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UpdateTask extends BaseService
{
    private Task $task;
    private User $user;
    private array $data;

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'task_id' => 'required|integer|exists:tasks,id',
            'title' => 'required|string|max:255',
            'is_completed' => 'required|boolean',
        ];
    }

    public function execute(array $data): Task
    {
        $this->data = $data;
        $this->validate();
        $this->edit();

        return $this->task;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->user = User::findOrFail($this->data['user_id']);
        $this->task = Task::findOrFail($this->data['task_id']);

        if ($this->task->taskList->organization_id !== $this->user->organization_id) {
            throw new ModelNotFoundException;
        }
    }

    private function edit(): void
    {
        $this->task->title = $this->data['title'];
        $this->task->is_completed = $this->data['is_completed'];
        $this->task->save();
    }
}
