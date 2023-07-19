<?php

namespace App\Http\Controllers\Projects\Tasks;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Projects\ProjectViewModel;
use App\Models\Project;
use App\Models\TaskList;
use App\Services\CreateTaskList;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProjectTaskListController extends Controller
{
    public function index(Project $project): Response
    {
        return Inertia::render('Projects/Tasks/Index', [
            'data' => ProjectTaskListViewModel::index($project),
            'menu' => ProjectViewModel::menu($project),
        ]);
    }

    public function create(Project $project): Response
    {
        return Inertia::render('Projects/Tasks/Create', [
            'data' => ProjectTaskListViewModel::create($project),
        ]);
    }

    public function store(Request $request, Project $project): JsonResponse
    {
        $taskList = (new CreateTaskList)->execute([
            'user_id' => auth()->user()->id,
            'name' => $request->input('name'),
        ]);

        $taskList->project_id = $project->id;
        $taskList->tasklistable_id = $project->id;
        $taskList->tasklistable_type = Project::class;
        $taskList->save();

        return response()->json([
            'data' => route('tasks.index', [
                'project' => $project->id,
            ]),
        ], 201);
    }

    public function edit(Project $project, TaskList $taskList): Response
    {
        if ($taskList->project_id !== $project->id) {
            abort(404);
        }

        return Inertia::render('Projects/Tasks/Edit', [
            'data' => ProjectTaskListViewModel::edit($project, $taskList),
        ]);
    }
}
