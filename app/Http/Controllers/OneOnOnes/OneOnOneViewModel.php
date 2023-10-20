<?php

namespace App\Http\Controllers\OneOnOnes;

use App\Models\OneOnOne;
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
}
