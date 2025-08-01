<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OptionsRequest extends FormRequest
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
            case "options.counter.store":
                    return $this->storeCounter();

            case "options.counter.update":
                    return $this->updateCounter();

            case "options.service.store":
                    return $this->storeService();

            case "options.service.update":
                    return $this->updateService();
                    
            default:
                return [];
        }
    }

    public function storeCounter():array
    {
        $id = $this->route('id');
        return [
            "name" => "required|string|unique:counter_options,name,{$id}",
            "status" => "nullable|integer|in:1,0"
        ];
    }

    public function updateCounter():array
    {
        $id = $this->route('id');
        return [
            "name" => "nullable|string|unique:counter_options,name,{$id}",
            "status" => "nullable|integer|in:1,0"
        ];
    }

    public function storeService():array
    {
         $id = $this->route('id');
         return [
            "name" => "required|string|unique:service_options,name,{$id}",
            "letter" => "required|string|unique:service_options,letter,{$id}",
            "start_number" => "nullable|integer",
            "status" => "nullable|integer|in:1,0"
         ];
    }

    public function updateService():array
    {
         $id = $this->route('id');
         return [
            "name" => "nullable|string|unique:service_options,name,{$id}",
            "letter" => "nullable|string|unique:service_options,letter,{$id}",
            "start_number" => "nullable|integer",
            "status" => "nullable|integer|in:1,0"
         ];
    }
}
