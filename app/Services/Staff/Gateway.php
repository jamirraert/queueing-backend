<?php

namespace App\Services\Staff;

use App\Models\CounterService;
use Illuminate\Http\JsonResponse;

abstract class Gateway
{
    /**
     * @var App\Models\CounterService
     */
    protected CounterService $staff;

    public function __construct()
    {
        $this->staff = new CounterService();
    }

    abstract public function find(int $user_id):array|JsonResponse;
    abstract public function store(array $validated):array|JsonResponse;
}
