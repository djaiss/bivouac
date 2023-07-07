<?php

namespace App\Http\Controllers\Projects\Messages;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Projects\ProjectViewModel;
use App\Models\Project;
use App\Services\CreateProject;
use App\Services\DestroyProject;
use App\Services\UpdateProject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MessageController extends Controller
{
    public function index(Project $project): Response
    {
        return Inertia::render('Projects/Messages/Index', [
            'data' => MessageViewModel::index($project),
            'menu' => ProjectViewModel::menu($project),
        ]);
    }

    public function create(Project $project): Response
    {
        return Inertia::render('Projects/Messages/Create', [
            'data' => MessageViewModel::create($project),
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
        return Inertia::render('Projects/Show', [
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
