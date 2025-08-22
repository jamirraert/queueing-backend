<?php

namespace App\manager\auth;

use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthGateway extends Gateway
{
    public function authenticate(array $data): array|JsonResponse
    {
        try {

            $this->user = User::where('email', $data['email'])->first();
            if(!$this->user || !Hash::check($data['password'], $this->user->password)) {
                $payload = [
                    'success' => false,
                    'message' => 'Invalid credentials'
                ];
                return $this->responseWithError($payload, 401);
            }
            
            $payload = [
                'message' => 'Successfully logging-in',
                'data' => $this->user->toArray()
            ];
    
            return $this->responseWithToken($payload, 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ]);
        }
    }

    public function registerUser(array $data): array|JsonResponse
    {
        try {
            $response = DB::transaction(function() use ($data) {
                $this->user = User::create([
                    'first_name' => $data['first_name'],
                    'middle_name' => $data['middle_name'] ?? null,
                    'last_name' => $data['last_name'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']),
                    'role' => $data['role']
                ]);

                $payload = [
                    'message' => 'Successfully created user.',
                    'data' => $this->user->toArray()
                ];
                return $this->responseWithToken($payload, 201);
            });

            return $response;
        } catch (Exception $e) {
            $payload = [
                'success' => false,
                'message' => 'Registration failed: ' . $e->getMessage()()
            ];

            return $this->responseWithError($payload, 500);
        }
    }
}
