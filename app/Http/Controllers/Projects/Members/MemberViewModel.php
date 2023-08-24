<?php

namespace App\Http\Controllers\Projects\Members;

use App\Models\Project;
use App\Models\User;

class MemberViewModel
{
    public static function index(Project $project): array
    {
        $members = $project->users()->get()
            ->map(fn (User $user) => self::dto($user, $project));

        return [
            'project' => [
                'id' => $project->id,
                'name' => $project->name,
                'short_description' => $project->short_description,
                'description' => $project->description,
                'is_public' => $project->is_public,
            ],
            'members' => $members,
            'url' => [
                'search' => route('members.user.index', $project),
            ],
        ];
    }

    public static function dto(User $user, Project $project): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => $user->avatar,
            'url' => [
                'show' => route('users.show', $user),
                'store' => route('members.user.store', [
                    'project' => $project,
                    'member' => $user,
                ]),
                'remove' => route('members.destroy', [
                    'project' => $project->id,
                    'member' => $user->id,
                ]),
            ],
        ];
    }

    /**
     * List all the users who are not part of the project yet.
     */
    public static function listUsers(User $user, Project $project): array
    {
        $users = User::where('id', '!=', $user->id)
            ->whereNotIn('id', $project->users()->pluck('id'))
            ->where('organization_id', $user->organization_id)
            ->get()
            ->map(fn (User $user) => self::dto($user, $project));

        return [
            'users' => $users,
        ];
    }
}
