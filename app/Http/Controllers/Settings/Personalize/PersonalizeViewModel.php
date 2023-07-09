<?php

namespace App\Http\Controllers\Settings\Personalize;

class PersonalizeViewModel
{
    public static function data(): array
    {
        return [
            'upgradable' => config('app.store.activated'),
            'url' => [
                'breadcrumb' => [
                    'home' => route('profile.edit'),
                    'settings' => route('settings.personalize.index'),
                ],
                'users' => route('settings.personalize.user.index'),
                'offices' => route('settings.personalize.office.index'),
                'team_types' => route('settings.personalize.team_type.index'),
                'upgrade' => route('settings.personalize.upgrade.index'),
            ],
        ];
    }
}
