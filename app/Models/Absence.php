<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Absence extends Model
{
    protected $table = 'absences';

    protected $fillable = [
        'date',
        'hour_start',
        'hour_end',
        'justify',
        'info_task',
        'user_id',
        'reason_id',
        'reason_description',
        'status',
    ];

    public static function createAbsence(array $data): Absence{
        $absence = new self();

        return $absence::create($data);
    }

    public static function getAbsencesTodayWithDetails(): Collection {
        $absence = new self();
    
        $absences = $absence::join('users', 'absences.user_id', '=', 'users.id')
            ->join('reasons', 'absences.reason_id', '=', 'reasons.id')
            ->whereDate('absences.date', now()->toDateString())
            ->where('absences.status', 0)
            ->select(
                'absences.id',
                'users.name as user_name',
                DB::raw("DATE_FORMAT(absences.hour_start, '%H:%i') as hour_start"),
                DB::raw("DATE_FORMAT(absences.hour_end, '%H:%i') as hour_end"),
                'reasons.name as reason_name'
            )
            ->get();
    
        $sessions = DB::table('sessions_evg')->get();
    
        foreach ($absences as $absence) {
            $absence->session_ids = $sessions->filter(function ($session) use ($absence) {
                return $session->hour_start < $absence->hour_end &&
                       $session->hour_end > $absence->hour_start;
            })->pluck('id')->toArray();
        }
    
        return $absences;
    }    

    public static function withSessionIdsForToday(): Collection {
        $absences = Absence::getAbsencesTodayWithDetails();
        $sessions = DB::table('sessions_evg')->get();

        foreach ($absences as $absence) {
            $absenceStart = Carbon::parse($absence->hour_start);
            $absenceEnd = Carbon::parse($absence->hour_end);

            $absence->session_ids = $sessions->filter(function ($session) use ($absenceStart, $absenceEnd) {
                $sessionStart = Carbon::parse($session->hour_start);
                $sessionEnd = Carbon::parse($session->hour_end);

                return $sessionStart->greaterThanOrEqualTo($absenceStart) &&
                    $sessionEnd->lessThanOrEqualTo($absenceEnd);
            })->pluck('id')->toArray();
        }

        return $absences;
    }

    public static function assignPossibleTeachers(Collection $absences, Collection $teachers): void {
        foreach ($absences as $absence) {
            $absence->possible_teachers = $teachers->filter(function ($teacher) use ($absence) {
                return !empty(array_intersect($teacher->session_ids, $absence->session_ids ?? []));
            })->values();
        }
    }
    

    public static function getAbsenceById($id): ?Absence {
        return self::where('id', $id)->first();
    }

}
