<?php

namespace App\Services\CounterOptions;

use App\Models\CounterOptions;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

abstract class Gateway
{
    public CounterOptions $counterOptions;

    public function __construct()
    {
        $this->counterOptions = new CounterOptions();
    }
    abstract public function create(array $validated):array|JsonResponse;
    abstract public function index():array|JsonResponse;
    abstract public function update(int $id, array $validated):array|JsonResponse;
    abstract public function delete(int $id):array|JsonResponse;
}
