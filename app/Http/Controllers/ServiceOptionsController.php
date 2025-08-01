<?php

namespace App\Http\Controllers;

use App\Http\Requests\OptionsRequest;
use App\Services\ServiceOptions\Gateway;

class ServiceOptionsController extends Controller
{
    /**
     * @var App\Servicec\ServiceOptions\Gateway
     */
    protected Gateway $serviceGateway;

    public function __construct(Gateway $gateway)
    {
        $this->serviceGateway = $gateway;
    }

    /**
     * @return {Services Paginated Data}
     * @method GET
     */
    public function index()
    {
        return $this->serviceGateway->index();
    }

    /**
     * @return {Response message with created data}
     * @method POST
     */
    public function store(OptionsRequest $request)
    {
        return $this->serviceGateway->store($request->validated());
    }

    /**
     * @return {Response message with updated data}
     * @var id
     * @method PATCH
     */
    public function update($id, OptionsRequest $request)
    {
        return $this->serviceGateway->update($id, $request->validated());
    }

    /**
     * @return Response with deleted data
     * @var id
     * @method DELETE
     */
    public function destroy($id)
    {
        return $this->serviceGateway->destroy($id);
    }
}
