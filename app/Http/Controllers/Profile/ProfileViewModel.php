<?php

namespace App\Http\Controllers\Profile;

use App\Models\User;
use Carbon\Carbon;

class ProfileViewModel
{
    public static function data(User $user): array
    {
        return [
            'user' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'avatar' => $user->avatar,
                'born_at' => $user->born_at ?? Carbon::now()->format('Y:m:d'),
                'age_preferences' => $user->age_preferences ?? User::AGE_HIDDEN,
            ],
            'url' => [
                'update' => route('profile.update'),
                'avatar_update' => route('profile.avatar.update'),
                'birthdate_update' => route('profile.birthdate.update'),
            ],
        ];
    }
}
