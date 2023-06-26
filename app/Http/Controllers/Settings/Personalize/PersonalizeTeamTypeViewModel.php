<?php

namespace App\Http\Controllers\Settings\Personalize;

use App\Models\Organization;
use App\Models\TeamType;

class PersonalizeTeamTypeViewModel
{
    public static function index(Organization $organization): array
    {
        $teamTypes = $organization->teamTypes()
            ->get()
            ->map(fn (TeamType $teamType) => self::dto($teamType));

        return [
            'team_types' => $teamTypes,
            'url' => [
                'create' => route('settings.personalize.team_type.create'),
                'breadcrumb' => [
                    'home' => route('profile.edit'),
                    'settings' => route('settings.personalize.index'),
                ],
            ],
        ];
    }

    public static function create(): array
    {
        return [
            'url' => [
                'store' => route('settings.personalize.team_type.store'),
                'breadcrumb' => [
                    'home' => route('profile.edit'),
                    'settings' => route('settings.personalize.index'),
                    'team_types' => route('settings.personalize.team_type.index'),
                ],
            ],
        ];
    }

    public static function edit(TeamType $teamType): array
    {
        return [
            'id' => $teamType->id,
            'label' => $teamType->label,
            'position' => $teamType->position,
            'url' => [
                'update' => route('settings.personalize.team_type.update', [
                    'teamType' => $teamType->id,
                ]),
                'breadcrumb' => [
                    'home' => route('profile.edit'),
                    'settings' => route('settings.personalize.index'),
                    'team_types' => route('settings.personalize.team_type.index'),
                ],
            ],
        ];
    }

    public static function dto(TeamType $teamType): array
    {
        return [
            'id' => $teamType->id,
            'label' => $teamType->label,
            'position' => $teamType->position,
            'url' => [
                'edit' => route('settings.personalize.team_type.edit', [
                    'teamType' => $teamType->id,
                ]),
                'destroy' => route('settings.personalize.team_type.destroy', [
                    'teamType' => $teamType->id,
                ]),
            ],
        ];
    }
}
