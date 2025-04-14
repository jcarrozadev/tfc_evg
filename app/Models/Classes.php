<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model {

    protected $table = 'classes';

    protected $fillable = [
        'num_class',
        'course',
        'code',
        'bookguard_id'
    ];

    public static function getAllClasses() {
        return self::select('id', 'num_class', 'course', 'code')
            ->get();
    }
}
