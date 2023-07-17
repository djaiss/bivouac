<?php

namespace App\Http\Controllers\Projects\Tasks;

use App\Http\Controllers\Tasks\TaskListViewModel;
use App\Models\Project;
use App\Models\TaskList;
use App\Models\User;

class ProjectTaskListViewModel
{
    public static function index(Project $project, User $user): array
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
                'create' => route('messages.create', [
                    'project' => $project->id,
                ]),
                'breadcrumb' => [
                    'home' => route('profile.edit'),
                ],
            ],
        ];
    }
}
