<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\Classes;
use App\Models\Guard;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller {
    public static function index()
    {
        
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

    public static function guards()
    {
        $absences = Absence::getAbsencesTodayWithDetails();
        $teachers = User::getAllEnabledTeachers();

        return view('admin.guards.config')->with([
            'absences' => $absences,
            'teachers' => $teachers,
        ]);
    }
}
