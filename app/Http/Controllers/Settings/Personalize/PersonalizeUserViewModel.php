<?php

namespace App\Http\Controllers\Settings\Personalize;

use App\Models\User;

class PersonalizeUserViewModel
{
    public static function data(User $user): array
    {
        $users = User::where('organization_id', $user->organization_id)
            ->get()
            ->map(fn (User $otherUser) => self::dtoUser($user, $otherUser));

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

    public static function dtoUser(User $loggedUser, User $otherUser): array
    {
        $permission = match ($otherUser->permissions) {
            User::ROLE_ACCOUNT_MANAGER => trans('Account manager'),
            User::ROLE_ADMINISTRATOR => trans('Administrator'),
            User::ROLE_USER => trans('User'),
            default => trans('User'),
        };

        return [
            'id' => $otherUser->id,
            'name' => $otherUser->name,
            'avatar' => $otherUser->avatar,
            'email' => $otherUser->email,
            'verified' => $otherUser->email_verified_at !== null,
            'can_delete' => $loggedUser->id !== $otherUser->id,
            'permissions' => $permission,
            'url' => [
                'edit' => route('settings.personalize.user.edit', [
                    'user' => $otherUser->id,
                ]),
                'destroy' => route('settings.personalize.user.destroy', [
                    'user' => $otherUser->id,
                ]),
            ],
        ];
    }

    public static function edit(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => $user->avatar,
            'permissions' => $user->permission,
            'url' => [
                'breadcrumb' => [
                    'home' => route('profile.edit'),
                    'settings' => route('settings.personalize.index'),
                    'users' => route('settings.personalize.user.index'),
                ],
                'update' => route('settings.personalize.user.edit', [
                    'user' => $user->id,
                ]),
            ],
        ];
    }
}
