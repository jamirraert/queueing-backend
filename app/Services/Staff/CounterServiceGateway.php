<?php

namespace App\Services\Staff;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class CounterServiceGateway extends Gateway
{
    /**
     * @return The data,counter and services
     * of the staff
     */
    public function find(int $user_id): array|JsonResponse
    {
        $staff = $this->staff->where('user_id', $user_id)
            ->whereDate('created_at', Carbon::now())
            ->first();
        
        if(!$staff) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'message' => 'Successfully fetch data',
            'data' => $staff->load('counter','service')
        ]);
    }
    /**
     * Store or Post data that 
     * is from the user
     * @return array|JsonResponse
     */
    public function store(array $validated): array|JsonResponse
    {
        $staff = $this->staff->create($validated);

        if(!$staff) {
            return  response()->json([
                'message' => "Something went wrong posting data"
            ], 201);
        }

        return response()->json([
            'message' => 'Successfully posting data.',
            'data' => $staff
        ]);
    }
}
