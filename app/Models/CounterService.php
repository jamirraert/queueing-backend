<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CounterService extends Model
{
    protected $fillable = [
        "user_id",
        "counter_id",
        "service_id"
    ];

    public function counter():BelongsTo
    {
        return $this->belongsTo(CounterOptions::class, 'counter_id');
    }

    public function service():BelongsTo
    {
        return $this->belongsTo(ServiceOptions::class, 'service_id');
    }
}
