<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivateUserAccount;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ValidateInvitationController extends Controller
{
    public function show(Request $request, string $code): Response|RedirectResponse
    {
        if (! $request->hasValidSignature()) {
            abort(401);
        }

        $user = User::where('invitation_code', $code)->firstOrFail();

        if ($user->email_verified_at) {
            return redirect()->route('dashboard');
        }

        return Inertia::render('Auth/ValidateInvitation', [
            'data' => ValidateInvitationViewModel::data($user),
        ]);
    }

    public function update(Request $request, string $code): JsonResponse
    {
        $user = (new ActivateUserAccount)->execute([
            'invitation_code' => $code,
            'password' => $request->input('password'),
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
        ]);

        if (Auth::attempt([
            'email' => $user->email,
            'password' => $request->input('password'),
        ])) {
            $request->session()->regenerate();

            return response()->json([
                'data' => route('dashboard'),
            ], 200);
        }

        throw new Exception('Something went wrong.');
    }
}
