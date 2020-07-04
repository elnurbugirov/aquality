<?php
namespace App\Http\Controllers\System;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Config;
use Response;

class UsefulMethods
{

    public static function createResponse($code = 1, $message = "OK", $content = [], $array = 0, $status = 200)
    {

        $response = [
            'responseCode' => $code,
            "responseMessage" => $message,
            "responseContent" => ($array == 1) ? $content : [$content]
        ];

        return response($response, $status);
    }
}
