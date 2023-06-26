<?php

namespace App\Http\Controllers\Settings\Personalize;

use App\Http\Controllers\Controller;
use App\Models\TeamType;
use App\Services\CreateTeamType;
use App\Services\DestroyTeamType;
use App\Services\UpdateTeamType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PersonalizeTeamTypeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Settings/Personalize/TeamTypes/Index', [
            'data' => PersonalizeTeamTypeViewModel::index(auth()->user()->organization),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Settings/Personalize/TeamTypes/Create', [
            'data' => PersonalizeTeamTypeViewModel::create(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        (new CreateTeamType)->execute([
            'user_id' => auth()->user()->id,
            'label' => $request->input('label'),
        ]);

        return response()->json([
            'data' => route('settings.personalize.team_type.index'),
        ], 201);
    }

    public function edit(Request $request, TeamType $teamType): Response
    {
        return Inertia::render('Settings/Personalize/TeamTypes/Edit', [
            'data' => PersonalizeTeamTypeViewModel::edit($teamType),
        ]);
    }

    public function update(Request $request, TeamType $teamType): JsonResponse
    {
        $teamType = (new UpdateTeamType)->execute([
            'user_id' => auth()->user()->id,
            'team_type_id' => $teamType->id,
            'label' => $request->input('label'),
        ]);

        return response()->json([
            'data' => route('settings.personalize.team_type.index'),
        ], 200);
    }

    public function destroy(Request $request, TeamType $teamType): JsonResponse
    {
        (new DestroyTeamType)->execute([
            'user_id' => auth()->user()->id,
            'team_type_id' => $teamType->id,
        ]);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
