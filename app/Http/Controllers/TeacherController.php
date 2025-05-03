<?php

namespace App\Http\Controllers;

use App\Models\Reason;
use App\Models\User;
use App\Models\Absence;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;
use App\Http\Controllers\TeacherValidatorController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

    public function edit($id): JsonResponse {
        $request = request();

        $teacher = User::getTeacherById($id);

        return !$teacher
            ? response()->json(['error' => 'Profesor no encontrado.'])
            : (User::editTeacher($teacher, $request->all())
                ? response()->json(['success' => 'Profesor editado correctamente.'])
                : response()->json(['error' => 'Error al editar profesor.']));
    }

    public function destroy($id): JsonResponse {
        if (User::deleteTeacher($id)) {
            return response()->json(['success' => 'Profesor eliminado correctamente.']);
        } else {
            return response()->json(['error' => 'Error al eliminar profesor.']);
        }
    }

    public function home(): View {
        $user = User::getHomeTeacherById(auth()->user()->id);
        return view('user.home')->with('user', $user);
    }

    public function settings(): View {
        $user = User::getDataSettingTeacherById(auth()->user()->id);

        return view('user.setting')->with('user', $user);
    }

    public function updateSettings(): RedirectResponse {
        $request = request();

        $user = User::getTeacherById(auth()->user()->id);

        return User::editTeacher($user, $request->all())
            ? redirect()->route('teacher.home')->with('success', 'Datos actualizados correctamente.')
            : redirect()->route('teacher.home')->with('error', 'Error al actualizar los datos.');
    }

    public function uploadAvatar(): RedirectResponse {

        $request = request();

        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = auth()->user();

        if ($user->image_profile && $user->image_profile !== 'default.png') {
            Storage::disk('public')->delete($user->image_profile);
        }

        $file = $request->file('avatar');
        $filename = 'avatar_' . $user->id . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
        $path = 'avatars/' . $user->name . '/' . $filename;

        $file->storeAs('avatars/' . $user->name, $filename, 'public');

        $user->image_profile = $path;
        $user->save();

        return redirect()->back()->with('success', 'Imagen de perfil actualizada.');
    }



    public function notifyAbsence(): View {
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

    public function consultAbsence(): View {
        return view('user.consultAbsence');
    }
}
