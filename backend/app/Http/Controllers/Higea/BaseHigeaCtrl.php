<?php

namespace App\Http\Controllers\Higea;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BaseHigeaCtrl extends Controller
{
    public function success($message = "", $data = [], $statusCode = 200): JsonResponse
    {
        return response()->json(array_merge($message, $data), $statusCode);
    }
    public function error($message = [], $statusCode = 400, $error = [], $data = []): JsonResponse
    {
        return response()->json(array_merge($message, $error, $data), $statusCode);
    }
}
