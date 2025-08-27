<?php

namespace App\Jobs;

use App\Models\QueueEntry;
use App\Models\Service;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class QueueEntryJob implements ShouldQueue
{
    use Queueable;

    protected array $payload;

    /**
     * Create a new job instance.
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('QueueEntryJob payload: ', $this->payload);
        QueueEntry::create($this->payload);
    }
}
