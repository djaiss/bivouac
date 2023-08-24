<?php

namespace App\Http\Controllers\Projects\Members;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Projects\ProjectViewModel;
use App\Models\Project;
use App\Models\User;
use App\Services\AddProjectMember;
use App\Services\RemoveMemberFromProject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MemberUserController extends Controller
{
    public function index(Project $project): JsonResponse
    {
        return response()->json([
            'data' => MemberViewModel::listUsers(auth()->user(), $project),
        ], 200);
    }

    public function store(Request $request, Project $project, User $member): JsonResponse
    {
        (new AddProjectMember)->execute([
            'user_id' => $member->id,
            'project_id' => $project->id,
        ]);

        return response()->json([
            'data' => MemberViewModel::dto($member, $project),
        ], 201);
    }
}
