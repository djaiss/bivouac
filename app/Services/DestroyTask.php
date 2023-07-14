<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DestroyTask extends BaseService
{
    private Task $task;
    private array $data;

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'task_id' => 'required|integer|exists:tasks,id',
        ];
    }

    public function execute(array $data): void
    {
        $this->data = $data;
        $this->validate();

        $this->task->delete();
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $user = User::findOrFail($this->data['user_id']);
        $this->task = Task::findOrFail($this->data['task_id']);

        if ($this->task->taskList->organization_id !== $user->organization_id) {
            throw new ModelNotFoundException;
        }
    }
}
