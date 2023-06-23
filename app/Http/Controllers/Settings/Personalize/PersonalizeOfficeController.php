<?php

namespace App\Http\Controllers\Settings\Personalize;

use App\Http\Controllers\Controller;
use App\Models\Office;
use App\Models\User;
use App\Services\CreateOffice;
use App\Services\DestroyOffice;
use App\Services\DestroyUser;
use App\Services\InviteUser;
use App\Services\UpdateOffice;
use App\Services\UpdateUserPermission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PersonalizeOfficeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Settings/Personalize/Offices/Index', [
            'data' => PersonalizeOfficeViewModel::data(auth()->user()->organization),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $office = (new CreateOffice)->execute([
            'author_id' => auth()->user()->id,
            'name' => $request->input('name'),
            'is_main_office' => $request->input('is_main_office'),
        ]);

        return response()->json([
            'data' => PersonalizeOfficeViewModel::dto($office),
        ], 201);
    }

    public function update(Request $request, Office $office): JsonResponse
    {
        $office = (new UpdateOffice)->execute([
            'user_id' => auth()->user()->id,
            'office_id' => $office->id,
            'name' => $request->input('name'),
            'is_main_office' => $request->input('is_main_office'),
        ]);

        return response()->json([
            'data' => PersonalizeOfficeViewModel::dto($office),
        ], 200);
    }

    public function destroy(Request $request, Office $office): JsonResponse
    {
        (new DestroyOffice)->execute([
            'user_id' => auth()->user()->id,
            'office_id' => $office->id,
        ]);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
