<?php

namespace App\Http\Controllers;

use App\Models\Reason;
use App\Models\User;
use App\Models\Absence;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\TeacherValidatorController;

class TeacherController extends Controller
{
    public function __construct() {}

    public function index(): View
    {
        $teachers = User::getAllEnabledTeachers();

        foreach ($teachers as $teacher) {
            $teacher->available = $teacher->available === 1 ? 'Sí' : 'No';
        }

        return view('admin.teacher', compact('teachers'));
    }

    public function create(): RedirectResponse
    {
        $validatedData = TeacherValidatorController::validateTeacherData(request()->all());

        return User::addTeacher($validatedData)
            ? redirect()->back()->with('success', 'Profesor creado correctamente.')
            : redirect()->back()->with('error', 'Error al crear el profesor.');
    }

    public function edit(Request $request, $id): JsonResponse
    {
        $teacher = User::getTeacherById($id);

        return !$teacher
            ? response()->json(['error' => 'Profesor no encontrado.'])
            : (User::editTeacher($teacher, $request->all())
                ? response()->json(['success' => 'Profesor editado correctamente.'])
                : response()->json(['error' => 'Error al editar profesor.']));
    }

    public function destroy($id): JsonResponse
    {
        if (User::deleteTeacher($id)) {
            return response()->json(['success' => 'Profesor eliminado correctamente.']);
        } else {
            return response()->json(['error' => 'Error al eliminar profesor.']);
        }
    }

    public function home(): View
    {
        return view('user.home');
    }

    public function settings(): View
    {
        return view('user.setting');
    }

    public function notifyAbsence(): View
    {
        $reasons = Reason::getAllReasons();
        return view('user.notifyAbsence', compact('reasons'));
    }

    public function storeNotifyAbsence(): RedirectResponse {
        $request = request();

        $absenceData = [
            'date' => Carbon::createFromFormat('d/m/Y', $request->input('date'))->format('Y-m-d'),
            'hour_start' => $request->input('start_hour') . ':' . $request->input('start_minute') . ':00',
            'hour_end' => $request->input('end_hour') . ':' . $request->input('end_minute') . ':00',
            'reason_id' => $request->input('typeAbsence'),
            'reason_description' => $request->input('description'),
            'info_task' => $request->input('description'),
            'user_id' => auth()->user()->id,
            'status' => 0,
        ];

        if ($request->hasFile('justify')) {
            $justify = $request->file('justify');
            $justifyPath = $justify->storeAs('justificantes', $justify->getClientOriginalName(), 'public');
            $absenceData['justify'] = $justifyPath;
        }

        User::disbledTeacher(auth()->user()->id);

        return Absence::createAbsence($absenceData)
            ? redirect()->route('teacher.home')->with('success', 'Ausencia notificada correctamente.')
            : redirect()->route('teacher.home')->with('error', 'Error al notificar ausencia.');
    }

    public function consultAbsence(): View
    {
        return view('user.consultAbsence');
    }
}
