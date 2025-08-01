<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Services\Auth\Auth;
use App\Services\Auth\AuthGateway;

class AuthController extends Controller
{
    public AuthGateway $auth;

    public function __construct(AuthGateway $authGateway)
    {
        $this->auth = $authGateway;
    }

    public function login(AuthRequest $request)
    {
        return $this->auth->login($request->validated());
    }

    public function register(AuthRequest $request)
    {
        return $this->auth->register($request->validated());
    }
}
