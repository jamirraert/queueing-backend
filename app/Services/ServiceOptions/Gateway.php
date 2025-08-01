<?php

namespace App\Services\ServiceOptions;

use App\Models\ServiceOptions;
use Illuminate\Http\JsonResponse;

abstract class Gateway
{
    protected ServiceOptions $serviceOptions;

    public function __construct()
    {
        $this->serviceOptions = new ServiceOptions();
    }
    abstract public function store(array $validated):array|JsonResponse;
    abstract public function index():array|JsonResponse;
    abstract public function update(int $id, array $validated):array|JsonResponse;
    abstract public function destroy(int $id):array|JsonResponse;
}
