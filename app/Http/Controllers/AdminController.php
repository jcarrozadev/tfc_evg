<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\Classes;
use App\Models\Guard;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller {
    public static function index(): View{
        
        $weeklyGuards = Guard::getWeeklySummary();
        $todayGuards = Guard::getTodaySummary();
        $classesCount = Classes::getClassesCount();
        $teachersCount = User::getTeachersCount();

        return view('admin.adminPanel')->with([
            'guardiasSemanales' => $weeklyGuards,
            'guardiasHoy' => $todayGuards,
            'clasesCount' => $classesCount,
            'profesoresCount' => $teachersCount,
        ]);

    }

    public static function guards(): View {
        $todayLetter = self::getNowLetter();
        if (is_null($todayLetter)) {
            return view('admin.guards.empty')->with('message', 'Hoy no hay guardias (fin de semana).');
        }

        $absences = Absence::withSessionIdsForToday();
        if ($absences->isEmpty()) {
            return view('admin.guards.empty')->with('message', 'Hoy no hay profesores ausentes.');
        }

        $allSessionIds = self::collectAllSessionIds($absences);

        $teachers = User::getAvailableTeachersForSessions($allSessionIds, $todayLetter);
        foreach ($teachers as $teacher) {
            $teacher->loadSessionIds();
        }

        Absence::assignPossibleTeachers($absences, $teachers);

        $sessionColors = self::getSessionColors();

        return view('admin.guards.config')->with([
            'absences' => $absences,
            'teachers' => $teachers,
            'sessionColors' => $sessionColors,
            'todayLetter' => $todayLetter,
        ]);
    }

    private static function getNowLetter(): ?string {
        return match (now()->addDay()->dayOfWeek) { // QUITAR ADD DAY 
            1 => 'L', 2 => 'M', 3 => 'X', 4 => 'J', 5 => 'V', default => null,
        };
    }

    private static function collectAllSessionIds(Collection $absences): array {
        return $absences->pluck('session_ids')
                        ->flatten() // pasar todo a un solo array
                        ->unique()
                        ->values()
                        ->all();
    }

    private static function getSessionColors(): array {
        return [
            1 => '#77d9ca',
            2 => '#2f4b7c',
            3 => '#665191',
            4 => '#a05195',
            5 => '#d45087', 
            6 => '#f95d6a', 
            7 => '#ff7c43', 
            8 => '#ffa600', 
        ];
    }
    


    public function assignGuard(): JsonResponse {

        $request = request();

        $assignments = $request->input('assignments', []);

        if (empty($assignments)) {
            return response()->json(['success' => false, 'message' => 'No se han recibido asignaciones.'], 400);
        }

        $saved = [];
        $skipped = [];

        foreach ($assignments as $assignment) {
            $absenceId = $assignment['absence_id'] ?? null;
            $teacherId = $assignment['teacher_id'] ?? null;

            if (!$absenceId || !$teacherId) {
                $skipped[] = $absenceId;
                continue;
            }

            $absence = Absence::find($absenceId);
            if (!$absence || $absence->status == 1) {
                $skipped[] = $absenceId;
                continue;
            }

            $guard = new Guard();
            $guard->date = now()->toDateString();
            $guard->text_guard = $absence->reason_description ?? '';
            $guard->hour = $absence->hour_start;
            $guard->user_sender_id = $teacherId;
            $guard->absence_id = $absenceId;
            $guard->save();

            $absence->status = 1;
            $absence->save();

            $saved[] = $absenceId;
        }

        return response()->json([
            'success' => true,
            'saved' => $saved,
            'skipped' => $skipped,
            'message' => count($saved) . ' guardias asignadas. ' . (count($skipped) > 0 ? count($skipped) . ' omitidas.' : '')
        ]);
    }
    
}
