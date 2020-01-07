<?php


namespace App\Traits;

trait ApiResponses
{
    public static function errorResponse(int $statusCode, string $errorCode, string $message)
    {
        return response()->json([
            "error" => [
                "status" => $statusCode,
                "code" => $errorCode,
                "message" => $message,
            ]

        ], $statusCode);
    }
    public function successResponse(string $message, $data){
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ]);
    }
}
