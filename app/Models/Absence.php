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

        $absence->date = $data['date'];
        $absence->hour_start = $data['hour_start'];
        $absence->hour_end = $data['hour_end'];
        $absence->justify = $data['justify'] ?? null; 
        $absence->info_task = $data['info_task'];
        $absence->user_id = $data['user_id'];
        $absence->reason_id = $data['reason_id'];
        $absence->class_id = $data['class_id'];
        $absence->reason_description = $data['reason_description'];
        $absence->status = $data['status'];

        $absence->save();

        return $absence;
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
                'absences.created_at as created_at',
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

    public function checkAndMarkAsCompleted(): void {
        $sessionIds = $this->sessions->pluck('hour_start'); 
    
        $assignedHours = Guard::where('absence_id', $this->id)
            ->pluck('hour')
            ->unique();
    
        $allCovered = $sessionIds->diff($assignedHours)->isEmpty();
    
        if ($allCovered) {
            $this->status = 1; 
            $this->save();
        }
    }    

    public static function withSessionsForToday(): Collection {
        $absences = self::getAbsencesTodayWithDetails();
        $sessions = DB::table('sessions_evg')->get();

        foreach ($absences as $absence) {
            if (!$absence->hour_start || !$absence->hour_end) {
                $absence->sessions = $sessions->map(function ($session) { // Ausencia todo el día / todas las sesiones
                    return [
                        'id' => $session->id,
                        'hour_start' => $session->hour_start,
                        'hour_end' => $session->hour_end,
                    ];
                })->toArray();
                continue;
            }

            $absenceStart = Carbon::parse($absence->hour_start);
            $absenceEnd = Carbon::parse($absence->hour_end);

            $absence->sessions = $sessions->filter(function ($session) use ($absenceStart, $absenceEnd) {
                $start = Carbon::parse($session->hour_start);
                $end = Carbon::parse($session->hour_end);
                return $start->greaterThanOrEqualTo($absenceStart) && $end->lessThanOrEqualTo($absenceEnd);
            })->map(function ($session) {
                return [
                    'id' => $session->id,
                    'hour_start' => $session->hour_start,
                    'hour_end' => $session->hour_end,
                ];
            })->values()->toArray();
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

    public static function getAbsencesTodayWithDetailsById($id): Collection {
        $absence = new self();

        $absences = $absence::join('users', 'absences.user_id', '=', 'users.id')
            ->join('reasons', 'absences.reason_id', '=', 'reasons.id')
            ->leftJoin('guards', 'guards.absence_id', '=', 'absences.id') 
            ->whereDate('absences.date', now()->toDateString())
            ->where('absences.status', 0)
            ->where('absences.user_id', $id)
            ->groupBy(
                'absences.id', 'users.name', 'absences.hour_start', 'absences.hour_end',
                'absences.created_at', 'reasons.name', 'absences.reason_description',
                'absences.info_task', 'absences.justify'
            )
            ->select(
                'absences.id',
                'users.name as user_name',
                DB::raw("DATE_FORMAT(absences.hour_start, '%H:%i') as hour_start"),
                DB::raw("DATE_FORMAT(absences.hour_end, '%H:%i') as hour_end"),
                'absences.created_at as created_at',
                'reasons.name as reason_name',
                'absences.reason_description as reason_description',
                'absences.info_task as info',
                'absences.justify as justify',
                DB::raw('COUNT(guards.id) as guards_count') 
            )
            ->orderBy('absences.hour_start')
            ->orderBy('absences.updated_at')
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


}
