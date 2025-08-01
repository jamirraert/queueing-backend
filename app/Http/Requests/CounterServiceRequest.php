<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CounterServiceRequest extends FormRequest
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
        switch($this->route()->getName())
        {
            case "staff.store":
                return $this->storeCounterService();
            default: 
                return [];
        }
    }
    
    /**
     * @return array
     */
    public function storeCounterService():array
    {
        return [
            "user_id" => "required|integer|unique:counter_services,user_id",
            "counter_id" => "required|integer|unique:counter_services,counter_id",
            "service_id" => "required|integer|unique:counter_services,service_id"
        ];
    }
}
