<?php

namespace App\Http\Controllers\Settings\Personalize;

use App\Http\Controllers\Controller;
use App\Services\InviteUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PersonalizeUserController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Settings/Personalize/Users/Index', [
            'data' => PersonalizeUserViewModel::data(auth()->user()),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Settings/Personalize/Users/Create', [
            'data' => PersonalizeUserViewModel::data(auth()->user()),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        (new InviteUser)->execute([
            'author_id' => auth()->user()->id,
            'email' => $request->input('email'),
        ]);

        return response()->json([
            'data' => route('settings.personalize.user.index'),
        ], 201);
    }
}
