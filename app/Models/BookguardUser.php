<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookguardUser extends Model
{
    protected $table = 'bookguard_user';
    protected $fillable = ['bookguard_id', 'user_id', 'class_id'];

    public static function getAllBookguardUsers(): array {
        return self::select( 'bookguard_id', 'user_id', 'class_id')
            ->orderBy('bookguard_id')
            ->get()
            ->toArray();
    }
}
