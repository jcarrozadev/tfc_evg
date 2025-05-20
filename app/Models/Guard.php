<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Guard extends Model
{
    protected $table = 'guards';

    protected $fillable = [
        'date', 'text_guard', 'hour', 'user_sender_id', 'absence_id',
    ];

    public function absence(): BelongsTo{
        return $this->belongsTo(Absence::class);
    }

    public function teacher(): BelongsTo{
        return $this->belongsTo(User::class);
    }

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

    public static function getTodaySummary(): int {
        return self::whereDate('date', Carbon::today())->count();
    }

    public static function getGuardsToday(): array {
        return self::whereDate('guards.date', Carbon::today())
            ->join('absences', 'guards.absence_id', '=', 'absences.id')
            ->join('users as absent_teachers', 'absences.user_id', '=', 'absent_teachers.id')
            ->leftJoin('users as covering_teachers', 'guards.user_sender_id', '=', 'covering_teachers.id')
            ->select(
                'guards.*',
                'absent_teachers.name          as absent_teacher_name',
                'absent_teachers.image_profile as absent_teacher_image',
                'covering_teachers.name          as covering_teacher_name',
                'covering_teachers.image_profile as covering_teacher_image'
            )
            ->get()
            ->toArray();
    }
 

    public static function assignToAbsence($absence, $session, $teacherId): self {
        $guard = new self();
        $guard->date = now()->toDateString();
        $guard->text_guard = $absence->reason_description ?? '';
        $guard->hour = $session->hour_start;
        $guard->user_sender_id = $teacherId;
        $guard->absence_id = $absence->id;
        $guard->save();
    
        return $guard;
    }

    public static function getGuardsTodayById($userId): array {
        return self::where('user_sender_id', $userId)
            ->join('absences', 'guards.absence_id', '=', 'absences.id')
            ->join('users as absent_teachers', 'absences.user_id', '=', 'absent_teachers.id')
            ->leftJoin('users as covering_teachers', 'guards.user_sender_id', '=', 'covering_teachers.id')
            ->select(
                'guards.*',
                'absent_teachers.name          as absent_teacher_name',
                'absent_teachers.image_profile as absent_teacher_image',
                'covering_teachers.name          as covering_teacher_name',
                'covering_teachers.image_profile as covering_teacher_image'
            )
            ->get()
            ->toArray();
    }


    public static function getGuardByAbsenceId($absenceId): ?Guard {
        dd(self::where('absence_id', $absenceId)->first());
    }

    public static function hasGuardByUserId($userId): bool {
        return self::where('user_sender_id', $userId)->exists();
    }
}

