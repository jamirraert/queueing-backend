<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

abstract class AuthGateway
{
    /**
     * @return {user, token}
     */
    protected User $user;
    protected string $token;
    public function __construct()
    {
        $this->user = new User();
        $this->token = "";
    }
     /**
     * Login method to authenticate a user and return a response.
     *
     * @param array $payload
     * @return array|JsonResponse
     */
    abstract public function login(array $payload):array | JsonResponse;

     /**
     * Register method to authenticate a user and return a response.
     *
     * @param array $payload
     * @return array|JsonResponse
     */
    abstract public function register(array $payload):array | JsonResponse;
     /**
     * Login method to authenticate a user and return a response.
     * 
     * @return token
     */
    public function createToken() : void
    {
        $this->token = $this->user->createToken($this->user->email)->plainTextToken;
    }

    public function destroyToken(User $user)
    {

        $currentToken = $user->currentAccessToken();

       if($currentToken) {
            $currentToken->delete();
            return response()->json([
                'message' => 'Logged out successfully.'
            ]);
       }

       return response()->json(['message' => 'No token found or user not authenticated'], 400);
    }

    public function lowerString(array $payload)
    {
        $newPayload = [];
        /**
         * @return array
         */
        foreach($payload as $key => $value) {
            $newPayload[$key] = is_string($value) ? strtolower($value) : $value;
        }

        return $newPayload;
    }
}
