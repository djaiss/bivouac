<?php

namespace App\Http\Controllers\OneOnOnes;

use App\Http\Controllers\Controller;
use App\Models\OneOnOne;
use App\Models\OneOnOneEntry;
use App\Services\CreateOneOnOneEntry;
use App\Services\DestroyOneOnOneEntry;
use App\Services\ToggleOneOnOneEntry;
use App\Services\UpdateOneOnOneEntry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OneOnOneEntryController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $oneOnOneEntry = (new CreateOneOnOneEntry)->execute([
            'user_id' => auth()->user()->id,
            'one_on_one_id' => $request->oneOnOne,
            'body' => $request->input('body'),
        ]);

        return response()->json([
            'data' => OneOnOneViewModel::dtoEntry($oneOnOneEntry),
        ], 201);
    }

    public function toggle(Request $request, OneOnOne $oneOnOne, OneOnOneEntry $entry): JsonResponse
    {
        $oneOnOneEntry = (new ToggleOneOnOneEntry)->execute([
            'user_id' => auth()->user()->id,
            'one_on_one_entry_id' => $entry->id,
        ]);

        return response()->json([
            'data' => OneOnOneViewModel::dtoEntry($oneOnOneEntry),
        ], 200);
    }

    public function update(Request $request, OneOnOne $oneOnOne, OneOnOneEntry $entry): JsonResponse
    {
        $oneOnOneEntry = (new UpdateOneOnOneEntry)->execute([
            'user_id' => auth()->user()->id,
            'one_on_one_entry_id' => $entry->id,
            'body' => $request->input('body'),
        ]);

        return response()->json([
            'data' => OneOnOneViewModel::dtoEntry($oneOnOneEntry),
        ], 200);
    }

    public function destroy(Request $request, OneOnOne $oneOnOne, OneOnOneEntry $entry): JsonResponse
    {
        (new DestroyOneOnOneEntry)->execute([
            'user_id' => auth()->user()->id,
            'one_on_one_entry_id' => $entry->id,
        ]);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
