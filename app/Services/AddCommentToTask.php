<?php

namespace App\Services;

use App\Exceptions\NotEnoughPermissionException;
use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class AddCommentToTask extends BaseService
{
    private Comment $comment;
    private Task $task;
    private User $user;
    private array $data;

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'task_id' => 'required|integer|exists:tasks,id',
            'body' => 'nullable|string|max:65535',
        ];
    }

    public function execute(array $data): Comment
    {
        $this->data = $data;
        $this->validate();
        $this->create();
        $this->associate();

        return $this->comment;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->user = User::findOrFail($this->data['user_id']);

        $this->task = Task::findOrFail($this->data['task_id']);

        $project = Project::where('organization_id', $this->user->organization_id)
            ->findOrFail($this->task->taskList->project_id);

        if ($project->users()->where('user_id', $this->user->id)->doesntExist() && ! $project->is_public) {
            throw new NotEnoughPermissionException;
        }
    }

    private function create(): void
    {
        $this->comment = Comment::create([
            'organization_id' => $this->user->organization_id,
            'author_id' => $this->user->id,
            'author_name' => $this->user->name,
            'body' => $this->data['body'],
        ]);
    }

    private function associate(): void
    {
        $this->task->comments()->save($this->comment);
    }
}
