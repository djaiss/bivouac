<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Projects\ProjectViewModel;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function show(Request $request, User $user): Response
    {
        return Inertia::render('Projects/Create', [
            'data' => ProjectViewModel::create(),
        ]);
    }
}
