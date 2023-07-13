<?php

namespace App\Http\Controllers\Tasks;

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

        return [
            'id' => $taskList->id,
            'name' => $taskList->name,
            'tasks' => $tasks->map(fn (Task $task) => TaskViewModel::dto($task)),
            'completion_rate' => $completionRate,
        ];
    }
}
