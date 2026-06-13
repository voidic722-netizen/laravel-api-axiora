<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

/**
 * Provides the standardized API response envelope used across all controllers.
 *
 *   Success:    { "success": true,  "message": "", "data": {} }
 *   Error:      { "success": false, "message": "", "errors": {} }
 *   Pagination: { "success": true,  "message": "", "data": [], "meta": {} }
 */
trait ApiResponse
{
    protected function success(mixed $data = null, string $message = '', int $status = 200, ?array $meta = null): JsonResponse
    {
        $payload = [
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ];

        if ($meta !== null) {
            $payload['meta'] = $meta;
        }

        return response()->json($payload, $status);
    }

    protected function error(string $message, int $status = 422, mixed $errors = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors'  => $errors ?? (object) [],
        ], $status);
    }

    protected function validationError(array $errors, string $message = 'The given data was invalid.'): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors'  => $errors,
        ], 422);
    }
}
