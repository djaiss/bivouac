<?php

namespace App\Http\Controllers\Projects\Messages;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Projects\ProjectViewModel;
use App\Models\Message;
use App\Models\Project;
use App\Services\CreateMessage;
use App\Services\DestroyMessage;
use App\Services\MarkMessageAsRead;
use App\Services\UpdateMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MessageController extends Controller
{
    public function index(Project $project): Response
    {
        return Inertia::render('Projects/Messages/Index', [
            'data' => MessageViewModel::index($project, auth()->user()),
            'menu' => ProjectViewModel::menu($project),
        ]);
    }

    public function create(Project $project): Response
    {
        return Inertia::render('Projects/Messages/Create', [
            'data' => MessageViewModel::create($project),
        ]);
    }

    public function store(Request $request, Project $project): JsonResponse
    {
        $message = (new CreateMessage)->execute([
            'user_id' => auth()->user()->id,
            'project_id' => $project->id,
            'title' => $request->input('title'),
            'body' => $request->input('body'),
        ]);

        return response()->json([
            'data' => route('messages.show', [
                'project' => $project->id,
                'message' => $message->id,
            ]),
        ], 201);
    }

    public function show(Request $request, Project $project, Message $message): Response
    {
        (new MarkMessageAsRead)->execute([
            'user_id' => auth()->user()->id,
            'message_id' => $message->id,
        ]);

        return Inertia::render('Projects/Messages/Show', [
            'data' => MessageViewModel::show($message),
            'menu' => ProjectViewModel::menu($project),
        ]);
    }

    public function edit(Request $request, Project $project, Message $message): Response
    {
        return Inertia::render('Projects/Messages/Edit', [
            'data' => MessageViewModel::edit($message),
            'menu' => ProjectViewModel::menu($project),
        ]);
    }

    public function update(Request $request, Project $project, Message $message): JsonResponse
    {
        (new UpdateMessage)->execute([
            'user_id' => auth()->user()->id,
            'message_id' => $message->id,
            'title' => $request->input('title'),
            'body' => $request->input('body'),
        ]);

        return response()->json([
            'data' => route('messages.show', [
                'project' => $project->id,
                'message' => $message->id,
            ]),
        ], 200);
    }

    public function destroy(Request $request, Project $project, Message $message): JsonResponse
    {
        (new DestroyMessage)->execute([
            'user_id' => auth()->user()->id,
            'message_id' => $message->id,
        ]);

        return response()->json([
            'data' => route('messages.index', [
                'project' => $project->id,
            ]),
        ], 200);
    }
}
