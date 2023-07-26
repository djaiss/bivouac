<?php

namespace App\Http\Controllers\Projects\Tasks;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Reactions\ReactionViewModel;
use App\Models\Project;
use App\Models\Task;
use App\Services\AddReactionToTask;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskReactionController extends Controller
{
    public function store(Request $request, Project $project, Task $task): JsonResponse
    {
        $reaction = (new AddReactionToTask)->execute([
            'user_id' => auth()->user()->id,
            'task_id' => $task->id,
            'emoji' => $request->input('emoji'),
        ]);

        return response()->json([
            'data' => ReactionViewModel::dto($reaction),
        ], 201);
    }
}
