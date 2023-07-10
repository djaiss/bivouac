<?php

namespace App\Http\Controllers\Reactions;

use App\Models\Reaction;

class ReactionViewModel
{
    public static function dto(Reaction $reaction): array
    {
        return [
            'id' => $reaction->id,
            'emoji' => $reaction->emoji,
            'author' => [
                'id' => $reaction->user->id,
                'name' => $reaction->user->name,
                'avatar' => $reaction->user->avatar,
                'url' => route('users.show', $reaction->user),
            ],
            'url' => [
                'destroy' => route('reactions.destroy', $reaction),
            ],
        ];
    }
}
