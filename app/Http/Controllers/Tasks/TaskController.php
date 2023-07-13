<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Services\DestroyTask;
use App\Services\UpdateTask;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function update(Request $request, Task $task): JsonResponse
    {
        $task = (new UpdateTask)->execute([
            'user_id' => auth()->user()->id,
            'task_id' => $task->id,
            'title' => $request->input('title'),
            'is_completed' => $request->input('is_completed'),
        ]);

        $tasksCount = $task->taskList->tasks()->count();
        $completionRate = $tasksCount > 0 ? $task->taskList->tasks->filter(fn (Task $task) => $task->is_completed)->count() / $tasksCount : 0;
        $completionRate = round($completionRate * 100);

        return response()->json([
            'data' => [
                'task' => TaskViewModel::dto($task),
                'completion_rate' => $completionRate,
            ],
        ], 200);
    }

    public function destroy(Task $task): JsonResponse
    {
        (new DestroyTask)->execute([
            'user_id' => auth()->user()->id,
            'task_id' => $task->id,
        ]);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
