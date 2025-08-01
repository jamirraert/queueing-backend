<?php

namespace App\Services\Queue;

use App\Models\Queue;
use Illuminate\Http\JsonResponse;

abstract class Gateway
{
    /**
     * @var App\Models\Queue
     */
    public Queue $queue;

    public function __construct()
    {
        $this->queue = new Queue();
    }

    abstract public function store(array $validated):array|JsonResponse;
    abstract public function call(int $id, int $userId):array|JsonResponse;
    abstract public function display(int $department_id, string $status):array|JsonResponse;
}
