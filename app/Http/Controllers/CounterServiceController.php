<?php

namespace App\Http\Controllers;

use App\Http\Requests\CounterServiceRequest as Request;
use App\Services\Staff\Gateway;

class CounterServiceController extends Controller
{
    /**
     * @var App\Models\CounterService
     */
    public Gateway $staff;

    public function __construct(Gateway $staff)
    {
        $this->staff = $staff;
    }

    /**
     * @return user | counter | service Data
     * @method {GET}
     */
    public function find($user_id)
    {
        return $this->staff->find($user_id);
    }

    /**
     * @return Response message with data
     * @method POST
     */
    public function store(Request $request) 
    {
        return $this->staff->store($request->validated());
    }
}
