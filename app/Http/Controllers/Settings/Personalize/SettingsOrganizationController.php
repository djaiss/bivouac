<?php

namespace App\Http\Controllers\Settings\Personalize;

use App\Http\Controllers\Controller;
use App\Services\DestroyOrganization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SettingsOrganizationController extends Controller
{
    public function delete(): Response
    {
        return Inertia::render('Settings/Organization/Index', [
            'data' => OrganizationViewModel::delete(auth()->user()->organization),
        ]);
    }

    public function destroy(Request $request): JsonResponse
    {
        (new DestroyOrganization)->execute();

        return response()->json([
            'data' => route('home.index'),
        ], 200);
    }
}
