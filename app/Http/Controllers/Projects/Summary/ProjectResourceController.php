<?php

namespace App\Http\Controllers\Projects\Summary;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Projects\ProjectViewModel;
use App\Models\Project;
use App\Models\ProjectResource;
use App\Services\CreateProjectResource;
use App\Services\DestroyProjectResource;
use App\Services\UpdateProjectResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectResourceController extends Controller
{
    public function store(Request $request, Project $project): JsonResponse
    {
        $projectResource = (new CreateProjectResource)->execute([
            'user_id' => auth()->user()->id,
            'project_id' => $project->id,
            'name' => $request->input('name'),
            'link' => $request->input('link'),
        ]);

        return response()->json([
            'data' => ProjectViewModel::dtoProjectResource($projectResource),
        ], 201);
    }

    public function update(Request $request, Project $project, ProjectResource $projectResource): JsonResponse
    {
        $projectResource = (new UpdateProjectResource)->execute([
            'user_id' => auth()->user()->id,
            'project_resource_id' => $projectResource->id,
            'name' => $request->input('name'),
            'link' => $request->input('link'),
        ]);

        return response()->json([
            'data' => ProjectViewModel::dtoProjectResource($projectResource),
        ], 200);
    }

    public function destroy(Request $request, Project $project, ProjectResource $projectResource): JsonResponse
    {
        $projectResource = (new DestroyProjectResource)->execute([
            'user_id' => auth()->user()->id,
            'project_resource_id' => $projectResource->id,
        ]);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
