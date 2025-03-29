<?php

declare (strict_types=1);

namespace App\Traits;


use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

trait ApiResponse
{
    /**
     * Return a success response.
     *
     * @param  mixed  $data
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    public function successResponse(mixed $data, string $message = '', int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    /**
     * Return an error response.
     *
     * @param string $message
     * @param int|null $statusCode
     * @return JsonResponse
     */
    public function errorResponse(string $message, int $statusCode = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $statusCode ?? 500);
    }
}
