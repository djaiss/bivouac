<?php

namespace App\Services;

use App\Models\TaskList;
use App\Models\User;

class CreateTaskList extends BaseService
{
    private TaskList $taskList;
    private User $user;
    private array $data;

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'name' => 'nullable|string|max:255',
        ];
    }

    public function execute(array $data): TaskList
    {
        $this->data = $data;
        $this->validate();
        $this->create();

        return $this->taskList;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->user = User::findOrFail($this->data['user_id']);
    }

    private function create(): void
    {
        $this->taskList = TaskList::create([
            'organization_id' => $this->user->organization_id,
            'name' => $this->valueOrNull($this->data, 'name'),
        ]);
    }
}
