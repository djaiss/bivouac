<?php

namespace App\Services;

use App\Exceptions\NotEnoughPermissionException;
use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class DestroyCommentOfTask extends BaseService
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
            'comment_id' => 'required|integer|exists:comments,id',
        ];
    }

    public function execute(array $data): void
    {
        $this->data = $data;
        $this->validate();

        $this->destroy();
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

        $this->comment = Comment::where('commentable_id', $this->task->id)
            ->findOrFail($this->data['comment_id']);
    }

    private function destroy(): void
    {
        $this->comment->delete();
    }
}
