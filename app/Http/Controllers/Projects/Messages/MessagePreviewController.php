<?php

namespace App\Http\Controllers\Projects\Messages;

use App\Helpers\StringHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessagePreviewController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        return response()->json([
            'data' => StringHelper::parse($request->input('body')),
        ], 200);
    }
}
