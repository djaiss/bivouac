<?php

namespace App\Http\Controllers\Tasks;

use App\Models\Reaction;
use App\Models\Task;

class TaskViewModel
{
    public static function dto(Task $task): array
    {
        return [
            'id' => $task->id,
            'task' => $task->title,
            'url' => [
                'destroy' => route('tasks.destroy', $task),
            ],
        ];
    }
}
