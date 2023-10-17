<?php

namespace App\Http\Controllers\Files;

use App\Http\Controllers\Controller;
use App\Services\UploadFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UploadFileController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        //dd($request->file('file'));

        (new UploadFile)->execute([
            'file' => $request->file('file'),
        ]);

        return response()->json([
            'data' => route('profile.edit'),
        ], 200);
    }
}
