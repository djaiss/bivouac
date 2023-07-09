<?php

namespace App\Http\Controllers;

use App\Helpers\StringHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PreviewController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        return response()->json([
            'data' => StringHelper::parse($request->input('body')),
        ], 200);
    }
}
