<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bookguard extends Model
{
    protected $table = 'bookguards';

    public function users()
    {
        return $this->belongsToMany(User::class, 'bookguard_user');
    }
}

