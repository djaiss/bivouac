<?php

namespace App\Http\Controllers\Projects\Members;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Projects\ProjectViewModel;
use App\Models\Project;
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
}
