<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Services\UpdateUserDateOfBirth;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileBirthdateController extends Controller
{
    public function update(Request $request): JsonResponse
    {
        $date = Carbon::parse($request->input('born_at'))->format('Y-m-d');

        (new UpdateUserDateOfBirth)->execute([
            'author_id' => auth()->user()->id,
            'user_id' => auth()->user()->id,
            'born_at' => $date,
            'age_preferences' => $request->input('age_preferences'),
        ]);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
