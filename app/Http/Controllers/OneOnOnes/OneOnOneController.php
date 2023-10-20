<?php

namespace App\Http\Controllers\OneOnOnes;

use App\Http\Controllers\Controller;
use App\Services\CreateOneOnOne;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OneOnOneController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('OneOnOnes/Index', [
            'data' => OneOnOneViewModel::index(
                user: auth()->user(),
            ),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('OneOnOnes/Create', [
            'data' => OneOnOneViewModel::create(auth()->user()),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $oneOnOne = (new CreateOneOnOne)->execute([
            'user_id' => auth()->user()->id,
            'other_user_id' => $request->input('user_id'),
        ]);

        return response()->json([
            'data' => route('oneonones.show', $oneOnOne),
        ], 201);
    }
}
