<?php

namespace App\Http\Controllers\Settings\Personalize;

use App\Models\Organization;

class OrganizationViewModel
{
    public static function delete(Organization $organization): array
    {
        return [
            'url' => [
                'destroy' => route('settings.organization.destroy'),
                'breadcrumb' => [
                    'home' => route('home.index'),
                    'settings' => route('settings.index'),
                ],
            ],
        ];
    }
}
