<?php

namespace App\Http\Controllers\OneOnOnes;

use App\Models\OneOnOne;
use App\Models\OneOnOneEntry;
use App\Models\User;

class OneOnOneViewModel
{
    public static function index(User $user): array
    {
        $oneOnOnes = OneOnOne::where('user_id', $user->id)
            ->orWhere('other_user_id', $user->id)
            ->get()
            ->map(fn (OneOnOne $oneOnOne) => self::dtoOneOnOne($oneOnOne));

        return [
            'one_on_ones' => $oneOnOnes,
            'url' => [
                'create' => route('oneonones.create'),
            ],
        ];
    }

    public static function dtoOneOnOne(OneOnOne $oneOnOne): array
    {
        return [
            'id' => $oneOnOne->id,
            'user' => [
                'id' => $oneOnOne->user->id,
                'name' => $oneOnOne->user->name,
                'avatar' => $oneOnOne->user->avatar,
            ],
            'other_user' => [
                'id' => $oneOnOne->otherUser->id,
                'name' => $oneOnOne->otherUser->name,
                'avatar' => $oneOnOne->otherUser->avatar,
            ],
            'url' => [
                'show' => route('oneonones.show', $oneOnOne),
            ],
        ];
    }

    public static function create(User $user): array
    {
        $oneOnOnes = OneOnOne::where('user_id', $user->id)
            ->orWhere('other_user_id', $user->id)
            ->get();

        $ids = $oneOnOnes->pluck('user_id')
            ->merge($oneOnOnes->pluck('other_user_id'))
            ->unique()
            ->toArray();

        $users = User::where('id', '!=', $user->id)
            ->where('organization_id', $user->organization_id)
            ->whereNotIn('id', $ids)
            ->get()
            ->map(fn (User $user) => [
                'id' => $user->id,
                'name' => $user->name,
                'avatar' => $user->avatar,
            ]);

        return [
            'users' => $users,
            'url' => [
                'store' => route('oneonones.store'),
                'breadcrumb' => [
                    'oneonones' => route('oneonones.index'),
                ],
            ],
        ];
    }

    public static function show(OneOnOne $oneOnOne): array
    {
        $activePointsOfDiscussion = $oneOnOne->oneOnOneEntries()
            ->whereNull('checked_at')
            ->get()
            ->map(fn (OneOnOneEntry $entry) => self::dtoEntry($entry));

        $inactivePointsOfDiscussion = $oneOnOne->oneOnOneEntries()
            ->whereNotNull('checked_at')
            ->get()
            ->map(fn (OneOnOneEntry $entry) => self::dtoEntry($entry));

        return [
            'one_on_one' => self::dtoOneOnOne($oneOnOne),
            'active_points_of_discussion' => $activePointsOfDiscussion,
            'inactive_points_of_discussion' => $inactivePointsOfDiscussion,
            'url' => [
                'index' => route('oneonones.index'),
                'store' => route('oneonones.entries.store', [
                    'oneOnOne' => $oneOnOne->id,
                ]),
                'breadcrumb' => [
                    'oneonones' => route('oneonones.index'),
                ],
            ],
        ];
    }

    public static function dtoEntry(OneOnOneEntry $entry): array
    {
        return [
            'id' => $entry->id,
            'body' => $entry->body,
            'written_at' => $entry->created_at->format('Y-m-d'),
            'checked' => (bool) $entry->checked_at,
            'user' => [
                'id' => $entry->user->id,
                'name' => $entry->user->name,
                'avatar' => $entry->user->avatar,
            ],
            'url' => [
                'update' => route('oneonones.entries.update', [
                    'oneOnOne' => $entry->one_on_one_id,
                    'entry' => $entry->id,
                ]),
                'toggle' => route('oneonones.entries.toggle', [
                    'oneOnOne' => $entry->one_on_one_id,
                    'entry' => $entry->id,
                ]),
                'destroy' => route('oneonones.entries.destroy', [
                    'oneOnOne' => $entry->one_on_one_id,
                    'entry' => $entry->id,
                ]),
            ],
        ];
    }
}
