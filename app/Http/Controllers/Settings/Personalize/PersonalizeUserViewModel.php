<?php

namespace App\Http\Controllers\Settings\Personalize;

use App\Models\User;

class PersonalizeUserViewModel
{
    public static function data(User $user): array
    {
        $users = User::where('organization_id', $user->organization_id)
            ->get()
            ->map(fn (User $user) => self::dtoUser($user));

        return [
            'users' => $users,
            'url' => [
                'invite' => route('settings.personalize.user.create'),
                'invite_store' => route('settings.personalize.user.store'),
                'breadcrumb' => [
                    'home' => route('profile.edit'),
                    'settings' => route('settings.personalize.index'),
                    'users' => route('settings.personalize.user.index'),
                ],
            ],
        ];
    }

    public static function dtoUser(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => $user->avatar,
            'email' => $user->email,
            'verified' => $user->email_verified_at !== null,
        ];
    }
}
