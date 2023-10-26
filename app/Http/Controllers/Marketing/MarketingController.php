<?php

namespace App\Http\Controllers\Marketing;

use App\Helpers\StringHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MarketingController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Marketing/Index');
    }
}
