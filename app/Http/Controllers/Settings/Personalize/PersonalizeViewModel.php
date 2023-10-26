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
                    'settings' => route('settings.index'),
                ],
                'users' => route('settings.user.index'),
                'offices' => route('settings.office.index'),
                'team_types' => route('settings.team_type.index'),
                'upgrade' => route('settings.upgrade.index'),
                'organization' => route('settings.organization.delete'),
            ],
        ];
    }
}
