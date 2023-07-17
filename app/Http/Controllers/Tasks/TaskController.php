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
    public function store(Request $request): JsonResponse
    {
        // a default task list is always created when we create a message
        // so we need to use that one to store a task
        $taskList = $message->taskLists()->first();

        $task = (new CreateTask)->execute([
            'user_id' => auth()->user()->id,
            'task_list_id' => $taskList->id,
            'title' => $request->input('title'),
        ]);

        $tasksCount = $taskList->tasks()->count();
        $completionRate = $tasksCount > 0 ? $taskList->tasks->filter(fn (Task $task) => $task->is_completed)->count() / $tasksCount : 0;
        $completionRate = round($completionRate * 100);

        return response()->json([
            'data' => [
                'task' => TaskViewModel::dto($task),
                'completion_rate' => $completionRate,
            ],
        ], 201);
    }

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
