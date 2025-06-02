<?php

namespace App\Services;

use App\Models\Absence;
use App\Models\Reason;
use App\Models\Session;
use App\Models\TeacherSchedule;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
/**
 * Class AbsenceService
 * Handles operations related to absences, such as fetching today's absences and collecting session IDs.
 */
class AbsenceService
{
    /**
     * Get all absences for today.
     *
     * @return Collection
     */
    public function getAbsencesForToday(): Collection
    {
        return Absence::withSessionsForToday();
    }

    /**
     * Collect all session IDs based on the provided absences.
     *
     * @param Collection $absences
     * @return array
     */
    public function collectAllSessionIds(Collection $absences): array
    {
        $hasFullDayAbsence = $absences->contains(function ($absence) {
            return is_null($absence->hour_start) || is_null($absence->hour_end);
        });

        if ($hasFullDayAbsence) {
            return DB::table('sessions_evg')->pluck('id')->unique()->toArray();
        }

        $hours = $absences->map(function ($absence) {
            return [
                'start' => $absence->hour_start,
                'end' => $absence->hour_end,
            ];
        });

        return DB::table('sessions_evg')
            ->where(function ($q) use ($hours) {
                foreach ($hours as $pair) {
                    $q->orWhere(function ($subQ) use ($pair) {
                        $subQ->where('hour_start', '>=', $pair['start'])
                            ->where('hour_end', '<=', $pair['end']);
                    });
                }
            })
            ->pluck('id')
            ->unique()
            ->toArray();
    }

    /**
     * Get reasons and sessions for absences.
     *
     * @return array
     */
    public function getReasonsAndSessions(): array
    {
        return [
            'reasons' => Reason::getAllReasons(),
            'sessions' => Session::getAllSessions(),
        ];
    }

    /**
     * Notify absence based on the request data.
     *
     * @param Request $request
     * @return Absence|bool
     */
    public function notifyAbsence(Request $request): Absence|bool
    {
        $carbonDate = Carbon::createFromFormat('d/m/Y', $request->input('date'));

        $baseAbsenceData = [
            'date' => $carbonDate->format('Y-m-d'),
            'reason_id' => $request->input('typeAbsence'),
            'reason_description' => $request->input('description'),
            'info_task' => 'No hay información de tarea asignada',
            'user_id' => Auth::id(),
            'status' => 0,
        ];

        $weekMap = [1 => 'L', 2 => 'M', 3 => 'X', 4 => 'J', 5 => 'V', 6 => 'S', 7 => 'D'];
        $day = $weekMap[$carbonDate->dayOfWeekIso];

        if ($request->filled('session_id')) {
            return $this->createSessionAbsence($request, $baseAbsenceData, $day);
        }

        return $this->createFullDayAbsences($request, $baseAbsenceData, $day);
    }

    /**
     * Create an absence for a specific session.
     *
     * @param Request $request
     * @param array $baseData
     * @param string $day
     * @return Absence
     */
    private function createSessionAbsence(Request $request, array $baseData, string $day): Absence
    {
        $session = Session::findOrFail($request->input('session_id'));

        $data = $baseData;
        $data['hour_start'] = $session->hour_start;
        $data['hour_end'] = $session->hour_end;

        $teacherSchedule = TeacherSchedule::getScheduleForUserOnDayAndSession(Auth::id(), $day, $session->id);
        $data['class_id'] = $teacherSchedule?->class_id;

        if ($request->hasFile('justify')) {
            $file = $request->file('justify');
            $data['justify'] = $file->storeAs('justificantes', $file->getClientOriginalName(), 'public');
        }

        \App\Models\User::disabledTeacher(Auth::id());

        return Absence::createAbsence($data);
    }

    /**
     * Create full-day absences for all sessions on a given day.
     *
     * @param Request $request
     * @param array $baseData
     * @param string $day
     * @return bool
     */
    private function createFullDayAbsences(Request $request, array $baseData, string $day): bool
    {
        $sessions = Session::orderBy('hour_start')->get();
        $success = true;

        foreach ($sessions as $session) {
            $data = $baseData;
            $data['hour_start'] = $session->hour_start;
            $data['hour_end'] = $session->hour_end;

            $teacherSchedule = TeacherSchedule::getScheduleForUserOnDayAndSession(Auth::id(), $day, $session->id);
            $data['class_id'] = $teacherSchedule?->class_id;

            if ($request->hasFile('justify')) {
                $file = $request->file('justify');
                $data['justify'] = $file->storeAs('justificantes', $file->getClientOriginalName(), 'public');
            }

            \App\Models\User::disabledTeacher(Auth::id());

            if (!Absence::createAbsence($data)) {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * Get absences for today with detailed information.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAbsencesTodayWithDetails(): Collection
    {
        return Absence::getAbsencesTodayWithDetailsById(Auth::id());
    }

    /**
     * Update the task information for a given absence.
     *
     * @param Absence $absence
     * @param string $info
     * @return void
     */
    public function updateTaskInfo(Absence $absence, string $info): void
    {
        $absence->update(['info_task' => $info]);
    }
}
