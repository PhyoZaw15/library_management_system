<?php

namespace App\Services;

class ApiResponse
{
    public static function success($data, $message = 'Request was successful', $status = 200)
    {
        $data = [
            'status' => true,
            'message' => $message,
            'data' => $data,
        ];

        return response()->json($data);        
    }

    public static function error($message, $status = 400)
    {
        $data = [
            'status' => false,
            'message' => $message,
        ];

        return response()->json($data);        
    }
}
