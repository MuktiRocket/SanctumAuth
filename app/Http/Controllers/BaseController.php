<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller
{
    protected function respond(string $message, int $code, array $data = [])
    {
        $payload = [];

        $payload['message'] = $message;
        if (!empty($data)) {
            $payload['data'] = $data;
        }

        return response()->json($payload, $code);
    }
}
