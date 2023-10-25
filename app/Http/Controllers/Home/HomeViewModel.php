<?php

namespace App\Http\Controllers\Home;

use App\Models\ProjectVisit;
use App\Models\Task;
use App\Models\User;

class HomeViewModel
{
    public static function index(User $user): array
    {
        $latestVisits = ProjectVisit::where('user_id', $user->id)
            ->with('project')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get()
            ->map(fn (ProjectVisit $visit) => [
                'id' => $visit->id,
                'project' => [
                    'id' => $visit->project->id,
                    'name' => $visit->project->name,
                ],
            ]);

        $assignedTasks = Task::where('is_completed', false)
            ->whereHas('users', fn ($query) => $query->where('user_id', $user->id))
            ->with('taskList')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (Task $task) => [
                'id' => $task->id,
                'title' => $task->title,
                'task_list' => [
                    'id' => $task->taskList->id,
                    'name' => $task->taskList->name,
                ],
                'project' => [
                    'id' => $task->taskList->project_id,
                    'name' => $task->taskList->project->name,
                    'url' => [
                        'show' => route('projects.show', $task->taskList->project_id),
                    ]
                ],
                'assignees' => $task->users()
                    ->get()
                    ->map(fn (User $user) => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'avatar' => $user->avatar,
                        'url' => route('users.show', $user),
                    ]),
                'url' => [
                    'show' => route('tasks.show', [
                        'project' => $task->taskList->project_id,
                        'task' => $task->id,
                    ]),
                ],
            ]);

        return [
            'latest_visits' => $latestVisits,
            'tasks' => $assignedTasks,
        ];
    }
}
