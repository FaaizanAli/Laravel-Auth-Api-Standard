<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait CanResponseTrait
{
    /**
     * The Success Response Method for API
     *
     * @param mixed|null $data Data to be sent
     * @param string $message Message to be sent
     * @param int $code Status code to be sent
     * @return JsonResponse Response that will be sent
     */
    protected function success(
        string $message = 'success',
        int    $code = Response::HTTP_OK,
        mixed  $data = null,
    ): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data
        ], status: $code);
    }
    /**
     * The Error Response Method for API
     *
     * @param mixed|null $data Data to be sent
     * @param string $message Message to be sent
     * @param int $code Status code to be sent
     * @return JsonResponse Response that will be sent
     */
    protected function error(
        mixed  $data = null,
        string $message = 'error',
        int    $code = Response::HTTP_BAD_REQUEST
    ): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data
        ], status: $code);
    }

    /**
     * The NotFound Response Method for API
     *
     * @param mixed|null $data Data to be sent
     * @param string $message Message to be sent
     * @param int $code Status code to be sent
     * @return JsonResponse Response that will be sent
     */
    protected function notFound(
        mixed  $data = null,
        string $message = 'not found',
        int    $code = Response::HTTP_NOT_FOUND
    ): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data
        ], status: $code);
    }
}
