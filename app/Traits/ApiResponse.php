<?php

namespace App\Traits;

trait ApiResponse
{
    public function success($data = null, $message = 'Success')
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
        ]);
    }

    public function error($message = 'Error', $code = 400)
    {
        return response()->json([
            'data' => null,
            'message' => $message,
        ], $code);
    }
}
