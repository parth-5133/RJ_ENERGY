<?php

namespace App\Helpers;

use App\Constants\ResStatusCode;

class ApiResponse
{
    public static function success($data = null, $message = 'Operation successful', $returnValue=null , $code = ResStatusCode::OK )
    {
        return response()->json([
            'status' => $code,
            'message' => $message,
            "returnValue" => $returnValue,
            'data' => $data,
        ], $code->value);
    }

    public static function error($errors = null, $message = 'An error occurred', $code = ResStatusCode::BAD_REQUEST )
    {
        $flattenedErrors = [];

        if ($errors instanceof \Illuminate\Support\MessageBag || is_array($errors)) {
            foreach ($errors as $field => $errorMessages) {
                $flattenedErrors[$field] = is_array($errorMessages) ? implode(' ', $errorMessages) : $errorMessages;
            }
        } else {
            $flattenedErrors = $errors;
        }

        return response()->json([
            'status' => $code->value,
            'message' => $message,
            'errors' => $flattenedErrors,
        ], $code->value);
    }

    public static function verifyError($message = 'An error occurred', $code = ResStatusCode::UNAUTHORIZED)
    {
        return response()->json([
            'status' => $code,
            'message' => $message
        ], $code->value);
    }
}
