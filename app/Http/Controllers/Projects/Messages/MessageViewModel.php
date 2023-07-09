<?php

namespace App\Http\Controllers\Projects\Messages;

use App\Helpers\StringHelper;
use App\Models\Comment;
use App\Models\Message;
use App\Models\Project;

class MessageViewModel
{
    public static function index(Project $project): array
    {
        $messages = $project->messages()
            ->with('creator')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (Message $message) => self::dto($message));

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

        return [
            'project' => [
                'name' => $message->project->name,
            ],
            'message' => self::dto($message),
            'comments' => $comments,
            'url' => [
                'preview' => route('preview.store'),
                'store' => route('messages.comments.store', [
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

    public static function dto(Message $message): array
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
        return [
            'id' => $comment->id,
            'author' => [
                'name' => $comment->authorName,
                'avatar' => $comment?->creator?->avatar,
                'url' => $comment->creator ? route('users.show', $comment->creator) : null,
            ],
            'content' => StringHelper::parse($comment->content),
            'content_raw' => $comment->content,
            'created_at' => $comment->created_at->format('Y-m-d H:i:s'),
            'url' => [
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
