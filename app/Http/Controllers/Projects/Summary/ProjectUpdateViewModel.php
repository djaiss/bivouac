<?php

namespace App\Http\Controllers\Projects\Summary;

use App\Helpers\StringHelper;
use App\Models\Project;
use App\Models\ProjectUpdate;

class ProjectUpdateViewModel
{
    public static function index(Project $project): array
    {
        $updates = $project->projectUpdates()
            ->with('creator')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (ProjectUpdate $projectUpdate) => self::dto($projectUpdate));

        return [
            'updates' => $updates,
            'url' => [
                'store' => route('project_updates.store', [
                    'project' => $project->id,
                ]),
            ],
        ];
    }

    public static function dto(ProjectUpdate $projectUpdate): array
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
}
