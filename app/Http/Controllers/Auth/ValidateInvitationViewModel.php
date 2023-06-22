<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;

class ValidateInvitationViewModel
{
    public static function data(User $user): array
    {
        return [
            'url' => [
                'store' => route('invitation.validate.update', [
                    'code' => $user->invitation_code,
                ]),
            ],
        ];
    }
}
