<?php
namespace Helpers;

class Response
{
    /**
     * Success response
     */
    public static function success($data = null, $message = 'Success', $code = 200)
    {
        http_response_code($code);
        echo json_encode([
            'success' => true,
            'message' => $message,
            'data' => $data
        ]);
        exit;
    }

    /**
     * Error response
     */
    public static function error($message = 'Error occurred', $code = 500, $details = null)
    {
        http_response_code($code);
        $response = [
            'success' => false,
            'message' => $message
        ];
        
        if ($details !== null) {
            $response['details'] = $details;
        }
        
        echo json_encode($response);
        exit;
    }

    /**
     * Not found response
     */
    public static function notFound($message = 'Resource not found')
    {
        self::error($message, 404);
    }

    /**
     * Validation error response
     */
    public static function validationError($errors, $message = 'Validation failed')
    {
        self::error($message, 422, ['validation_errors' => $errors]);
    }

    /**
     * Unauthorized response
     */
    public static function unauthorized($message = 'Unauthorized')
    {
        self::error($message, 401);
    }

    /**
     * Forbidden response
     */
    public static function forbidden($message = 'Forbidden')
    {
        self::error($message, 403);
    }
}