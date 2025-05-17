<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GeneralHelper
{
    /**
     * Generate a JSON response.
     *
     * This static method constructs a JSON response based on the provided parameters.
     * It checks the presence of data and message, and formats the response accordingly.
     *
     * @param string|null $message An optional message to include in the response.
     * @param array|AnonymousResourceCollection|JsonResource $data The data to be returned in the response.
     * @param int $httpCode The HTTP status code for the response.
     * @return JsonResponse|null Returns a JsonResponse containing the formatted response data, or null if conditions are not met.
     */
    public static function response(
        ?string $message,
        array|AnonymousResourceCollection|JsonResource $data,
        int $httpCode
    ): ?JsonResponse {
        // Check if data it's not empty
        if (!empty($data) && $message) {
            return response()->json([
                'message' => $message,
                'data' => $data
            ], $httpCode);
        } elseif (empty($data) && $message) {
            return response()->json([
                'message' => $message
            ], $httpCode);
        } elseif (!empty($data) && !$message) {
            return response()->json([
                'data' => $data
            ], $httpCode);
        }
    }
}
