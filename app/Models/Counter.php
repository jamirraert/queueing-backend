<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Counter extends Model
{
    protected $fillable = ['name', 'status'];

    public function tellers():HasMany
    {
        return $this->hasMany(teller::class);
    }
}
