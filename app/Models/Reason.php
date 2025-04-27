<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Reason extends Model {
    protected $table = 'reasons';
    protected $fillable = ['name'];

    public static function getAllReasons(): Collection {

        $reasons = new self();

        return $reasons->select('id','name')
            ->orderBy('id')
            ->get();
    }
}
