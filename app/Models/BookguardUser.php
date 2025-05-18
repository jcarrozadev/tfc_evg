<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function bookguard(): BelongsTo {
        return $this->belongsTo(Bookguard::class, 'bookguard_id');
    }

    public static function getBookguardUserById($userId):array {
        return self::query()
            ->where('bookguard_user.user_id', $userId)
            ->join('bookguards', 'bookguard_user.bookguard_id', '=', 'bookguards.id')
            ->join('sessions_evg', 'bookguards.session_id', '=', 'sessions_evg.id')
            ->select([
                'bookguards.day',
                'bookguards.session_id',
                'sessions_evg.hour_start',
                'sessions_evg.hour_end',
            ])
            ->get()
            ->toArray();
    }
    

}
