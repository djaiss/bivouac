<?php

namespace App\Http\Controllers\Projects\Messages;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Message;
use App\Models\Project;
use App\Services\AddCommentToMessage;
use App\Services\DestroyCommentOfMessage;
use App\Services\UpdateCommentOfMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageCommentController extends Controller
{
    public function store(Request $request, Project $project, Message $message): JsonResponse
    {
        $comment = (new AddCommentToMessage)->execute([
            'user_id' => auth()->user()->id,
            'message_id' => $message->id,
            'body' => $request->input('body'),
        ]);

        return response()->json([
            'data' => MessageViewModel::dtoComment($message, $comment),
        ], 201);
    }

    public function update(Request $request, Project $project, Message $message, Comment $comment): JsonResponse
    {
        $comment = (new UpdateCommentOfMessage)->execute([
            'user_id' => auth()->user()->id,
            'message_id' => $message->id,
            'comment_id' => $comment->id,
            'body' => $request->input('body'),
        ]);

        return response()->json([
            'data' => MessageViewModel::dtoComment($message, $comment),
        ], 200);
    }

    public function destroy(Request $request, Project $project, Message $message, Comment $comment): JsonResponse
    {
        (new DestroyCommentOfMessage)->execute([
            'user_id' => auth()->user()->id,
            'message_id' => $message->id,
            'comment_id' => $comment->id,
        ]);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
