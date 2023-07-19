<?php

namespace App\Http\Controllers\Tasks;

use App\Models\Message;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskList;

class TaskListViewModel
{
    public static function dto(TaskList $taskList): array
    {
        $tasks = $taskList->tasks()
            ->get();

        $tasksCount = $tasks->count();
        $completionRate = $tasksCount > 0 ? $tasks->filter(fn (Task $task) => $task->is_completed)->count() / $tasksCount : 0;
        $completionRate = round($completionRate * 100);

        $url = match ($taskList->tasklistable_type) {
            Project::class => '',
            Message::class => route('messages.show', [
                'project' => $taskList->tasklistable->project_id,
                'message' => $taskList->tasklistable->id,
            ]),
            default => '',
        };

        return [
            'id' => $taskList->id,
            'name' => $taskList->name,
            'tasks' => $tasks->map(fn (Task $task) => TaskViewModel::dto($task)),
            'completion_rate' => $completionRate,
            'collapsed' => $taskList->collapsed,
            'parent' => [
                'id' => $taskList->tasklistable->id,
                'title' => $taskList->tasklistable->title,
                'is_project' => $taskList->tasklistable_type === Project::class,
                'url' => $url,
            ],
            'url' => [
                'store' => route('tasks.store'),
                'toggle' => route('task_lists.toggle', $taskList->id),
                'edit' => route('task_lists.edit', [
                    'project' => $taskList->project_id,
                    'taskList' => $taskList->id,
                ]),
                'destroy' => route('task_lists.destroy', [
                    'project' => $taskList->project_id,
                    'taskList' => $taskList->id,
                ]),
            ],
        ];
    }
}
