<?php

namespace App\Http\Controllers\Projects\Summary;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Projects\ProjectViewModel;
use App\Models\KeyPeople;
use App\Models\Project;
use App\Services\CreateKeyPeople;
use App\Services\DestroyKeyPeople;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectKeyPeopleController extends Controller
{
    public function store(Request $request, Project $project): JsonResponse
    {
        $keyPeople = (new CreateKeyPeople)->execute([
            'user_id' => $request->input('user_id'),
            'project_id' => $project->id,
            'role' => $request->input('role'),
        ]);

        return response()->json([
            'data' => ProjectViewModel::dtoKeyPeople($keyPeople),
        ], 201);
    }

    public function destroy(Request $request, Project $project, KeyPeople $people): JsonResponse
    {
        (new DestroyKeyPeople)->execute([
            'user_id' => auth()->user()->id,
            'key_people_id' => $people->id,
        ]);

        return response()->json([
            'data' => true,
        ], 201);
    }
}
