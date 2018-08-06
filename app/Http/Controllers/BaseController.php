<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{
    /**
     * @param string $mess
     * @param int    $code
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function throwError(string $mess, int $code): JsonResponse
    {
        return response()->json(['message' => $mess], $code);
    }
}
