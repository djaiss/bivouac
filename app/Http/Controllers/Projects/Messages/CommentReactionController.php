<?php

namespace App\Http\Controllers\Projects\Messages;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Reactions\ReactionViewModel;
use App\Models\Comment;
use App\Models\Message;
use App\Models\Project;
use App\Services\AddReactionToComment;
use App\Services\AddReactionToMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentReactionController extends Controller
{
    public function store(Request $request, Project $project, Message $message, Comment $comment): JsonResponse
    {
        $reaction = (new AddReactionToComment)->execute([
            'user_id' => auth()->user()->id,
            'comment_id' => $comment->id,
            'emoji' => $request->input('emoji'),
        ]);

        return response()->json([
            'data' => ReactionViewModel::dto($reaction),
        ], 201);
    }
}
