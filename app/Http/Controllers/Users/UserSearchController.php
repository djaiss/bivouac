<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Search\SearchViewModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserSearchController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $searchResults = SearchViewModel::users(
            organization: auth()->user()->organization,
            term: $request->input('term'),
        );

        return response()->json([
            'data' => $searchResults,
        ], 200);
    }
}
