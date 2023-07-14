<?php

namespace App\Services;

use App\Models\TaskList;
use App\Models\User;

class DestroyTaskList extends BaseService
{
    private TaskList $taskList;
    private array $data;

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'task_list_id' => 'required|integer|exists:task_lists,id',
        ];
    }

    public function execute(array $data): void
    {
        $this->data = $data;
        $this->validate();

        $this->taskList->delete();
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $user = User::findOrFail($this->data['user_id']);

        $this->taskList = TaskList::where('organization_id', $user->organization_id)
            ->findOrFail($this->data['task_list_id']);
    }
}
