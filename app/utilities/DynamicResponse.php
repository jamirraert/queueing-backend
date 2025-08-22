<?php

namespace App\utilities;

use Illuminate\Http\JsonResponse;

class DynamicResponse
{
    protected function jsonResponse(array $data = [], int $status = 200):JsonResponse
    {
        $response['success'] = $data['success'] ?? true;

        if(isset($data['message'])) {
            $response['message'] = $data['message'];
        }

        if(isset($data['token'])) {
            $response['token'] = $data['token'];
        }

        if(isset($data['data']) && is_array($data['data'])) {
            $response['data'] = $data['data'];
        }

        return response()->json($response, $status);
    }
}
