<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;
use App\Models\TaskList;
use App\Services\ToggleTaskList;
use Illuminate\Http\JsonResponse;

class TaskListController extends Controller
{
    public function toggle(TaskList $taskList): JsonResponse
    {
        (new ToggleTaskList)->execute([
            'user_id' => auth()->user()->id,
            'task_list_id' => $taskList->id,
        ]);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
