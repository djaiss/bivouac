<?php

namespace App\Http\Controllers\Projects\Messages;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Reactions\ReactionViewModel;
use App\Models\Message;
use App\Models\Project;
use App\Services\AddReactionToMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageReactionController extends Controller
{
    public function store(Request $request, Project $project, Message $message): JsonResponse
    {
        $reaction = (new AddReactionToMessage)->execute([
            'user_id' => auth()->user()->id,
            'message_id' => $message->id,
            'emoji' => $request->input('emoji'),
        ]);

        return response()->json([
            'data' => ReactionViewModel::dto($reaction),
        ], 201);
    }
}
