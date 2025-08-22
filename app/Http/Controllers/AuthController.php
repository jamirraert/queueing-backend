<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\manager\auth\Gateway;

class AuthController extends Controller
{
    protected Gateway $authGateway;

    public function __construct(Gateway $authGateway)
    {
        $this->authGateway = $authGateway;
    }

    public function authenticate(AuthRequest $request)
    {
        return $this->authGateway->authenticate($request->validated());
    }

    public function registerUser(AuthRequest $request)
    {
        return $this->authGateway->registerUser($request->validated());
    }
}
