<?php

namespace App\Http\Controllers\Projects\Messages;

use App\Helpers\StringHelper;
use App\Http\Controllers\Reactions\ReactionViewModel;
use App\Http\Controllers\Tasks\TaskListViewModel;
use App\Models\Comment;
use App\Models\Message;
use App\Models\Project;
use App\Models\Reaction;
use App\Models\Task;
use App\Models\TaskList;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class MessageViewModel
{
    public static function index(Project $project, User $user): array
    {
        $messages = $project->messages()
            ->with('creator')
            ->withCount('comments')
            ->orderByDesc('created_at')
            ->get();

        $readStatuses = DB::table('message_read_status')
            ->whereIn('message_id', $messages->pluck('id'))
            ->get();

        $messages = $messages->map(fn (Message $message) => self::dto($message, $readStatuses->contains(fn ($readStatus) => $readStatus->message_id === $message->id &&
            $readStatus->user_id === $user->id
        )));

        return [
            'project' => [
                'id' => $project->id,
                'name' => $project->name,
                'is_public' => $project->is_public,
            ],
            'messages' => $messages,
            'url' => [
                'create' => route('messages.create', [
                    'project' => $project->id,
                ]),
                'breadcrumb' => [
                    'home' => route('profile.edit'),
                ],
            ],
        ];
    }

    public static function create(Project $project): array
    {
        return [
            'project' => [
                'name' => $project->name,
            ],
            'url' => [
                'preview' => route('preview.store'),
                'store' => route('messages.store', [
                    'project' => $project->id,
                ]),
                'breadcrumb' => [
                    'projects' => route('projects.index'),
                    'project' => route('projects.show', [
                        'project' => $project->id,
                    ]),
                    'messages' => route('messages.index', [
                        'project' => $project->id,
                    ]),
                ],
            ],
        ];
    }

    public static function show(Message $message): array
    {
        $comments = $message->comments()
            ->with('creator')
            ->orderBy('created_at')
            ->get()
            ->map(fn (Comment $comment) => self::dtoComment($message, $comment));

        $reactions = $message->reactions()
            ->with('user')
            ->get()
            ->map(fn (Reaction $reaction) => ReactionViewModel::dto($reaction));

        $taskList = $message->taskLists()
            ->with('tasks')
            ->first();
        $taskList = TaskListViewModel::dto($taskList);

        return [
            'project' => [
                'name' => $message->project->name,
            ],
            'message' => self::dto($message),
            'comments' => $comments,
            'reactions' => $reactions,
            'task_list' => $taskList,
            'url' => [
                'preview' => route('preview.store'),
                'store' => route('messages.comments.store', [
                    'project' => $message->project_id,
                    'message' => $message->id,
                ]),
                'store_reaction' => route('messages.reactions.store', [
                    'project' => $message->project_id,
                    'message' => $message->id,
                ]),
                'store_task' => route('messages.tasks.store', [
                    'project' => $message->project_id,
                    'message' => $message->id,
                ]),
                'breadcrumb' => [
                    'projects' => route('projects.index'),
                    'project' => route('projects.show', [
                        'project' => $message->project_id,
                    ]),
                    'messages' => route('messages.index', [
                        'project' => $message->project_id,
                    ]),
                ],
            ],
        ];
    }

    public static function edit(Message $message): array
    {
        return [
            'project' => [
                'name' => $message->project->name,
            ],
            'message' => self::dto($message),
            'url' => [
                'preview' => route('preview.store'),
                'update' => route('messages.update', [
                    'project' => $message->project_id,
                    'message' => $message->id,
                ]),
                'breadcrumb' => [
                    'projects' => route('projects.index'),
                    'project' => route('projects.show', [
                        'project' => $message->project_id,
                    ]),
                    'messages' => route('messages.index', [
                        'project' => $message->project_id,
                    ]),
                ],
            ],
        ];
    }

    public static function dto(Message $message, bool $isRead = false): array
    {
        return [
            'id' => $message->id,
            'author' => [
                'name' => $message->authorName,
                'avatar' => $message?->creator?->avatar,
                'url' => $message->creator ? route('users.show', $message->creator) : null,
            ],
            'title' => $message->title,
            'body' => StringHelper::parse($message->body),
            'body_raw' => $message->body,
            'created_at' => $message->created_at->format('Y-m-d'),
            'read' => $isRead,
            'comments_count' => $message->comments_count,
            'url' => [
                'show' => route('messages.show', [
                    'project' => $message->project_id,
                    'message' => $message->id,
                ]),
                'edit' => route('messages.edit', [
                    'project' => $message->project_id,
                    'message' => $message->id,
                ]),
                'destroy' => route('messages.destroy', [
                    'project' => $message->project_id,
                    'message' => $message->id,
                ]),
            ],
        ];
    }

    public static function dtoComment(Message $message, Comment $comment): array
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
                'store_reaction' => route('messages.comments.reactions.store', [
                    'project' => $message->project_id,
                    'message' => $message->id,
                    'comment' => $comment->id,
                ]),
                'update' => route('messages.comments.update', [
                    'project' => $message->project_id,
                    'message' => $message->id,
                    'comment' => $comment->id,
                ]),
                'destroy' => route('messages.comments.destroy', [
                    'project' => $message->project_id,
                    'message' => $message->id,
                    'comment' => $comment->id,
                ]),
            ],
        ];
    }
}
