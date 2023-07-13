<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;
use App\Models\Reaction;
use App\Models\Task;
use App\Services\DestroyReaction;
use App\Services\DestroyTask;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function destroy(Request $request, Task $task): JsonResponse
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
