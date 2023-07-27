<?php

namespace App\Http\Controllers\Projects\Tasks;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Services\AssignUserToTask;
use App\Services\RemoveUserFromTask;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectAssignTaskController extends Controller
{
    public function store(Request $request, Project $project, Task $task): JsonResponse
    {
        $assignee = (new AssignUserToTask)->execute([
            'user_id' => auth()->user()->id,
            'assignee_id' => $request->input('assignee_id'),
            'task_id' => $task->id,
        ]);

        return response()->json([
            'data' => [
                'assignee' => [
                    'id' => $assignee->id,
                    'name' => $assignee->name,
                    'avatar' => $assignee->avatar,
                ],
            ],
        ], 201);
    }

    public function destroy(Request $request, Project $project, Task $task): JsonResponse
    {
        (new RemoveUserFromTask)->execute([
            'user_id' => auth()->user()->id,
            'assignee_id' => $request->input('assignee_id'),
            'task_id' => $task->id,
        ]);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
