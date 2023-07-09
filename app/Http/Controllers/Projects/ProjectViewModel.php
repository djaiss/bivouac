<?php

namespace App\Http\Controllers\Projects;

use App\Models\Organization;
use App\Models\Project;
use App\Models\User;

class ProjectViewModel
{
    public static function index(Organization $organization, User $user): array
    {
        $projects = $organization->projects()
            ->with('creator')
            ->orderBy('name')
            ->get()
            ->filter(function (Project $project) use ($user) {
                $userBelongsToProject = $project->users()->where('user_id', $user->id)->exists();

                return $project->is_public || (! $project->is_public && $userBelongsToProject);
            })
            ->map(fn (Project $project) => self::dto($project))
            ->values()->all();

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
                    'avatar' => $project?->creator?->avatar,
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
            ],
        ];
    }

    public static function dto(Project $project): array
    {
        return [
            'id' => $project->id,
            'author' => [
                'name' => $project->author,
                'avatar' => $project?->creator?->avatar,
            ],
            'name' => $project->name,
            'description' => $project->description,
            'is_public' => $project->is_public,
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

    public static function menu(Project $project): array
    {
        return [
            'url' => [
                'messages' => route('messages.index', [
                    'project' => $project->id,
                ]),
                'settings' => route('projects.edit', [
                    'project' => $project->id,
                ]),
            ],
        ];
    }
}
