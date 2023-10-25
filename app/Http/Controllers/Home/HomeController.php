<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
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
