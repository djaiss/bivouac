<?php

namespace App\Http\Controllers\Projects\Files;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Projects\ProjectViewModel;
use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProjectFileController extends Controller
{
    public function index(Request $request, Project $project): Response
    {
        return Inertia::render('Projects/Files/Index', [
            'data' => FileViewModel::index($project),
            'menu' => ProjectViewModel::menu($project),
        ]);
    }
}
