<?php

namespace App\Http\Controllers\Settings\Personalize;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\DestroyUser;
use App\Services\InviteUser;
use App\Services\UpdateUserPermission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PersonalizeOfficeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Settings/Personalize/Office/Index', [
            'data' => PersonalizeUserViewModel::data(auth()->user()),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Settings/Personalize/Office/Create', [
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

    public function edit(Request $request, User $user): Response
    {
        return Inertia::render('Settings/Personalize/Office/Edit', [
            'data' => PersonalizeUserViewModel::edit($user),
        ]);
    }

    public function update(Request $request, User $user): JsonResponse
    {
        (new UpdateUserPermission)->execute([
            'author_id' => auth()->user()->id,
            'user_id' => $user->id,
            'permissions' => $request->input('permissions'),
        ]);

        return response()->json([
            'data' => route('settings.personalize.user.index'),
        ], 200);
    }

    public function destroy(Request $request, User $user): JsonResponse
    {
        (new DestroyUser)->execute([
            'author_id' => auth()->user()->id,
            'user_id' => $user->id,
        ]);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
