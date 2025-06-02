<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Classes
 * Represents a class record in the system.
 */
class Session extends Model
{
    protected $table = 'sessions_evg';
    protected $fillable = ['hour_start', 'hour_end'];

    /**
     * Create a new session record.
     *
     * @param array $data
     * @return Session
     */
    public static function getAllSessions(): Collection {
        return self::select('id', 'hour_start', 'hour_end')
            ->orderBy('hour_start')
            ->get()
            ->map(function (Session $session): Session {
            $session->hour_start = date('H:i', strtotime($session->hour_start));
            $session->hour_end = date('H:i', strtotime($session->hour_end));
            return $session;
            });
    }

    /**
     * Get all sessions.
     *
     * @return Collection
     */
    public static function getSessionById($id): ?Session {
        return self::select('hour_start', 'hour_end')
            ->where('id', $id)
            ->first();
    }
    
}
