<?php

namespace App\manager\auth;

use App\Models\User;
use App\utilities\DynamicResponse;
use Illuminate\Http\JsonResponse;

abstract class Gateway extends DynamicResponse
{
    protected User $user;

    public function __construct()
    {
        $this->user = new User();
    }

    abstract public function authenticate(array $data):mixed;
    abstract public function registerUser(array $data):mixed;

    public function responseWithToken(array $payload, int $status = 200):array|JsonResponse
    {
        $token = $this->user->createToken($this->user->email)->plainTextToken;
        $payload['token'] = $token;
        return $this->jsonResponse($payload, $status);
    }

    public function responseWithError(array $payload, int $status = 200):array|JsonResponse
    {
         return $this->jsonResponse($payload, $status);
    }
}
