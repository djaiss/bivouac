<?php

namespace App\Http\Controllers\Home;

use App\Helpers\StringHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
}
