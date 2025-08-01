<?php

namespace App\Services\CounterOptions;

use Illuminate\Http\JsonResponse;

class CounterOptions extends Gateway
{
    public function create($validated): array|JsonResponse
    {
        $counterOption = $this->counterOptions->create($validated);

        if(!$counterOption) {
            return response()->json([
                'message' => 'Something went wrong!',
            ]);
        }
        
        return response()->json([
            'message' => 'Successfully created.',
            'counter' => $counterOption
        ], 201);
    }

    public function index(): array|JsonResponse
    {
        $active = 1;
        $counter = $this->counterOptions
            ->where('status', $active)
            ->paginate(10);

        if(!$counter) {
            return response()->json([
                'message' => 'No data found'
            ], 404);
        }

        return response()->json([
            'message' => 'Data successfully fetch',
            'pagination' => $counter
        ]);
    }

    public function update($id, $validated): array|JsonResponse
    {
        $counterOption = $this->counterOptions::where('id', $id)
            ->first();

        if(!$counterOption) {
            return response()->json([
                'message' => 'Counter not found!'
            ]);
        }

        $isSuccess = $counterOption->update($validated);

        if($isSuccess) {
            return response()->json([
                'message' => 'Successfully updating data',
                'counter' => $counterOption
            ]);
        } else {
            return response()->json([
                'message' => 'Something went wrong!'
            ]);
        }

    }

    public function delete(int $id): array|JsonResponse
    {
        $counter = $this->counterOptions->find($id);

        if (!$counter) {
            return response()->json([
                'message' => 'Counter not found.',
            ], 404);
        }

        $deleted = $counter->delete();

        if (!$deleted) {
            return response()->json([
                'message' => 'Cannot delete data. Something went wrong.',
            ], 500);
        }

        return response()->json([
            'message' => 'Successfully deleted.',
            'data' => $counter,
        ]);
    }
}
