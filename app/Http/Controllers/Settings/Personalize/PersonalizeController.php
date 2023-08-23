<?php

namespace App\Http\Controllers\Settings\Personalize;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class PersonalizeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Settings/Index', [
            'data' => PersonalizeViewModel::data(),
        ]);
    }
}
