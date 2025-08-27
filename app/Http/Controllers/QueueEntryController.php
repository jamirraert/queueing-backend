<?php

namespace App\Http\Controllers;

use App\Jobs\QueueEntryJob;
use App\Models\QueueEntry;
use App\Models\Service;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class QueueEntryController extends Controller
{
    /**
     * Display all queues
     */
    public function index()
    {
        try {

            $today = Carbon::today();
            $cachedKey = "queues:today:{$today}";

            $queues = Redis::get($cachedKey);
        
            if($queues) {
                $queues = json_decode($queues, true);
            } else {
                $queues = QueueEntry::whereDate('created_at', $today)->get()->toArray();
                $expiration = now()->endOfDay()->timestamp - now()->timestamp;
                Redis::setex($cachedKey, $expiration, json_encode($queues));
            }

            return $this->jsonResponse([
                'message' => 'Successfully fetching data',
                'data' => $queues
            ]);
        } catch (\Exception $e) {
            $this->setErrorMessage($e->getMessage());
            return $this->jsonResponse();
        }
    }

    /**
     * Store a queue requests
     */
    public function  store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|in:priority,regular',
            'service_id' => 'required|integer|exists:services,id'
        ]);

        try {        
            $service = Service::find($validated['service_id']);
            $letter = $service->letter;
            $initialNumber = $service->initial_number;
            $ttl = 20 * 60 * 60; //72000 20hrs

            $today = now()->toDateString();
            $counterKey = "queue_counter:{$validated['type']}:{$validated['service_id']}:{$today}";

            if(!Redis::exists($counterKey)) {
                $lastQueue = QueueEntry::whereDate('created_at', $today)
                    ->where('service_id', $validated['service_id'])
                    ->where('type', $validated['type'])
                    ->orderBy('id', 'desc')
                    ->first();

                    if($lastQueue) {
                        [, $lastNumber] = explode('-', $lastQueue->queue_number);
                        Redis::set($counterKey, $lastNumber);
                    } else {
                        Redis::set($counterKey, $initialNumber - 1);
                    }
            }

            $counter = Redis::incr($counterKey);

            if ($counter == 1) {
                $counter = $initialNumber;
                Redis::set($counterKey, $initialNumber);
            }

            Redis::expire($counterKey, $ttl);

            $queueNumber = $letter . '-' . $counter;

            $payload = [
                "service_id"   => $validated['service_id'],
                "type"         => $validated['type'],
                "queue_number" => $queueNumber
            ];

            QueueEntryJob::dispatch($payload);

            return $this->jsonResponse([
                'message'       => 'Queue processed.',
                'queue_number'  => $queueNumber
            ], 201);

        } catch (\Exception $e) {
            $this->setErrorMessage($e->getMessage());
            return $this->jsonResponse();
        }
    }
}
