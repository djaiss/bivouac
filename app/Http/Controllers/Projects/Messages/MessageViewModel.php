<?php

namespace App\Http\Controllers\Projects\Messages;

use App\Helpers\StringHelper;
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
                'preview' => route('messages.preview.store', [
                    'project' => $project->id,
                ]),
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
        return [
            'project' => [
                'name' => $message->project->name,
            ],
            'message' => self::dto($message),
            'url' => [
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
                'preview' => route('messages.preview.store', [
                    'project' => $message->project_id,
                ]),
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
                'avatar' => $message?->creator->avatar,
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
}
