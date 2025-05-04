<?php

namespace App\Http\Controllers;

use App\Models\Bookguard;
use App\Models\Classes;
use App\Models\User;
use Illuminate\Contracts\View\View;

class BookGuardController extends Controller
{
    public function index(): View {
        return view('admin.bookGuard.bookGuard')
            ->with('teachers', User::getAllEnabledTeachers())
            ->with('classes', Classes::getAllClasses());
    }

    public function store() {
        $guardias = request()->input('guardias');

        Bookguard::storeFromWeeklyInput($guardias);

        return redirect()->back()->with('success', 'Guardias guardadas correctamente.');
    }
}
