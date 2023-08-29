<?php

namespace App\Http\Controllers\Projects;

use App\Helpers\StringHelper;
use App\Models\Organization;
use App\Models\Project;
use App\Models\ProjectResource;
use App\Models\ProjectUpdate;
use App\Models\User;

class ProjectViewModel
{
    public static function index(Organization $organization, User $user): array
    {
        // we make sure users can't see projects they don't belong to
        $projects = $organization->projects()
            ->with('creator')
            ->withCount('users')
            ->orderBy('name')
            ->get()
            ->filter(function (Project $project) use ($user) {
                $userBelongsToProject = $project->users()->where('user_id', $user->id)->exists();

                return $project->is_public || (! $project->is_public && $userBelongsToProject);
            })
            ->map(fn (Project $project) => self::dto($project))
            ->values()->all();

        return [
            'needs_upgrade' => $organization->licence_key === null && count($projects) >= 1 && config('app.store.activated'),
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
        $resources = $project->projectResources()
            ->get()
            ->map(fn (ProjectResource $projectResource) => self::dtoProjectResource($projectResource));

        $updates = $project->projectUpdates()
            ->with('creator')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (ProjectUpdate $projectUpdate) => self::dtoProjectUpdate($projectUpdate));

        return [
            'project' => self::dto($project),
            'resources' => $resources,
            'updates' => $updates,
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
                'short_description' => $project->short_description,
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
        // get random members of the project
        if ($project->users->count() > 4) {
            $random = 4;
        } else {
            $random = $project->users->count();
        }
        $randomMembers = $project->users->random($random)
            ->map(fn (User $user) => [
                'id' => $user->id,
                'name' => $user->name,
                'avatar' => $user->avatar,
            ]);

        return [
            'id' => $project->id,
            'author' => [
                'name' => $project->author,
                'avatar' => $project?->creator?->avatar,
            ],
            'name' => $project->name,
            'short_description' => $project->short_description,
            'description' => $project->description ? StringHelper::parse($project->description) : null,
            'is_public' => $project->is_public,
            'updated_at' => $project->updated_at->ago(),
            'members' => $randomMembers,
            'other_members_counter' => $project->users_count - 4,
            'url' => [
                'show' => route('projects.show', [
                    'project' => $project->id,
                ]),
                'preview' => route('preview.store'),
                'store_resource' => route('projects.resources.store', [
                    'project' => $project->id,
                ]),
                'store_update' => route('project_updates.store', [
                    'project' => $project->id,
                ]),
                'edit' => route('projects.edit', [
                    'project' => $project->id,
                ]),
            ],
        ];
    }

    public static function dtoProjectResource(ProjectResource $projectResource): array
    {
        return [
            'id' => $projectResource->id,
            'name' => $projectResource->name,
            'link' => $projectResource->link,
            'url' => [
                'update' => route('projects.resources.update', [
                    'project' => $projectResource->project_id,
                    'projectResource' => $projectResource->id,
                ]),
                'destroy' => route('projects.resources.destroy', [
                    'project' => $projectResource->project_id,
                    'projectResource' => $projectResource->id,
                ]),
            ],
        ];
    }

    public static function dtoProjectUpdate(ProjectUpdate $projectUpdate): array
    {
        return [
            'id' => $projectUpdate->id,
            'author' => [
                'name' => $projectUpdate->author,
                'avatar' => $projectUpdate?->creator?->avatar,
                'url' => $projectUpdate->creator ? route('users.show', $projectUpdate->creator) : null,
            ],
            'content' => StringHelper::parse($projectUpdate->content),
            'content_raw' => $projectUpdate->content,
            'created_at' => $projectUpdate->created_at->format('Y-m-d'),
            'url' => [
                'update' => route('project_updates.update', [
                    'project' => $projectUpdate->project_id,
                    'update' => $projectUpdate->id,
                ]),
                'destroy' => route('project_updates.destroy', [
                    'project' => $projectUpdate->project_id,
                    'update' => $projectUpdate->id,
                ]),
            ],
        ];
    }

    public static function menu(Project $project): array
    {
        return [
            'url' => [
                'summary' => route('projects.show', [
                    'project' => $project->id,
                ]),
                'messages' => route('messages.index', [
                    'project' => $project->id,
                ]),
                'tasks' => route('tasks.index', [
                    'project' => $project->id,
                ]),
                'members' => route('members.index', [
                    'project' => $project->id,
                ]),
                'settings' => route('projects.edit', [
                    'project' => $project->id,
                ]),
            ],
        ];
    }
}
