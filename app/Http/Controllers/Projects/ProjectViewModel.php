<?php

namespace App\Http\Controllers\Projects;

use App\Models\Organization;
use App\Models\Project;
use App\Models\User;

class ProjectViewModel
{
    public static function index(Organization $organization): array
    {
        $projects = $organization->projects()
            ->with('creator')
            ->with('users')
            ->get()
            ->map(fn (Project $project) => self::dto($project));

        return [
            'projects' => $projects,
            'url' => [
                'create' => route('projects.create'),
                'breadcrumb' => [
                    'home' => route('profile.edit'),
                ],
            ],
        ];
    }

    public static function create(): array
    {
        return [
            'url' => [
                'store' => route('projects.store'),
                'breadcrumb' => [
                    'projects' => route('projects.index'),
                ],
            ],
        ];
    }

    public static function show(Project $project): array
    {
        return [
            'project' => self::dto($project),
            'url' => [
                'store' => route('projects.store'),
                'breadcrumb' => [
                    'projects' => route('projects.index'),
                ],
            ],
        ];
    }

    public static function edit(Project $project): array
    {
        return [
            'id' => $project->id,
            'author' => [
                'name' => $project->author,
                'avatar' => $project?->creator->avatar,
            ],
            'name' => $project->name,
            'description' => $project->description,
            'is_public' => $project->is_public,
            'url' => [
                'update' => route('projects.update', [
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

    public static function dto(Project $project): array
    {
        $totalNumberOfUsers = $project->users->count();
        $members = $project->users->random(min($totalNumberOfUsers, 4))
            ->map(fn (User $user) => [
                'id' => $user->id,
                'name' => $user->name,
                'avatar' => $user->avatar,
                'url' => [
                    'show' => route('users.show', [
                        'user' => $user->id,
                    ]),
                ],
            ]);

        return [
            'id' => $project->id,
            'author' => [
                'name' => $project->author,
                'avatar' => $project?->creator->avatar,
            ],
            'name' => $project->name,
            'description' => $project->description,
            'is_public' => $project->is_public,
            'members' => [
                'remaining' => $totalNumberOfUsers - $members->count(),
                'list' => $members,
            ],
            'url' => [
                'show' => route('projects.show', [
                    'project' => $project->id,
                ]),
                'edit' => route('projects.edit', [
                    'project' => $project->id,
                ]),
            ],
        ];
    }
}
