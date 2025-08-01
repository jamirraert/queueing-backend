<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class Auth extends AuthGateway
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function login(array $payload): array|JsonResponse
    {
        try {
            $this->user = User::where('email', $payload['email'])->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Email does not exist',
            ], 404);
        }

        if (!Hash::check($payload['password'], $this->user->password)) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        $this->createToken();

        return response()->json([
            'success' => true,
            'message' => 'Successfully loggedin',
            'user' => $this->user,
            'token' => $this->token
        ]);
    }

    public function register(array $payload): array|JsonResponse
    {
        $password = $payload['password'];
        $payload = $this->lowerString($payload);
        $payload['password'] = $password;

        $this->user = User::create([
            'first_name' => $payload['first_name'],
            'middle_name' => $payload['middle_name'],
            'last_name' => $payload['last_name'],
            'email' => $payload['email'],
            'password' => bcrypt($payload['password']),
            'role_id' => $payload['role_id']
        ]);

        if(!$this->user) {
            return response()->json([
                "message" => "Something went wrong",
            ], 500);
        }

        $this->createToken();

        return response()->json([
            'success' => true,
            'message' => 'Successfully created user',
            'user' => $this->user,
            'token' => $this->token
        ], 201);
    }
}
