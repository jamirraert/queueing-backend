<?php

namespace App\Services\ServiceOptions;

use Illuminate\Http\JsonResponse;

class ServiceOptionsGateway extends Gateway
{
    /**
     * @return {Paginated Data}
     */
    public function index(): array|JsonResponse
    {
        $service = $this->serviceOptions
            ->where('status', 1)
            ->paginate(10);
        if(!$service) {
            return response()->json([
                'message' => 'No data found!'
            ], 404);
        }

        return response()->json([
            'message' => 'Successfully fetch data',
            'pagination' => $service
        ]);
    }

    /**
     * @param {Payload to store data}
     * @return {array | JsonResponse Data}
     */
    public function store(array $validated): array|JsonResponse
    {
        $service = $this->serviceOptions
            ->create($validated);

        if(!$service) {
            return response()->json([
                "message" => "Something went wrong"
            ], 500);
        }

        return response()->json([
            "message" => "Successfully created service!",
            "service" => $service
        ], 201);
    }

    /**
     * @param {Payload | ID to update data}
     * @return {array | JsonResponse Data}
     */
    public function update(int $id, array $validated): array|JsonResponse
    {
        $service = $this->serviceOptions
            ->where('id', $id)
            ->first();

        if(!$service) {
            return response()->json([
                'message' => 'Service not found'
            ], 404);
        }

        $updated = $service->update($validated);

        if($updated) {
            return response()->json([
                "message" => "Successfully updating data.",
                "data" => $service->fresh()
            ]);
        } else {
            return response()->json([
                "message" => "Something went wrong updating data."
            ], 500);
        }


        dd($service);
        return [];
    }

    /**
     * @param {ID to delete data}
     * @return {Payload of deleted data}
     */
    public function destroy(int $id): array|JsonResponse
    {
        $service = $this->serviceOptions
            ->where('id', $id)
            ->first();

        if(!$service) {
            return response()->json([
                'message' => 'Data not found'
            ], 404);
        }

        $deleted = $service->delete();

        if($deleted) {
            return response()->json([
                'message' => 'Service successfully deleted.',
                'data' => $service
            ]);
        } else {
            return response()->json([
                'message' => 'Something went wrong deleting the data'
            ]);
        }
    }

}
