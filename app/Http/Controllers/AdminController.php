<?php

namespace App\Http\Controllers;

use App\Mail\NotificationCustom;
use App\Models\Absence;
use App\Models\Bookguard;
use App\Models\BookguardUser;
use App\Models\Classes;
use App\Models\Guard;
use App\Models\Session;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
                    'image_profile' => $teacher->image_profile,
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
            $carbonDate = \Carbon\Carbon::parse((string)$absence->date);
            $dayOfWeek = $carbonDate->dayOfWeek;
            $dayLetter = match ($dayOfWeek) {
                1 => 'L',
                2 => 'M', 
                3 => 'X', 
                4 => 'J', 
                5 => 'V', 
                default => null,
            };
            $bookguard = Bookguard::findByDayAndSession($dayLetter, $session->id);

            if ($bookguard) {
                Log::info('Bookguard encontrado', [
                    'day' => $dayLetter,
                    'session_id' => $session->id,
                    'bookguard_id' => $bookguard->id,
                ]);
                BookguardUser::assignUserToClass(
                    $bookguard->id,
                    $teacherId,
                    $absence->class_id
                );
            } else {
                Log::warning('NO se encontró bookguard', [
                    'day' => $dayLetter,
                    'session_id' => $session->id,
                ]);
            }

            $saved[] = $assignment;
        }

        foreach ($removals as $removal) {
            $absenceId = $removal['absence_id'] ?? null;
            $sessionId = $removal['session_id'] ?? null;

            $session = DB::table('sessions_evg')->where('id', $sessionId)->first();

            if (!$absenceId || !$session) continue;

            $teacherId = $removal['teacher_id'] ?? Guard::getRemovalUserIdByAbsenceId(
                $absenceId,
                $session->hour_start
            );

            $deletedRows = Guard::deleteGuardByAbsenceId($absenceId, $session->hour_start);

            if ($deletedRows > 0) {
                $deleted[] = $removal;
            }

            if ($teacherId) {
                $absence = Absence::find($absenceId);
                if ($absence) {
                    $carbonDate = \Carbon\Carbon::parse((string) $absence->date);
                    $dayLetter = match ($carbonDate->dayOfWeek) {
                        1 => 'L', 2 => 'M', 3 => 'X', 4 => 'J', 5 => 'V', default => null,
                    };

                    $bookguard = Bookguard::findByDayAndSession($dayLetter, $session->id);
                    if ($bookguard) {
                        Log::info('Se encontró bookguard para eliminar class_id', [
                            'bookguard_id' => $bookguard->id,
                            'teacher_id' => $teacherId,
                        ]);

                        BookguardUser::deleteClassFromBookguard(
                            $bookguard->id,
                            $teacherId
                        );
                    } else {
                        Log::warning('NO se encontró bookguard en el removal', [
                            'day' => $dayLetter,
                            'session_id' => $session->id,
                        ]);
                    }
                }
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

    public function resetBookGuard():JsonResponse {
        BookguardUser::query()->delete();
        Bookguard::query()->delete();  
    
        return response()->json(['message' => 'Guardias restablecidas correctamente.']);
    
    }

    public function sendEmails(): JsonResponse {
        $assignments = request()->input('assignments', []);

        if (empty($assignments)) {
            return response()->json(['success' => false, 'message' => 'No hay asignaciones para enviar.']);
        }

        foreach ($assignments as $assignment) {
            $guardTeacher = User::getTeacherByIdForGuard($assignment['teacher_id']);

            $absenceTeacher = User::getTeacherByIdForGuard($assignment['absence_id']);
            
            $session = Session::getSessionById($assignment['session_id']);

            $hourStart = substr($session->hour_start, 0, 5);
            $hourEnd = substr($session->hour_end, 0, 5);
            
            $data = [
                'name' => $guardTeacher->name,
                'body' => 'Hola ' . $guardTeacher->name . ', estás cubriendo la guardia de ' . $absenceTeacher->name . ' el día ' . now()->translatedFormat('j \d\e F \d\e Y') . ' de ' . $hourStart . ' a ' . $hourEnd,
            ];

            Mail::to($guardTeacher->email)->queue(new NotificationCustom($data));
        }

        return response()->json(['success' => true, 'message' => 'Correos enviados a los profesores asignados.']);
    }

    public function sendWhatsapps(): JsonResponse {
        $assignments = request()->input('assignments', []);

        if (empty($assignments)) {
            return response()->json(['success' => false, 'message' => 'No hay asignaciones para enviar.']);
        }

        $sentCount = 0;
        $failedCount = 0;

        foreach ($assignments as $assignment) {
            $guardTeacher = User::getTeacherByIdForGuard($assignment['teacher_id']);
            $absenceTeacher = User::getTeacherByIdForGuard($assignment['absence_id']);
            $session = Session::getSessionById($assignment['session_id']);

            if (!$guardTeacher || !$absenceTeacher || !$session || !$guardTeacher->phone || !$guardTeacher->callmebot_apikey) {
                $failedCount++;
                continue;
            }

            $fecha = now()->translatedFormat('j \d\e F \d\e Y');

            $hourStart = substr($session->hour_start, 0, 5);
            $hourEnd = substr($session->hour_end, 0, 5);

            $mensaje = "👨‍🏫 Hola {$guardTeacher->name}, tienes una guardia que cubrir de {$absenceTeacher->name} el día {$fecha} de {$hourStart} a {$hourEnd}.";

            $success = $this->sendWhatsAppMessage($guardTeacher->phone, $mensaje, $guardTeacher->callmebot_apikey);

            $success ? $sentCount++ : $failedCount++;
        }

        return response()->json([
            'success' => $sentCount > 0,
            'message' => "WhatsApps enviados: $sentCount. Fallidos: $failedCount."
        ]);
    }


    private function sendWhatsAppMessage(string $phone, string $message, string $apikey): bool {
        $url = "https://api.callmebot.com/whatsapp.php";

        $params = [
            'phone' => '34' . ltrim($phone, '+'),
            'text' => $message,
            'apikey' => $apikey,
        ];

        $response = Http::get($url, $params);
        
        return !$response->failed();
    }



    public function resetBookGuardClasses(): JsonResponse {
        BookguardUser::query()->update(['class_id' => null]);
    
        return response()->json(['message' => 'Guardias restablecidas correctamente.']);
    
    }
}
