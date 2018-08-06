<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

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
