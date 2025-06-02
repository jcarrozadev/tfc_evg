<?php

namespace App\Http\Controllers;

use App\Helpers\SessionHelper;
use App\Mail\NotificationCustom;
use App\Models\Absence;
use App\Models\Bookguard;
use App\Models\BookguardUser;
use App\Models\Classes;
use App\Models\Guard;
use App\Models\Session;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

/**
 * AdminController
 * Controller for managing the admin panel functionalities.
 */
class AdminController extends Controller {

    /**
     * Display the admin panel with weekly and today's guards, classes count, and teachers count.
     *
     * @return View
     */
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

    /**
     * Display the guards configuration for today.
     *
     * @return View
     */
    public static function guards(): View {
        $todayLetter = SessionHelper::getNowLetter();

        if (is_null($todayLetter)) {
            return self::renderEmpty("Hoy no hay guardias (fin de semana).");
        }

        $absenceService = new \App\Services\AbsenceService();
        $absences = $absenceService->getAbsencesForToday();

        if ($absences->isEmpty()) {
            return self::renderEmpty("Hoy no hay profesores ausentes / ya asignados.");
        }

        $sessionIds = $absenceService->collectAllSessionIds($absences);

        $teacherService = new \App\Services\TeacherService();
        $teachers = $teacherService->getAvailableTeachers($sessionIds, $todayLetter);
        $teacherCards = $teacherService->prepareTeacherCards($teachers, $sessionIds);

        Absence::assignPossibleTeachers($absences, $teachers);

        return view('admin.guards.config')->with([
            'absences' => $absences,
            'teachers' => $teacherCards,
            'sessionColors' => SessionHelper::getSessionColors(),
            'todayLetter' => $todayLetter,
            'assignedGuards' => Guard::where('date', now()->toDateString())->get(),
        ]);
    }

    /**
     * Render an empty view with a message.
     *
     * @param string $message
     * @return View
     */
    private static function renderEmpty(string $message): View {
        return view('admin.guards.empty')->with('message', $message);
    }

    /**
     * Assign guards to absences based on the provided assignments and removals.
     *
     * @return JsonResponse
     */
    public function assignGuard(): JsonResponse {
        $assignments = request()->input('assignments', []);
        $removals = request()->input('removals', []);

        $service = new \App\Services\GuardAssignmentService();

        $result = $service->assignGuards($assignments);
        $deleted = $service->removeGuards($removals);

        return response()->json([
            'success' => true,
            'saved' => $result['saved'],
            'skipped' => $result['skipped'],
            'deleted' => $deleted,
            'message' => count($result['saved']) . ' asignadas, ' . count($deleted) . ' eliminadas, ' . count($result['skipped']) . ' omitidas.'
        ]);
    }

    /**
     * Reset all book guard assignments and delete the records.
     *
     * @return JsonResponse
     */
    public function resetBookGuard():JsonResponse {
        BookguardUser::query()->delete();
        Bookguard::query()->delete();  
    
        return response()->json(['message' => 'Guardias restablecidas correctamente.']);
    
    }

    /**
     * Reset all book guard classes by setting class_id to null.
     *
     * @return JsonResponse
     */
    public function resetBookGuardClasses(): JsonResponse {
        BookguardUser::query()->update(['class_id' => null]);
    
        return response()->json(['message' => 'Guardias restablecidas correctamente.']);
    
    }

    /**
     * Send emails to teachers assigned to guards.
     *
     * @return JsonResponse
     */
    public function sendEmails(): JsonResponse {
        $assignments = request()->input('assignments', []);
        $service = new \App\Services\NotificationService();

        $result = $service->sendEmails($assignments);

        return response()->json($result);
    }

    /**
     * Send WhatsApp messages to teachers assigned to guards.
     *
     * @return JsonResponse
     */
    public function sendWhatsapps(): JsonResponse {
        $assignments = request()->input('assignments', []);
        $service = new \App\Services\NotificationService();

        $result = $service->sendWhatsapps($assignments);

        return response()->json($result);
    }
}
