<?php

namespace App\Http\Controllers\Reactions;

use App\Models\Reaction;

class ReactionViewModel
{
    public static function dto(Reaction $reaction): array
    {
        return [
            'author' => [
                'name' => $reaction->authorName,
                'avatar' => $reaction?->creator?->avatar,
                'url' => $reaction->creator ? route('users.show', $reaction->creator) : null,
            ],
        ];
    }
}
