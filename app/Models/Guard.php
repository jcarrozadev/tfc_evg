<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Class Guard
 * Represents a guard record in the system.
 */
class Guard extends Model
{
    protected $table = 'guards';

    protected $fillable = [
        'date', 'text_guard', 'hour', 'user_sender_id', 'absence_id',
    ];

    /**
     * Get the absence associated with the guard.
     *
     * @return BelongsTo
     */
    public function absence(): BelongsTo{
        return $this->belongsTo(Absence::class);
    }

    /**
     * Get the class associated with the guard.
     *
     * @return BelongsTo
     */
    public function teacher(): BelongsTo{
        return $this->belongsTo(User::class);
    }

    /**
     * Get the weekly summary of guards.
     *
     * @return array
     */
    public static function getWeeklySummary(): array {
        $startOfWeek = Carbon::now()->startOfWeek(); 
        $endOfWeek = Carbon::now()->startOfWeek()->addDays(4); 

        $guards = self::selectRaw('DAYNAME(date) as dia, COUNT(*) as total')
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->groupBy('dia')
            ->pluck('total', 'dia');

        $dayOrders = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

        $resum = [];
        foreach ($dayOrders as $day) {
            $resum[] = $guards[$day] ?? 0;
        }

        return $resum;
    }

    /**
     * Get the total number of guards for today.
     *
     * @return int
     */
    public static function getTodaySummary(): int {
        return self::whereDate('date', Carbon::today())->count();
    }

    /**
     * Get all guards for today.
     *
     * @return array
     */
    public static function getGuardsToday(): array {
        return self::whereDate('guards.date', Carbon::today())
            ->join('absences', 'guards.absence_id', '=', 'absences.id')
            ->join('users as absent_teachers', 'absences.user_id', '=', 'absent_teachers.id')
            ->leftJoin('users as covering_teachers', 'guards.user_sender_id', '=', 'covering_teachers.id')
            ->leftJoin('classes', 'guards.class_id', '=', 'classes.id')
            ->select(
                'guards.*',
                'absent_teachers.name          as absent_teacher_name',
                'absent_teachers.image_profile as absent_teacher_image',
                'covering_teachers.name          as covering_teacher_name',
                'covering_teachers.image_profile as covering_teacher_image',
                DB::raw("CONCAT(classes.num_class, ' ', classes.course, ' ', classes.code) as class_name")
            )
            ->get()
            ->toArray();
    }

    /**
     * Assign a guard to an absence.
     *
     * @param Absence $absence
     * @param Session $session
     * @param int $teacherId
     * @return self
     */
    public static function assignToAbsence($absence, $session, $teacherId): self {
        $guard = new self();
        $guard->date = now()->toDateString();
        $guard->text_guard = $absence->reason_description ?? '';
        $guard->hour = $session->hour_start;
        $guard->user_sender_id = $teacherId;
        $guard->absence_id = $absence->id;
        $guard->class_id = $absence->class_id ?? null;
        $guard->save();
    
        return $guard;
    }

    /**
     * Get all guards for a specific user today.
     *
     * @param int $userId
     * @return array
     */
    public static function getGuardsTodayById($userId): array {
        return self::where('guards.user_sender_id', $userId)
            ->join('absences', 'guards.absence_id', '=', 'absences.id')
            ->join('users as absent_teachers', 'absences.user_id', '=', 'absent_teachers.id')
            ->leftJoin('users as covering_teachers', 'guards.user_sender_id', '=', 'covering_teachers.id')
            ->leftJoin('classes', 'guards.class_id', '=', 'classes.id')
            ->select(
                'guards.*',
                'absent_teachers.name as absent_teacher_name',
                'absent_teachers.image_profile as absent_teacher_image',
                'covering_teachers.name as covering_teacher_name',
                'covering_teachers.image_profile as covering_teacher_image',
                'absences.info_task as info',
                DB::raw("CONCAT(classes.num_class, ' ', classes.course, ' ', classes.code) as class_name")
            )
            ->get()
            ->toArray();
    }

    /**
     * Get all guards for a specific user.
     *
     * @param int $userId
     * @return array
     */
    public static function getGuardByAbsenceId($absenceId): ?Guard {
        return self::where('absence_id', $absenceId)->first();
    }

    /**
     * Check if a guard exists for a specific user ID.
     *
     * @param int $userId
     * @return bool
     */
    public static function hasGuardByUserId($userId): bool {
        return self::where('user_sender_id', $userId)->exists();
    }

    /**
     * Delete a guard by absence ID and hour.
     *
     * @param int $absenceId
     * @param string $hour_start
     * @return bool|null
     */
    public static function deleteGuardByAbsenceId($absenceId, $hour_start):?bool {
        return self::where('absence_id', $absenceId)
                ->where('hour', $hour_start)
                ->delete();
    }

    /**
     * Get the user ID of the person who removed a guard by absence ID and hour.
     *
     * @param int $absenceId
     * @param string $hour
     * @return int|null
     */
    public static function getRemovalUserIdByAbsenceId(int $absenceId, string $hour): ?int {
        return self::where('absence_id', $absenceId)
            ->where('hour', $hour)
            ->value('user_sender_id');
    }

}

