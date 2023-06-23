<?php

namespace App\Http\Controllers\Settings\Personalize;

use App\Models\Office;
use App\Models\Organization;
use App\Models\User;

class PersonalizeOfficeViewModel
{
    public static function data(Organization $organization): array
    {
        $offices = $organization->offices()
            ->get()
            ->map(fn (Office $office) => self::dto($office));

        return [
            'offices' => $offices,
            'url' => [
                'store' => route('settings.personalize.office.store'),
                'breadcrumb' => [
                    'home' => route('profile.edit'),
                    'settings' => route('settings.personalize.index'),
                ],
            ],
        ];
    }

    public static function dto(Office $office): array
    {
        return [
            'id' => $office->id,
            'name' => $office->name,
            'is_main_office' => $office->is_main_office,
            'url' => [
                'edit' => route('settings.personalize.user.edit', [
                    'user' => $office->id,
                ]),
                'destroy' => route('settings.personalize.user.destroy', [
                    'user' => $office->id,
                ]),
            ],
        ];
    }
}
