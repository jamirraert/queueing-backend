<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    /**
     * @return { index } active services
     */

    public function index()
    {
        try {

            $service = DB::transaction(fn () => Service::where('status', 1)->get());

            return $this->jsonResponse([
                'message' => 'Successfully fetched data',
                'data' => $service->toArray()
            ]);

        } catch (Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Something went wrong., ' . $e->getMessage()
            ]);
        }
    }

    /**
     * @return { store } posted data
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:services,name',
            'letter' => 'required|string|unique:services,letter',
            'initial_number' => 'required|integer|unique:services,initial_number',
            'status' => 'nullable|in:0,1'
        ]);

        try {

            $service = DB::transaction(fn () => Service::create($validated));

            return $this->jsonResponse([
                'message' => 'Successfully posted service',
                'data' => $service->toArray()
            ]);

        } catch (Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Something went wrong., ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @return { show } data
     */
    public function show($id) 
    {
        try {

            $service = DB::transaction(function() use ($id) {
                $service = Service::where('id', $id)
                    ->where('status', '1')->first();

                if(!$service) {
                    throw new \Exception('Service not found', 404);
                }

                return $service;
            });

            return $this->jsonResponse([
                'message' => 'Successfully fetch data',
                'data' => $service->toArray()
            ], 200);

        } catch (Exception $e) {
            $status = $e->getCode() === 404 ? 404 : 500;
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Something went wrong., ' . $e->getMessage()
            ], $status);
        }
    }

    /**
     * @return { update } updated data
     */
    public function update(Request $request, string $id)
    {
         $validated = $request->validate([
            'name' => 'nullable|string|unique:services,name,' . $id,
            'letter' => 'nullable|string|unique:services,letter, ' . $id,
            'initial_number' => 'nullable|integer|unique:services,initial_number, ' . $id,
            'status' => 'nullable|in:0,1'
        ]);

        try {

            $service = DB::transaction(function() use ($validated, $id) {
                $service = Service::where('id', $id)
                    ->where('status', '1')->first();

                if(!$service) {
                    throw new \Exception('Service not found.', 404);
                }

                $service->update($validated);
                $service->save();

                return $service;
            });

            return $this->jsonResponse([
                'message' => 'Successfully updated data',
                'data' => $service->toArray()
            ], 200);

        } catch (Exception $e) {
            $status = $e->getCode() === 404 ? 404 : 500;
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Something went wrong., ' . $e->getMessage()
            ], $status);
        }
    }

    /**
     * @return { destroy } deleted data
     */
    public function destroy($id)
    {
        try {

            $service = DB::transaction(function () use ($id) {
                $service = Service::find($id);

                if(!$service) {
                    throw new \Exception('Service not found', 404);
                }

                $service->delete();

                return $service;
            });

            return $this->jsonResponse([
                'message' => 'Successfully deleted data.',
                'data' => $service->toArray()
            ], 200);

        } catch (Exception $e) {
            $status = $e->getCode() === 404 ? 404 : 500;
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Something went wrong., ' . $e->getMessage()
            ], $status);
        }
    }
}
