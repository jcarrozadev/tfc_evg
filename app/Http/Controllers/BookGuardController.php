<?php

namespace App\Http\Controllers;

use App\Models\Bookguard;
use App\Models\BookguardUser;
use App\Models\Classes;
use App\Models\Session;
use App\Models\User;

use Illuminate\Contracts\View\View;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BookGuardController
 * Handles the book guard functionalities such as displaying guards, storing them, and downloading the PDF.
 */
class BookGuardController extends Controller
{
    /**
     * Display the book guard page with teachers, classes, bookguards, and bookguard users.
     *
     * @return View
     */
    public function index(): View {
        return view('admin.bookGuard.bookGuard')
            ->with('teachers', User::getAllEnabledTeachers())
            ->with('classes', Classes::getAllClasses())
            ->with('bookguards', Bookguard::getAllBookguards())
            ->with('bookguardUsers', BookguardUser::getAllBookguardUsers())
            ->with('sessions', Session::select('id', 'hour_start', 'hour_end')->get());
    }

    /**
     * Store the guards from the weekly input.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(): RedirectResponse {
        $guards = request()->input('guards');
        return Bookguard::storeFromWeeklyInput($guards) 
            ? redirect()->back()->with('success', 'Guardias guardadas correctamente.')
            : redirect()->back()->with('error', 'Error al guardar las guardias.');
    }

    /**
     * Download the PDF of the book guard.
     *
     * @return \Symfony\Component\HttpFoundation\Response;
     */
    public function downloadPdf(): Response {
        $bookguards = Bookguard::with('users', 'session')->get();
        $bookguardUsers = BookguardUser::all();
        $teachers = User::where('role_id', 2)->get();
        $classes = Classes::all();
        $sessions = Session::all();

        $slots = [
            '8:15-9:10',
            '9:10-10:05',
            '10:05-11:00',
            '11:30-12:25',
            '12:25-13:20',
            '13:20-14:15',
        ];

        $days = ['L', 'M', 'X', 'J', 'V'];
        $daysTitle = ['LUNES', 'MARTES', 'MIERCOLES', 'JUEVES', 'VIERNES'];

        $pdf = Pdf::loadView('admin.bookGuard.pdf', compact(
            'bookguards', 'bookguardUsers', 'teachers', 'classes', 'sessions', 'slots', 'days', 'daysTitle'
        ))->setPaper('a4', 'landscape');


        return $pdf->download('LibroGuardia - ' . date('Y') . '.pdf');
    }
}
