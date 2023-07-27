<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Search\SearchViewModel;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskSearchUserController extends Controller
{
    public function index(Request $request, Project $project, Task $task): JsonResponse
    {
        $assignees = $task->users;

        $searchResults = SearchViewModel::users(
            organization: auth()->user()->organization,
            term: $request->input('term'),
        );

        foreach ($assignees as $assignee) {
            foreach ($searchResults as $key => $searchResult) {
                if ($assignee->id === $searchResult['id']) {
                    $searchResults->forget($key);
                }
            }
        }

        return response()->json([
            'data' => $searchResults,
        ], 200);
    }
}
