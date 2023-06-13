<?php

namespace App\Http\Controllers\Profile;

use App\Models\Contact;
use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Collection;

class ProfileViewModel
{
    public static function data(User $user): array
    {
        return [
            'user' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
            ],
            'url' => [
                'update' => route('profile.update'),
            ],
        ];
    }
}
