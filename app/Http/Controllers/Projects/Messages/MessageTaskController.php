<?php

namespace App\Http\Controllers\Projects\Messages;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Tasks\TaskViewModel;
use App\Models\Comment;
use App\Models\Message;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskList;
use App\Services\AddCommentToMessage;
use App\Services\CreateTask;
use App\Services\CreateTaskList;
use App\Services\DestroyCommentOfMessage;
use App\Services\UpdateCommentOfMessage;
use App\Services\UpdateTask;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageTaskController extends Controller
{
    public function store(Request $request, Project $project, Message $message): JsonResponse
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
}
