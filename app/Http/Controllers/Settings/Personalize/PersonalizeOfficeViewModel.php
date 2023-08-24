<?php

namespace App\Http\Controllers\Settings\Personalize;

use App\Models\Office;
use App\Models\Organization;

class PersonalizeOfficeViewModel
{
    public static function index(Organization $organization): array
    {
        $offices = $organization->offices()
            ->get()
            ->map(fn (Office $office) => self::dto($office));

        return [
            'offices' => $offices,
            'url' => [
                'create' => route('settings.office.create'),
                'breadcrumb' => [
                    'home' => route('profile.edit'),
                    'settings' => route('settings.index'),
                ],
            ],
        ];
    }

    public static function create(): array
    {
        return [
            'url' => [
                'store' => route('settings.office.store'),
                'breadcrumb' => [
                    'home' => route('profile.edit'),
                    'settings' => route('settings.index'),
                    'offices' => route('settings.office.index'),
                ],
            ],
        ];
    }

    public static function edit(Office $office): array
    {
        return [
            'id' => $office->id,
            'name' => $office->name,
            'is_main_office' => $office->is_main_office,
            'url' => [
                'update' => route('settings.office.update', [
                    'office' => $office->id,
                ]),
                'breadcrumb' => [
                    'home' => route('profile.edit'),
                    'settings' => route('settings.index'),
                    'offices' => route('settings.office.index'),
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
                'edit' => route('settings.office.edit', [
                    'office' => $office->id,
                ]),
                'destroy' => route('settings.office.destroy', [
                    'office' => $office->id,
                ]),
            ],
        ];
    }
}
