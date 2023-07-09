<?php

namespace App\Http\Controllers\Settings\Personalize;

use App\Http\Controllers\Controller;
use App\Services\ActivateLicenceKey;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PersonalizeUpgradeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Settings/Personalize/Upgrade/Index', [
            'data' => PersonalizeUpgradeViewModel::data(auth()->user()->organization),
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $organization = (new ActivateLicenceKey)->execute([
            'user_id' => auth()->user()->id,
            'licence_key' => $request->input('licence_key'),
        ]);

        return response()->json([
            'data' => $organization,
        ], 200);
    }
}
