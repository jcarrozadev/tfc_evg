<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $table = 'sessions_evg';
    protected $fillable = ['hour_start', 'hour_end'];
}
