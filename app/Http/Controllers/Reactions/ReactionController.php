<?php

namespace App\Http\Controllers\Reactions;

use App\Http\Controllers\Controller;
use App\Models\Reaction;
use App\Services\DestroyReaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReactionController extends Controller
{
    public function destroy(Request $request, Reaction $reaction): JsonResponse
    {
        (new DestroyReaction)->execute([
            'user_id' => auth()->user()->id,
            'reaction_id' => $reaction->id,
        ]);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
