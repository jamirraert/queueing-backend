<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CounterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $counter = DB::transaction(function () {
                $counter = Counter::where('status', 1)->get();

                return $counter;
            });

            return $this->jsonResponse([
                'message' => 'Successfully fetched counters',
                'data' => $counter->toArray()
            ], 200);

        } catch (Exception $e) {
            $status = $e->getCode() === 404 ? 404 : 500;
            $this->jsonResponse([
                'success' => false,
                'message' => 'Something went wrong., ' . $e->getMessage()
            ], $status);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:counters,name'
        ]);

        try {

            $counter = DB::transaction(fn () => Counter::create([
                'name' => $validated['name']
            ]));

            return $this->jsonResponse([
                'message' => 'Successfully created counter.',
                'data' => $counter->toArray()
            ]);

        } catch (Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Something went wrong., ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {

            $counter = DB::transaction(function() use ($id) {
                $counter = Counter::find($id);

                if(!$counter || !$id) {
                    throw new \Exception('Counter not found. ', 404);
                }

                return $counter;
            });

            return $this->jsonResponse([
                'message' => 'Success fully fetch ' . $counter->name,
                'data' => $counter->toArray()
            ], 200);

        } catch (Exception $e) {
            $status = $e->getCode() === 404 ? 404 : 500;
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Something went wrong., '. $e->getMessage()
            ], $status);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string'
        ]);

        try {

            $counter = DB::transaction(function () use ($validated, $id) {
                $counter = Counter::find($id);

                if(!$counter) {
                    throw new \Exception('Counter not found.', 404);
                }

                $counter->update($validated);
                $counter->save();

                return $counter;
            });

            return $this->jsonResponse([
                'message' => 'Counter successfully updated.',
                'data' => $counter->toArray()
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {

            $counter = DB::transaction(function() use ($id) {
                $counter = Counter::find($id);

                if(!$counter) {
                    throw new \Exception('Counter not found', 404);
                }

                $counter->delete();

                return $counter;
            });

            return $this->jsonResponse([
                'message' => 'Successfully deleted: ' . $counter->name,
                'data' => $counter->toArray()
            ], 200);

        } catch (Exception $e) {
            $status = $e->getCode() === 404 ? 404 : 500;
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Something went wrong., '. $e->getMessage()
            ], $status);
        }
    }
}
