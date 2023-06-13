<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Services\RegenerateAvatar;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileAvatarController extends Controller
{
    public function update(Request $request): JsonResponse
    {
        (new RegenerateAvatar)->execute([
            'user_id' => auth()->user()->id,
        ]);

        return response()->json([
            'data' => route('profile.edit'),
        ], 200);
    }
}
