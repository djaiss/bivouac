<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use App\Models\Office;
use App\Models\Project;
use App\Services\CreateProject;
use App\Services\DestroyOffice;
use App\Services\UpdateOffice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SearchController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Search/Index');
    }

    public function show(Request $request): JsonResponse
    {
        return response()->json([
            'data' => SearchViewModel::data(
                organization: auth()->user()->organization,
                term: $request->input('term')
            ),
        ], 200);
    }
}
