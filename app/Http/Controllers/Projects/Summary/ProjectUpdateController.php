<?php

namespace App\Http\Controllers\Projects\Summary;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectUpdate;
use App\Services\CreateProjectUpdate;
use App\Services\DestroyProjectResource;
use App\Services\UpdateProjectUpdate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectUpdateController extends Controller
{
    public function store(Request $request, Project $project): JsonResponse
    {
        $projectUpdate = (new CreateProjectUpdate)->execute([
            'user_id' => auth()->user()->id,
            'project_id' => $project->id,
            'content' => $request->input('content'),
        ]);

        return response()->json([
            'data' => ProjectUpdateViewModel::dto($projectUpdate),
        ], 201);
    }

    public function update(Request $request, Project $project, ProjectUpdate $projectUpdate): JsonResponse
    {
        $projectUpdate = (new UpdateProjectUpdate)->execute([
            'user_id' => auth()->user()->id,
            'project_update_id' => $projectUpdate->id,
            'content' => $request->input('content'),
        ]);

        return response()->json([
            'data' => ProjectUpdateViewModel::dto($projectUpdate),
        ], 200);
    }

    public function destroy(Request $request, Project $project, ProjectUpdate $projectUpdate): JsonResponse
    {
        (new DestroyProjectResource)->execute([
            'user_id' => auth()->user()->id,
            'project_update_id' => $projectUpdate->id,
        ]);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
