<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Reactions\ReactionViewModel;
use App\Models\Comment;
use App\Services\AddReactionToComment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentReactionController extends Controller
{
    public function store(Request $request, Comment $comment): JsonResponse
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
