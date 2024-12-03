<?php

namespace App\Traits;

trait ApiResponse
{
    /**
     * Success Response
     * 
     * @param array|null $data
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function successResponse($data = null, string $message = "Process successful", int $code = 200)
    {
        $response = [
            'success' => true,
            'message' => $message
        ];

        if (!is_null($data)) { 
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }

    /**
     * Error Response
     *
     * @param string $message
     * @param int $code
     * @param mixed|null $errors
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorResponse(string $message, int $code = 400, $errors = null)
    {
        $response = [
            'success' => false,
            'message' => $message
        ];

        if (!is_null($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    /**
     * Created Response
     *
     * @param array|null $data
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createdResponse($data = null, string $message = 'Resource created successfully')
    {
        return $this->successResponse($data, $message, 201);
    }

    /**
     * No Content Response
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function noContentResponse(string $message = 'Resource deleted successfully')
    {
        return $this->successResponse(null, $message, 204);
    }

    /**
     * Not Found Response
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function notFoundResponse(string $message = 'Resource not found')
    {
        return $this->errorResponse($message, 404);
    }

    /**
     * Validation Error Response
     *
     * @param array $errors
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function validationErrorResponse(array $errors, string $message = 'Validation failed')
    {
        return $this->errorResponse($message, 422, $errors);
    }
}
