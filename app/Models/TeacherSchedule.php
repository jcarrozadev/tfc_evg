<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherSchedule extends Model
{
    protected $table = 'teacher_schedules';
    public $timestamps = false;

    public function session(): BelongsTo {
        return $this->belongsTo(Session::class, 'session_id');
    }

    public function class(): BelongsTo {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public static function getByUser(int $userId): array
    {
        return self::with(['session', 'class'])
            ->where('user_id', $userId)
            ->get()
            ->map(function ($item) {
                return [
                    'day'         => $item->day,
                    'session_id'  => $item->session_id,
                    'hour_start'  => $item->session->hour_start,
                    'hour_end'    => $item->session->hour_end,
                    'type'        => 'class',
                    'label'       => $item->class?->display_name,
                ];
            })
            ->toArray();
    }

    public static function getScheduleForUserOnDayAndSession(int $userId, string $day, int $sessionId): ?self {
        return self::where('user_id', $userId)
            ->where('day', $day)
            ->where('session_id', $sessionId)
            ->first();
    }

}

