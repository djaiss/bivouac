<?php

namespace App\Http\Controllers\Projects\Files;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Services\AddFileToTask;
use App\Services\UploadFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskFileController extends Controller
{
    public function index(Request $request, Project $project, Task $task): JsonResponse
    {
        $files = $task->getMedia('*')
            ->map(fn ($file) => FileViewModel::dto($file));

        return response()->json([
            'data' => $files,
        ], 200);
    }

    public function store(Request $request, Project $project, Task $task): JsonResponse
    {
        $path = (new UploadFile)->execute([
            'file' => $request->file('file'),
        ]);

        $media = (new AddFileToTask)->execute([
            'user_id' => auth()->user()->id,
            'task_id' => $task->id,
            'file_path' => $path,
        ]);

        return response()->json([
            'data' => FileViewModel::dto($media),
        ], 201);
    }
}
