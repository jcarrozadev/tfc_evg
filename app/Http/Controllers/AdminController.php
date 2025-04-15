<?php

namespace App\Http\Controllers;

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

    public function create()
    {
        // Logic to create a new admin
    }

    public function edit($id)
    {
        // Logic to edit an existing admin
    }

    public function destroy($id)
    {
        // Logic to delete an admin
    }
}
