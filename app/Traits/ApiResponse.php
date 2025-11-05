<?php

namespace App\Traits;

use App\Helpers\ResponseHelper;

trait ApiResponse
{
    //
    public function sendResponse(mixed $result, string $message, $httpCode = 200) {
        return ResponseHelper::sendResponse($result, $message, $httpCode);
    }

    public function sendError(string $error, string|int $code, $httpCode = 400) {
        return ResponseHelper::sendError($error, $code, $httpCode);
    }
}
