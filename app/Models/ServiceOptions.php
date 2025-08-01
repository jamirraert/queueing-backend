<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceOptions extends Model
{
    protected $fillable = [
        "name", 
        "letter", 
        "start_number",
        "status"
    ];
}
