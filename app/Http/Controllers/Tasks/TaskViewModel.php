<?php

namespace App\Http\Controllers\Tasks;

use App\Models\Task;

class TaskViewModel
{
    public static function dto(Task $task): array
    {
        return [
            'id' => $task->id,
            'title' => $task->title,
            'is_completed' => $task->is_completed,
            'url' => [
                'update' => route('tasks.update', $task),
                'destroy' => route('tasks.destroy', $task),
            ],
        ];
    }
}
