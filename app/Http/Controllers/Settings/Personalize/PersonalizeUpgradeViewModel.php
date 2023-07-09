<?php

namespace App\Http\Controllers\Settings\Personalize;

use App\Models\Organization;

class PersonalizeUpgradeViewModel
{
    public static function data(Organization $organization): array
    {
        return [
            'upgraded' => $organization->licence_key !== null,
            'url' => [
                'store' => config('app.store.url'),
                'upgrade' => route('settings.personalize.upgrade.update'),
                'breadcrumb' => [
                    'home' => route('profile.edit'),
                    'settings' => route('settings.personalize.index'),
                ],
            ],
        ];
    }
}
