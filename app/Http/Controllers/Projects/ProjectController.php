<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\CreateProject;
use App\Services\DestroyProject;
use App\Services\UpdateProject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProjectController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Projects/Index', [
            'data' => ProjectViewModel::index(
                organization: auth()->user()->organization,
                user: auth()->user(),
            ),
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
        $project = (new CreateProject)->execute([
            'user_id' => auth()->user()->id,
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'is_public' => $request->input('is_public') === 'true',
        ]);

        return response()->json([
            'data' => route('projects.show', $project),
        ], 201);
    }

    public function show(Request $request, Project $project): Response
    {
        return Inertia::render('Projects/Summary/Index', [
            'data' => ProjectViewModel::show($project),
            'menu' => ProjectViewModel::menu($project),
        ]);
    }

    public function edit(Request $request, Project $project): Response
    {
        return Inertia::render('Projects/Edit', [
            'data' => ProjectViewModel::edit($project),
            'menu' => ProjectViewModel::menu($project),
        ]);
    }

    public function update(Request $request, Project $project): JsonResponse
    {
        $project = (new UpdateProject)->execute([
            'user_id' => auth()->user()->id,
            'project_id' => $project->id,
            'name' => $request->input('name'),
            'short_description' => $request->input('short_description'),
            'description' => $request->input('description'),
            'is_public' => $request->input('is_public') === 'true',
        ]);

        return response()->json([
            'data' => true,
        ], 200);
    }

    public function destroy(Request $request, Project $project): JsonResponse
    {
        (new DestroyProject)->execute([
            'user_id' => auth()->user()->id,
            'project_id' => $project->id,
        ]);

        return response()->json([
            'data' => route('projects.index'),
        ], 200);
    }
}
