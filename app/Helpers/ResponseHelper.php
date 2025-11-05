<?php
namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class ResponseHelper
{
    public static function sendResponse(mixed $result, String $message, $httpCode = 200) {
        $response = [
            'error' => false,
            'code' => "0",
            'message' => $message,
            'data' => $result,
        ];
        return Response::json($response, $httpCode);
    }

    public static function sendError(String $error, String|int $code, $httpCode = 400)
    {
        $response = [
            'error' => true,
            'code' => $code . "",
            'message' => $error,
            'data' => null,
        ];

        return Response::json($response, $httpCode);
    }
}
