<?php

namespace App\Http\Controllers\Projects\Summary;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Projects\ProjectViewModel;
use App\Models\Project;
use App\Models\ProjectUpdate;
use App\Services\CreateProjectUpdate;
use App\Services\DestroyProjectUpdate;
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
            'content' => $request->input('body'),
        ]);

        return response()->json([
            'data' => ProjectViewModel::dtoProjectUpdate($projectUpdate),
        ], 201);
    }

    public function update(Request $request, Project $project, ProjectUpdate $update): JsonResponse
    {
        $projectUpdate = (new UpdateProjectUpdate)->execute([
            'user_id' => auth()->user()->id,
            'project_update_id' => $update->id,
            'content' => $request->input('body'),
        ]);

        return response()->json([
            'data' => ProjectViewModel::dtoProjectUpdate($projectUpdate),
        ], 200);
    }

    public function destroy(Request $request, Project $project, ProjectUpdate $update): JsonResponse
    {
        (new DestroyProjectUpdate)->execute([
            'user_id' => auth()->user()->id,
            'project_update_id' => $update->id,
        ]);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
