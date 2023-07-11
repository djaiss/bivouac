<?php

namespace App\Services;

use App\Models\Task;
use App\Models\TaskList;
use App\Models\User;

class CreateTask extends BaseService
{
    private Task $task;
    private User $user;
    private array $data;

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'task_list_id' => 'required|integer|exists:task_lists,id',
            'title' => 'required|string|max:255',
        ];
    }

    public function execute(array $data): Task
    {
        $this->data = $data;
        $this->validate();
        $this->create();

        return $this->task;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->user = User::findOrFail($this->data['user_id']);
        TaskList::where('organization_id', $this->user->organization_id)
            ->findOrFail($this->data['task_list_id']);
    }

    private function create(): void
    {
        $this->task = Task::create([
            'task_list_id' => $this->data['task_list_id'],
            'title' => $this->data['title'],
        ]);
    }
}
