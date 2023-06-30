<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Models\Office;
use App\Models\Project;
use App\Services\CreateProject;
use App\Services\DestroyOffice;
use App\Services\UpdateOffice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProjectController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Projects/Index', [
            'data' => ProjectViewModel::index(auth()->user()->organization),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Projects/Create', [
            'data' => ProjectViewModel::create(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        (new CreateProject)->execute([
            'user_id' => auth()->user()->id,
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'is_public' => $request->input('is_public') === 'true' ? true : false,
        ]);

        return response()->json([
            'data' => route('projects.index'),
        ], 201);
    }

    public function show(Request $request, Project $project): Response
    {
        return Inertia::render('Projects/Show', [
            'data' => ProjectViewModel::show($project),
        ]);
    }

    public function edit(Request $request, Project $project): Response
    {
        return Inertia::render('Projects/Edit', [
            'data' => ProjectViewModel::edit($office),
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
