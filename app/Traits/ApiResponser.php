<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponser {

    protected function apiResponse($data, $error, $message)
{
    return response()->json([
        'data' => $data,
        'error' => $error,
        'message' => $message
    ]);
}

    protected function successResponse($data, $message = null, $code = 200): JsonResponse
    {
        return response()->json([
            'status'  => 'success',
            'message' => $message,
            'data'    => $data
        ], $code);
    }

    protected function successResponseWithPagination($data, $message = null, $code = 200): JsonResponse
    {
        $response = array_merge(
            [
                'status'  => 'success',
                'message' => $message,
            ],
            $data->response()->getData(true)
        );

        return response()->json($response, $code);
    }

    protected function errorResponse($message = null, $code): JsonResponse
    {
        return response()->json([
            'status'  => 'error',
            'message' => $message,
            'data'    => null,
        ], $code);
    }

    protected function errorWithValidationResponse($message = null, $errors = null, $code = 422): JsonResponse
    {
        return response()->json([
            'status'  => 'error',
            'message' => $message,
            'errors'  => $errors,
            'data'    => null,
        ], $code);
    }

}
