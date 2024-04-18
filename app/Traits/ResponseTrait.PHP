<?php

namespace App\Traits;

trait ResponseTrait
{


    public function success($data, string $message, int $code = 200)
    {
        return response()->json([
            'error' => false,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public function error($data = null, string $message = null, int $code)
    {
        return response()->json([
            'error' => true,
            'data' => $data,
            'message' => $message
        ], $code);
    }
}
