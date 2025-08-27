<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QueueEntry extends Model
{
    protected $fillable = [
        'service_id',
        'type',
        'queue_number',
        'status',
        'teller_id'
    ];

    public function teller():BelongsTo
    {
        return $this->belongsTo(Teller::class);
    }
}
