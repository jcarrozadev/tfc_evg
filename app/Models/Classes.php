<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model {

    protected $table = 'classes';
    public $timestamps = true;

    protected $fillable = [
        'num_class',
        'course',
        'code',
        'bookguard_id'
    ];

    public function getKeyName()
    {
        return ['num_class', 'course', 'code'];
    }

    public static function getAllClasses() {
        return self::select('id', 'num_class', 'course', 'code')
            ->get();
    }
}
