<?php

namespace App\Http\Controllers\Settings\Personalize;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Profile\ProfileViewModel;
use App\Services\UpdateUserInformation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class PersonalizeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Settings/Personalize/Index', [
            'data' => ProfileViewModel::data(auth()->user()),
        ]);
    }
}
