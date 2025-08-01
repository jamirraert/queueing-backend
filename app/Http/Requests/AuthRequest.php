<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
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
        switch($this->route()->getName()) {
            case 'auth.login':
                return $this->login();
            case 'auth.register':
                return $this->register();
            default:
                return [];
        }
    }
    /**
     * @return array
     */
    public function login():array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string|min:8'
        ];
    }

    /**
     * @return array
     */
    public function register():array
    {
        return [
            'first_name' => 'required|string',
            'middle_name' => 'nullable|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|integer|exists:roles,id'
        ];
    }
}
