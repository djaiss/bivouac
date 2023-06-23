<?php

namespace App\Http\Controllers\Settings\Personalize;

use App\Http\Controllers\Controller;
use App\Models\Office;
use App\Services\CreateOffice;
use App\Services\DestroyOffice;
use App\Services\UpdateOffice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PersonalizeOfficeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Settings/Personalize/Offices/Index', [
            'data' => PersonalizeOfficeViewModel::index(auth()->user()->organization),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Settings/Personalize/Offices/Create', [
            'data' => PersonalizeOfficeViewModel::create(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        (new CreateOffice)->execute([
            'user_id' => auth()->user()->id,
            'name' => $request->input('name'),
            'is_main_office' => $request->input('is_main_office'),
        ]);

        return response()->json([
            'data' => route('settings.personalize.office.index'),
        ], 201);
    }

    public function edit(Request $request, Office $office): Response
    {
        return Inertia::render('Settings/Personalize/Offices/Edit', [
            'data' => PersonalizeOfficeViewModel::edit($office),
        ]);
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
            'data' => route('settings.personalize.office.index'),
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
