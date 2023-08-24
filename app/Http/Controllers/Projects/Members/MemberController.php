<?php

namespace App\Http\Controllers\Projects\Members;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Projects\ProjectViewModel;
use App\Models\Project;
use App\Models\User;
use App\Services\RemoveMemberFromProject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MemberController extends Controller
{
    public function index(Project $project): Response
    {
        return Inertia::render('Projects/Members/Index', [
            'data' => MemberViewModel::index($project),
            'menu' => ProjectViewModel::menu($project),
        ]);
    }

    public function destroy(Request $request, Project $project, User $member): JsonResponse
    {
        (new RemoveMemberFromProject)->execute([
            'user_id' => auth()->user()->id,
            'member_id' => $member->id,
            'project_id' => $project->id,
        ]);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
