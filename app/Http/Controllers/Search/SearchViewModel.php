<?php

namespace App\Http\Controllers\Search;

use App\Models\Message;
use App\Models\Organization;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SearchViewModel
{
    public static function data(Organization $organization, string $term = null): array
    {
        return [
            'users' => $term ? self::users($organization, $term) : [],
            'projects' => $term ? self::projects($organization, $term) : [],
            'messages' => $term ? self::messages($organization, $term) : [],
            'url' => [
                'search' => route('search.show'),
            ],
        ];
    }

    private static function users(Organization $organization, string $term): Collection
    {
        /** @var Collection<int, User> */
        $users = User::search($term)
            ->where('organization_id', $organization->id)
            ->get();

        return $users->map(fn (User $user) => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar,
            'url' => [
                'show' => route('users.show', $user),
            ],
        ]);
    }

    private static function projects(Organization $organization, string $term): Collection
    {
        /** @var Collection<int, Project> */
        $projects = Project::search($term)
            ->where('organization_id', $organization->id)
            ->get();

        return $projects->map(fn (Project $project) => [
            'id' => $project->id,
            'name' => $project->name,
            'description' => $project->description,
            'url' => [
                'show' => route('projects.show', $project),
            ],
        ]);
    }

    private static function messages(Organization $organization, string $term): Collection
    {
        $projectsIds = DB::table('projects')->select('id')
            ->where('organization_id', $organization->id)
            ->get()
            ->pluck('id')
            ->toArray();

        /** @var Collection<int, Project> */
        $messages = Message::search($term)
            ->whereIn('project_id', $projectsIds)
            ->get();

        return $messages->map(fn (Message $message) => [
            'id' => $message->id,
            'title' => $message->title,
            'body' => $message->body,
            'url' => [
                'show' => route('messages.show', [
                    'project' => $message->project_id,
                    'message' => $message,
                ]),
            ],
        ]);
    }
}
