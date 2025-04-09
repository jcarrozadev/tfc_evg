<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model {

    protected $table = 'classes';
    protected $primaryKey = ['num_class', 'course', 'code'];
    public $timestamps = true;

    protected $fillable = [
        'num_class',
        'course',
        'code',
        'bookguard_id'
    ];

    public static function getAllClasses() {
        return self::select('num_class', 'course', 'code')
            ->get();
    }
}
