<?php

namespace App\Services;

use App\Models\TaskList;
use App\Models\User;

class UpdateTaskList extends BaseService
{
    private TaskList $taskList;
    private User $user;
    private array $data;

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'task_list_id' => 'required|integer|exists:task_lists,id',
            'name' => 'nullable|string|max:255',
        ];
    }

    public function execute(array $data): TaskList
    {
        $this->data = $data;
        $this->validate();
        $this->edit();

        return $this->taskList;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->user = User::findOrFail($this->data['user_id']);

        $this->taskList = TaskList::where('organization_id', $this->user->organization_id)
            ->findOrFail($this->data['task_list_id']);
    }

    private function edit(): void
    {
        $this->taskList->name = $this->valueOrNull($this->data, 'name');
        $this->taskList->save();
    }
}
