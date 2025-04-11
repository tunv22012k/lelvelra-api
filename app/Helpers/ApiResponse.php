<?php

namespace App\Helpers;

class ApiResponse
{
    public static function success($data = null, $message = 'Success', $status = 200)
    {
        return response()->json([
            'status'    => 'success',
            'message'   => $message,
            'data'      => $data
        ], $status);
    }

    public static function error($message = 'Something went wrong', $status = 500, $errors = null)
    {
        return response()->json([
            'status'    => 'error',
            'message'   => $message,
            'errors'    => $errors
        ], $status);
    }

    public static function validation($errors, $message = 'Validation failed', $status = 422)
    {
        return self::error($message, $status, $errors);
    }

    public static function unauthorized($message = 'Unauthorized')
    {
        return self::error($message, 401);
    }

    public static function forbidden($message = 'Forbidden')
    {
        return self::error($message, 403);
    }
}
