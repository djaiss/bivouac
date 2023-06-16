<?php

namespace App\Http\Controllers\Settings\Personalize;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Profile\ProfileViewModel;
use Inertia\Inertia;
use Inertia\Response;

class PersonalizeUserController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Settings/Personalize/Users/Index', [
            'data' => PersonalizeViewModel::data(),
        ]);
    }
}
