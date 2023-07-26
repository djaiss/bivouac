<?php

namespace App\Http\Controllers\Projects\Tasks;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Projects\Messages\MessageViewModel;
use App\Http\Controllers\Tasks\TaskViewModel;
use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use App\Services\AddCommentToTask;
use App\Services\DestroyCommentOfTask;
use App\Services\UpdateCommentOfTask;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskCommentController extends Controller
{
    public function store(Request $request, Project $project, Task $task): JsonResponse
    {
        $comment = (new AddCommentToTask)->execute([
            'user_id' => auth()->user()->id,
            'task_id' => $task->id,
            'body' => $request->input('body'),
        ]);

        return response()->json([
            'data' => TaskViewModel::dtoComment($task, $comment),
        ], 201);
    }

    public function update(Request $request, Project $project, Task $task, Comment $comment): JsonResponse
    {
        $comment = (new UpdateCommentOfTask)->execute([
            'user_id' => auth()->user()->id,
            'task_id' => $task->id,
            'comment_id' => $comment->id,
            'body' => $request->input('body'),
        ]);

        return response()->json([
            'data' => TaskViewModel::dtoComment($task, $comment),
        ], 200);
    }

    public function destroy(Request $request, Project $project, Task $task, Comment $comment): JsonResponse
    {
        (new DestroyCommentOfTask)->execute([
            'user_id' => auth()->user()->id,
            'task_id' => $task->id,
            'comment_id' => $comment->id,
        ]);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
