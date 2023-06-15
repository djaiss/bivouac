<?php

namespace App\Http\Controllers\Settings\Personalize;

class PersonalizeViewModel
{
    public static function data(): array
    {
        return [
            'url' => [
                'users' => route('settings.personalize.user.index'),
            ],
        ];
    }
}
