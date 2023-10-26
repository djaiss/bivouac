<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Services\HideWelcomeMessage;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Home/Index', [
            'data' => HomeViewModel::index(auth()->user()),
        ]);
    }

    public function hide(): JsonResponse
    {
        (new HideWelcomeMessage)->execute();

        return response()->json([
            'data' => true,
        ], 200);
    }
}
