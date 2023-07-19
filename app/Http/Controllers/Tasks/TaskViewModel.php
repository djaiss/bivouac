<?php

namespace App\Http\Controllers\Tasks;

use App\Models\Task;
use App\Models\User;

class TaskViewModel
{
    public static function show(Task $task): array
    {
        $taskList = $task->taskList;

        return [
            'task' => self::dto($task),
            'project' => [
                'name' => $taskList->project->name,
            ],
            'breadcrumb' => [
                'projects' => route('projects.index'),
                'project' => route('projects.show', [
                    'project' => $taskList->project_id,
                ]),
                'tasks' => route('tasks.index', [
                    'project' => $taskList->project_id,
                ]),
            ],
        ];
    }

    public static function dto(Task $task): array
    {
        $assignees = $task->users()
            ->get()
            ->map(fn (User $user) => [
                'id' => $user->id,
                'name' => $user->name,
                'avatar' => $user->avatar,
                'url' => route('users.show', $user),
            ]);

        return [
            'id' => $task->id,
            'title' => $task->title,
            'is_completed' => $task->is_completed,
            'assignees' => $assignees,
            'url' => [
                'show' => route('tasks.show', $task),
                'update' => route('tasks.update', $task),
                'destroy' => route('tasks.destroy', $task),
            ],
        ];
    }
}
