<?php

namespace App\Http\Controllers\Projects\Members;

use App\Models\Project;
use App\Models\User;

class MemberViewModel
{
    public static function index(Project $project): array
    {
        $members = $project->users()->get()
            ->map(fn (User $user) => self::dto($user));

        return [
            'project' => [
                'id' => $project->id,
                'name' => $project->name,
                'short_description' => $project->short_description,
                'description' => $project->description,
                'is_public' => $project->is_public,
            ],
            'members' => $members,
        ];
    }

    public static function dto(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => $user->avatar,
            'url' => route('users.show', $user),
        ];
    }
}
