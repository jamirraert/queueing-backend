<?php

namespace App\Services\Queue;

use App\Models\ServiceOptions;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QueueGateway extends Gateway
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function store($validated): array|JsonResponse
    {
        try {
            $queue = DB::transaction(function() use ($validated) {
                /**
                 * get the department
                 * through departmnet_id 
                 * from payload
                 */
                $queueType = $validated['type'];
                $departmentId = $validated['department_id'];

                $department = ServiceOptions::where('id', $departmentId)
                    ->lockForUpdate()
                    ->first();

                if(!$department) {
                    return response()->json([
                        'message' => 'Department not found!'
                    ], 404);
                }

                $departmentLetter = $department['letter'];
                $departmentStartNumber = $department['start_number'];

                /**
                 * Get the last queue entry
                 * 
                 * adding a condition if there 
                 * is a last entry
                 */
                 $lastEntry = $this->queue
                    ->where('type', $queueType)
                    ->where('department_id', $departmentId)
                    ->whereDate('created_at', Carbon::now())
                    ->orderByDesc('updated_at')
                    ->lockForUpdate()
                    ->first();

                $nextNumber = $departmentStartNumber;
                if($lastEntry) {
                    $queueNumber = explode("-",$lastEntry->queue_number)[1];
                    $lastNumber = (int) filter_var($queueNumber, FILTER_SANITIZE_NUMBER_INT);
                    $nextNumber = $lastNumber + 1;
                }

                $queueNumber = $departmentLetter . '-' .str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

                $payload = [
                    "type" => $queueType,
                    "queue_number" => $queueNumber,
                    "department_id" => $department->id
                ];

                $queueEntry = $this->queue->create($payload);

                return $queueEntry;

            });

            return response()->json([
                'message' => 'Successfully added new queue',
                'data' => $queue
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to generate queue: ' . $e->getMessage());
            return response()->json([
                "success" => false,
                "message" => "Failed to generate queue number"
            ], 500);
        }
        return [];
    }

    public function call($id, $userId): array|JsonResponse
    {
        try {
           $queue = DB::transaction(function() use ($id, $userId) {
                $queue = $this->queue
                    ->where('id', $id)
                    ->lockForUpdate()
                    ->first();

                if(!$queue) {
                    throw new \Exception("Queue ID $id not found.");
                }

                $queue->status = 'called';
                $queue->user_id = $userId;
                $queue->save();

                return $queue;
           });    
                  
             return response()->json([
                'message' => 'Queue successfully called.',
                'data' => $queue
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to call queue: ' . $e->getMessage());
            return response()->json([
                "success" => false,
                "message" => "Failed to call queue number"
            ], 500);
        }
        return [];
    }

    public function display($department_id, $status): array|JsonResponse
    {
        if(!in_array($status, ['waiting', 'called', 'skipped', 'served'])) {
            return response()->json([
                'message' => 'Invalid status provided'
            ], 400);
        }

        $queues = $this->queue
            ->where('department_id', $department_id)
            ->where('status', $status)
            ->whereDate('created_at', Carbon::now())
            ->orderBy('created_at', 'asc')
            ->get();

        if($queues->isEmpty()) {
            return response()->json([
                'message' => 'No queue found',
                'data' => $queues
            ], 404);
        }

        
        return response()->json([
            'message' => 'Queue data fetched successfully',
            'data' => $queues
        ]);
    }
}
