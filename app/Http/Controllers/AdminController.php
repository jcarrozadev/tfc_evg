<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\Bookguard;
use App\Models\BookguardUser;
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
            return self::renderEmpty("Hoy no hay guardias (fin de semana).");
        }
    
        $absences = self::getAbsencesForToday();
    
        if ($absences->isEmpty()) {
            return self::renderEmpty("Hoy no hay profesores ausentes / ya asignados.");
        }
    
        $sessionIds = self::collectAllSessionIds($absences);
        $teachers = self::getAvailableTeachers($sessionIds, $todayLetter);
        $teacherCards = self::prepareTeacherCards($teachers, $sessionIds);
    
        Absence::assignPossibleTeachers($absences, $teachers);
    
        return view('admin.guards.config')->with([
            'absences' => $absences,
            'teachers' => $teacherCards,
            'sessionColors' => self::getSessionColors(),
            'todayLetter' => $todayLetter,
            'assignedGuards' => Guard::where('date', now()->toDateString())->get(),
        ]);
    }

    private static function renderEmpty(string $message): View {
        return view('admin.guards.empty')->with('message', $message);
    }
    
    private static function getAbsencesForToday(): Collection {
        return Absence::withSessionsForToday();
    }
    
    private static function getAvailableTeachers(array $sessionIds, string $todayLetter): Collection {
        $teachers = User::getAvailableTeachersForSessions($sessionIds, $todayLetter);
    
        foreach ($teachers as $teacher) {
            $teacher->loadSessionIds(); 
        }
    
        return $teachers;
    }
    
    private static function prepareTeacherCards($teachers, array $sessionIds): \Illuminate\Support\Collection {
        $cards = collect();
    
        foreach ($teachers as $teacher) {
            foreach ($teacher->session_ids as $sessionId) {
                if (!in_array($sessionId, $sessionIds)) continue;
    
                $cards->push((object)[
                    'id' => $teacher->id,
                    'name' => $teacher->name,
                    'session_id' => $sessionId,
                ]);
            }
        }
    
        return $cards->sortBy('session_id')->values();
    }

    private static function getNowLetter(): ?string {
        return match (now()->dayOfWeek) { 
            1 => 'L', 2 => 'M', 3 => 'X', 4 => 'J', 5 => 'V', default => null,
        };
    }     

    private static function collectAllSessionIds(Collection $absences): array {
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
    

    private static function getSessionColors(): array {
        return [
            1 => '#752948',
            2 => '#66d95b',
            3 => '#7340db',
            4 => '#a05195',
            5 => '#d42a71', 
            6 => '#f52a3b', 
            7 => '#f59064', 
            8 => '#00fffb', 
        ];
    }

    public function assignGuard(): JsonResponse {
        $assignments = request()->input('assignments', []);
        $removals = request()->input('removals', []);
    
        $saved = [];
        $skipped = [];
        $deleted = [];
    
        foreach ($assignments as $assignment) {
            $absenceId = $assignment['absence_id'] ?? null;
            $sessionId = $assignment['session_id'] ?? null;
            $teacherId = $assignment['teacher_id'] ?? null;
    
            if (!$absenceId || !$sessionId || !$teacherId) {
                $skipped[] = $assignment;
                continue;
            }
    
            $absence = Absence::find($absenceId);
            $session = DB::table('sessions_evg')->where('id', $sessionId)->first();
    
            if (!$absence || !$session) {
                $skipped[] = $assignment;
                continue;
            }
    
            $alreadyExists = Guard::where('absence_id', $absenceId)
                ->where('hour', $session->hour_start)
                ->exists();
    
            if ($alreadyExists) {
                $skipped[] = $assignment;
                continue;
            }
    
            Guard::assignToAbsence($absence, $session, $teacherId);
    
            $saved[] = $assignment;
        }
    
        foreach ($removals as $removal) {
            $absenceId = $removal['absence_id'] ?? null;
            $sessionId = $removal['session_id'] ?? null;
    
            $session = DB::table('sessions_evg')->where('id', $sessionId)->first();
    
            if (!$absenceId || !$session) continue;
    
            $deletedRows = Guard::where('absence_id', $absenceId)
                ->where('hour', $session->hour_start)
                ->delete();
    
            if ($deletedRows > 0) {
                $deleted[] = $removal;
            }
        }
    
        return response()->json([
            'success' => true,
            'saved' => $saved,
            'skipped' => $skipped,
            'deleted' => $deleted,
            'message' => count($saved) . ' asignadas, ' . count($deleted) . ' eliminadas, ' . count($skipped) . ' omitidas.'
        ]);
    }
    

    public function resetBookGuard() {
        BookguardUser::query()->delete();
        Bookguard::query()->delete();  
    
        return response()->json(['message' => 'Guardias restablecidas correctamente.']);
    
    }
}
