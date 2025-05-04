<?php

namespace App\Http\Controllers;

use App\Models\Bookguard;
use App\Models\BookguardUser;
use App\Models\Classes;
use App\Models\Session;
use App\Models\User;
use Illuminate\Contracts\View\View;

class BookGuardController extends Controller
{
    public function index(): View {
        return view('admin.bookGuard.bookGuard')
            ->with('teachers', User::getAllEnabledTeachers())
            ->with('classes', Classes::getAllClasses())
            ->with('bookguards', Bookguard::getAllBookguards())
            ->with('bookguardUsers', BookguardUser::getAllBookguardUsers())
            ->with('sessions', Session::select('id', 'hour_start', 'hour_end')->get());
    }

    public function store() {
        $guards = request()->input('guards');
        return Bookguard::storeFromWeeklyInput($guards) 
            ? redirect()->back()->with('success', 'Guardias guardadas correctamente.')
            : redirect()->back()->with('error', 'Error al guardar las guardias.');
    }
}
