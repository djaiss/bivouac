<?php

namespace App\Services;

use App\Exceptions\NotEnoughPermissionException;
use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class UpdateCommentOfTask extends BaseService
{
    private Comment $comment;
    private Task $task;
    private User $user;
    private Project $project;
    private array $data;

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'task_id' => 'required|integer|exists:tasks,id',
            'comment_id' => 'required|integer|exists:comments,id',
            'body' => 'nullable|string|max:65535',
        ];
    }

    public function execute(array $data): Comment
    {
        $this->data = $data;
        $this->validate();

        $this->edit();

        return $this->comment;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->user = User::findOrFail($this->data['user_id']);

        $this->task = Task::findOrFail($this->data['task_id']);

        $this->project = Project::where('organization_id', $this->user->organization_id)
            ->findOrFail($this->task->taskList->project_id);

        if ($this->project->users()->where('user_id', $this->user->id)->doesntExist() && ! $this->project->is_public) {
            throw new NotEnoughPermissionException;
        }

        $this->comment = Comment::where('commentable_id', $this->task->id)
            ->findOrFail($this->data['comment_id']);
    }

    private function edit(): void
    {
        $this->comment->body = $this->data['body'];
        $this->comment->save();

        $this->project->updated_at = now();
        $this->project->save();
    }
}
