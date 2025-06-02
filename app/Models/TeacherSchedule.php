<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class TeacherSchedule
 * Represents the schedule of a teacher in the system.
 */
class TeacherSchedule extends Model
{
    protected $table = 'teacher_schedules';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function session(): BelongsTo {
        return $this->belongsTo(Session::class, 'session_id');
    }

    /**
     * Get the class associated with the teacher schedule.
     *
     * @return BelongsTo
     */
    public function class(): BelongsTo {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    /**
     * Get all teacher schedules for a specific user.
     *
     * @param int $userId
     * @return array
     */
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

    /**
     * Get the schedule for a specific user on a specific day and session.
     *
     * @param int $userId
     * @param string $day
     * @param int $sessionId
     * @return TeacherSchedule|null
     */
    public static function getScheduleForUserOnDayAndSession(int $userId, string $day, int $sessionId): ?self {
        return self::where('user_id', $userId)
            ->where('day', $day)
            ->where('session_id', $sessionId)
            ->first();
    }

}

