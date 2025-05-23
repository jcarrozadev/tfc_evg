<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $table = 'sessions_evg';
    protected $fillable = ['hour_start', 'hour_end'];

    public static function getAllSessions(): Collection {
        return self::select('id', 'hour_start', 'hour_end')
            ->orderBy('hour_start')
            ->get()
            ->map(function ($session) {
            $session->hour_start = date('H:i', strtotime($session->hour_start));
            $session->hour_end = date('H:i', strtotime($session->hour_end));
            return $session;
            });
    }

    public static function getSessionById($id): ?Session {
        return self::select('hour_start', 'hour_end')
            ->where('id', $id)
            ->first();
    }
    
}
