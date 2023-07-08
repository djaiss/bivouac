<?php

namespace App\Http\Controllers\Projects\Messages;

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
            'message' => self::dto($message),
            'url' => [
                'breadcrumb' => [
                    'projects' => route('projects.index'),
                ],
            ],
        ];
    }

    public static function edit(Project $project): array
    {
        return [
            'project' => [
                'id' => $project->id,
                'author' => [
                    'name' => $project->author,
                    'avatar' => $project?->creator->avatar,
                ],
                'name' => $project->name,
                'description' => $project->description,
                'is_public' => $project->is_public,
            ],
            'url' => [
                'update' => route('projects.update', [
                    'project' => $project->id,
                ]),
                'destroy' => route('projects.destroy', [
                    'project' => $project->id,
                ]),
                'breadcrumb' => [
                    'home' => route('profile.edit'),
                    'projects' => route('projects.index'),
                    'project' => route('projects.show', [
                        'project' => $project->id,
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
                'name' => $message->author,
                'avatar' => $message?->creator->avatar,
            ],
            'title' => $message->title,
            'body' => $message->body,
            'url' => [
                'show' => route('messages.show', [
                    'project' => $message->project_id,
                    'message' => $message->id,
                ]),
                'edit' => route('messages.edit', [
                    'project' => $message->project_id,
                    'message' => $message->id,
                ]),
            ],
        ];
    }
}
