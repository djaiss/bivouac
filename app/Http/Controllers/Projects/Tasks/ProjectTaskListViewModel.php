<?php

namespace App\Http\Controllers\Projects\Tasks;

use App\Http\Controllers\Tasks\TaskListViewModel;
use App\Models\Project;
use App\Models\TaskList;
use App\Models\User;

class ProjectTaskListViewModel
{
    public static function index(Project $project): array
    {
        $taskLists = $project->taskLists()
            ->get()
            ->map(fn (TaskList $taskList) => TaskListViewModel::dto($taskList));

        return [
            'project' => [
                'id' => $project->id,
                'name' => $project->name,
                'is_public' => $project->is_public,
            ],
            'task_lists' => $taskLists,
            'url' => [
                'create' => route('task_lists.create', [
                    'project' => $project->id,
                ]),
                'breadcrumb' => [
                    'home' => route('profile.edit'),
                ],
            ],
        ];
    }

    public static function create(Project $project): array
    {
        return [
            'project' => [
                'name' => $project->name,
            ],
            'url' => [
                'store' => route('task_lists.store', [
                    'project' => $project->id,
                ]),
                'breadcrumb' => [
                    'projects' => route('projects.index'),
                    'project' => route('projects.show', [
                        'project' => $project->id,
                    ]),
                    'tasks' => route('tasks.index', [
                        'project' => $project->id,
                    ]),
                ],
            ],
        ];
    }
}
