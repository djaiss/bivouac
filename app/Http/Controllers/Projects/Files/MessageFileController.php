<?php

namespace App\Http\Controllers\Projects\Files;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Project;
use App\Services\AddFileToMessage;
use App\Services\UploadFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageFileController extends Controller
{
    public function index(Request $request, Project $project, Message $message): JsonResponse
    {
        $files = $message->getMedia('*')
            ->map(fn ($file) => FileViewModel::dto($file));

        return response()->json([
            'data' => $files,
        ], 200);
    }

    public function store(Request $request, Project $project, Message $message): JsonResponse
    {
        $path = (new UploadFile)->execute([
            'file' => $request->file('file'),
        ]);

        $media = (new AddFileToMessage)->execute([
            'user_id' => auth()->user()->id,
            'message_id' => $message->id,
            'file_path' => $path,
        ]);

        return response()->json([
            'data' => FileViewModel::dto($media),
        ], 201);
    }
}
