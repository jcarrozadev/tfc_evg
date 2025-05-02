<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\Classes;
use App\Models\Guard;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

    public static function guards(): View{
        $absences = Absence::getAbsencesTodayWithDetails();
        $teachers = User::getAllAvailableTeachers();

        return view('admin.guards.config')->with([
            'absences' => $absences,
            'teachers' => $teachers,
        ]);
    }


    public function assignGuard(): JsonResponse {

        $request = request();

        $absenceId = $request->input('absence_id');
        $teacherId = $request->input('teacher_id');
    
        $absence = Absence::getAbsenceById($absenceId);
    
        if ($absence) {
            $guard = new Guard();
            $guard->date = now()->toDateString();
            $guard->text_guard = $absence->reason_description;
            $guard->hour = $absence->hour_start;
            $guard->user_sender_id = $teacherId;
            $guard->absence_id = $absenceId;
            $guard->save();

            $absence->status = 1;
            $absence->save();
    
            return response()->json(['success' => true, 'message' => 'Guardia asignada con éxito']);
        }
    
        return response()->json(['success' => false, 'message' => 'Error al asignar la guardia'], 400);
    }
    
}
