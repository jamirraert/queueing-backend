<?php

namespace App\Http\Controllers;

use App\Http\Requests\OptionsRequest;
use App\Services\CounterOptions\Gateway;

class CounterOptionsController extends Controller
{
    public Gateway $optionCounter;

    public function __construct(Gateway $gateway)
    {   
        $this->optionCounter = $gateway;
    }
    /**
     * @return {Paginated Data}
     */
    public function index()
    {
        return $this->optionCounter->index();
    }
    /**
     * @return {Created Data}
     */
    public function store(OptionsRequest $request)
    {
        return $this->optionCounter->create($request->validated());
    }
    /**
     * @return  {Updated Data}
     */
    public function update($id, OptionsRequest $request) 
    {
        return $this->optionCounter->update($id, $request->validated());
    }

    public function destroy($id) 
    {
        return $this->optionCounter->delete($id);
    }
}
