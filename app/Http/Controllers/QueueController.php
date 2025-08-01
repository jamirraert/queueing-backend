<?php

namespace App\Http\Controllers;

use App\Http\Requests\QueueRequest;
use App\Services\Queue\Gateway;

class QueueController extends Controller
{
    /**
     * @var App\Services\Queue\Gateway
     */
    protected Gateway $queue;

    public function __construct(Gateway $queue)
    {
        $this->queue = $queue;
    }

    public function store(QueueRequest $request)
    {
        return $this->queue->store($request->validated());
    }

    public function call($id, $user_id)
    {
        return $this->queue->call($id, $user_id);
    }

    public function display($department_id, $status)
    {
        return $this->queue->display($department_id, $status);
    }
}
