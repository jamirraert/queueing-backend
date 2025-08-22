<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
{
    private const LOGIN_ROUTE = 'auth.login';
    private const REGISTER_ROUTE = 'auth.register';
    private const INVALID_ROUTE = 'Invalid route for AuthRequest validation.';

    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $routeName = $this->route()->getName();
        switch($routeName) {
            case self::LOGIN_ROUTE:
                return $this->authenticationRules();
            case self::REGISTER_ROUTE:
                return $this->registrationRules();
            default:
                abort(400, self::INVALID_ROUTE);
        }
    }

    public function authenticationRules():array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string'
        ];
    }

    public function registrationRules():array
    {
        return [
            'first_name' => 'required|string',
            'middle_name' => 'nullable|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|integer'
        ];
    }
}
