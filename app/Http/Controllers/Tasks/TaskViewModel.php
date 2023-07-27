<?php

namespace App\Http\Controllers\Tasks;

use App\Helpers\StringHelper;
use App\Http\Controllers\Reactions\ReactionViewModel;
use App\Models\Comment;
use App\Models\Reaction;
use App\Models\Task;
use App\Models\User;

class TaskViewModel
{
    public static function show(Task $task): array
    {
        $taskList = $task->taskList;

        return [
            'task' => self::dto($task),
            'project' => [
                'name' => $taskList->project->name,
            ],
            'breadcrumb' => [
                'projects' => route('projects.index'),
                'project' => route('projects.show', [
                    'project' => $taskList->project_id,
                ]),
                'tasks' => route('tasks.index', [
                    'project' => $taskList->project_id,
                ]),
            ],
        ];
    }

    public static function dto(Task $task): array
    {
        $assignees = $task->users()
            ->get()
            ->map(fn (User $user) => [
                'id' => $user->id,
                'name' => $user->name,
                'avatar' => $user->avatar,
                'url' => route('users.show', $user),
            ]);

        $reactions = $task->reactions()
            ->with('user')
            ->get()
            ->map(fn (Reaction $reaction) => ReactionViewModel::dto($reaction));

        $comments = $task->comments()
            ->with('creator')
            ->orderBy('created_at')
            ->get()
            ->map(fn (Comment $comment) => self::dtoComment($task, $comment));

        return [
            'id' => $task->id,
            'title' => $task->title,
            'description' => $task->description ? StringHelper::parse($task->description) : null,
            'description_raw' => $task->description,
            'is_completed' => $task->is_completed,
            'assignees' => $assignees,
            'reactions' => $reactions,
            'comments' => $comments,
            'url' => [
                'preview' => route('preview.store'),
                'search_users' => route('tasks.search.user.index', [
                    'project' => $task->taskList->project_id,
                    'task' => $task->id,
                ]),
                'show' => route('tasks.show', [
                    'project' => $task->taskList->project_id,
                    'task' => $task->id,
                ]),
                'store' => route('tasks.comments.store', [
                    'project' => $task->taskList->project_id,
                    'task' => $task->id,
                ]),
                'store_reaction' => route('tasks.reactions.store', [
                    'project' => $task->taskList->project_id,
                    'task' => $task->id,
                ]),
                'update' => route('tasks.update', $task),
                'destroy' => route('tasks.destroy', $task),
                'assign' => route('tasks.assign.store', [
                    'project' => $task->taskList->project_id,
                    'task' => $task->id,
                ]),
                'unassign' => route('tasks.assign.destroy', [
                    'project' => $task->taskList->project_id,
                    'task' => $task->id,
                ]),
            ],
        ];
    }

    public static function dtoComment(Task $task, Comment $comment): array
    {
        $reactions = $comment->reactions()
            ->get()
            ->map(fn (Reaction $reaction) => ReactionViewModel::dto($reaction));

        return [
            'id' => $comment->id,
            'author' => [
                'name' => $comment->authorName,
                'avatar' => $comment?->creator?->avatar,
                'url' => $comment->creator ? route('users.show', $comment->creator) : null,
            ],
            'body' => StringHelper::parse($comment->body),
            'body_raw' => $comment->body,
            'created_at' => $comment->created_at->format('Y-m-d H:i:s'),
            'reactions' => $reactions,
            'url' => [
                'store_reaction' => route('comments.reactions.store', [
                    'comment' => $comment->id,
                ]),
                'update' => route('tasks.comments.update', [
                    'project' => $task->taskList->project_id,
                    'task' => $task->id,
                    'comment' => $comment->id,
                ]),
                'destroy' => route('tasks.comments.destroy', [
                    'project' => $task->taskList->project_id,
                    'task' => $task->id,
                    'comment' => $comment->id,
                ]),
            ],
        ];
    }
}
