<?php

namespace App\utilities;

use Illuminate\Http\JsonResponse;

class DynamicResponse
{
    protected ?string $errorMessage;
    protected ?int $statusCode;

    public function __construct()
    {
       $this->errorMessage = null; 
       $this->statusCode = null;
    }
    protected function jsonResponse(?array $data = null, int $status = 200):JsonResponse
    {
        /**
         * @return {500} status
         */
        if($data === null) {
            $errorMessage = $this->errorMessage !== null ? $this->errorMessage : null; 
            $message = 'Something went wrong.';
            return response()->json([
                'success' => false,
                'message' => $message . ' ' . $errorMessage
            ], $this->statusCode);
        }


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

    public function setErrorMessage(string $errorMessage, int $statusCode = 500)
    {
        $this->statusCode = $statusCode === 404 ? 404 : 500;
        $this->errorMessage = $errorMessage;
    }
}
