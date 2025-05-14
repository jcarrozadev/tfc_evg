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
use App\Models\BookguardUser;
use App\Models\Guard;
use App\Models\Session;
use Illuminate\Support\Facades\Hash;
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
        $guard = Guard::hasGuardByUserId(auth()->user()->id);

        $view = view('user.home')->with('user', $user);

        if ($guard) {
            $view->with('guard', $guard);
        }

        return $view;
    }

    public function settings(): View {
        $user = User::getDataSettingTeacherById(auth()->user()->id);

        return view('user.setting')->with('user', $user);
    }

    public function guardsToday(): View {
        $user = User::getDataSettingTeacherById(auth()->user()->id);
        $guards = Guard::getGuardsToday();

        return view('user.guardsToday')->with('user', $user)->with('guards', $guards);
    }

    public function updateSettings(): RedirectResponse {
        $request = request();

        $user = User::getTeacherById(auth()->user()->id);

        return User::editTeacher($user, $request->all())
            ? redirect()->route('teacher.home')->with('success', 'Datos actualizados correctamente.')
            : redirect()->route('teacher.home')->with('error', 'Error al actualizar los datos.');
    }

    public function updatePassword(): RedirectResponse {
        $request = request();

        $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!is_null($user->google_id)) {
            return redirect()->route('teacher.home')->with('error', 'No puedes cambiar la contraseña de una cuenta de Google.');
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('teacher.home')->with('success', 'Contraseña actualizada correctamente.');
    }

    public function personalGuard(): View {
        $guards = Guard::getGuardsTodayById(auth()->user()->id);

        return view('user.personalGuard')->with('guards', $guards);
    }

    public function personalSchedule(): View {
        $user = User::getDataSettingTeacherById(auth()->user()->id);
        $sessions = Session::getAllSessions();
        $bookguard = BookguardUser::getBookguardUserById(auth()->user()->id);

        return view('user.schedule')->with('user', $user)->with('bookguard', $bookguard)->with('sessions', $sessions);
    }

    public function uploadAvatar(): RedirectResponse {
        $request = request();

        $user = auth()->user();

        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($user->image_profile && $user->image_profile !== 'default.png') {
            Storage::disk('public')->delete($user->image_profile);
        }

        $file = $request->file('avatar');

        $filename = 'avatar_' . $user->id . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();

        $safeFolder = Str::slug($user->name);
        $directory = 'avatars/' . $safeFolder;
        Storage::disk('public')->makeDirectory($directory);

        $file->storeAs($directory, $filename, 'public');

        $user->image_profile = $directory . '/' . $filename;
        $user->save();

        return redirect()->back()->with('success', 'Imagen de perfil actualizada.');
    }


    public function notifyAbsence(): View {
        $reasons = Reason::getAllReasons();
        $sessions = Session::getAllSessions();
        return view('user.notifyAbsence', compact('reasons', 'sessions'));
    }

    public function storeNotifyAbsence(): RedirectResponse {
        $request = request();
    
        $absenceData = [
            'date' => Carbon::createFromFormat('d/m/Y', $request->input('date'))->format('Y-m-d'),
            'reason_id' => $request->input('typeAbsence'),
            'reason_description' => $request->input('description'),
            'info_task' => $request->input('description'),
            'user_id' => auth()->user()->id,
            'status' => 0,
        ];
    
        if ($request->filled('session_id')) {
            $session = Session::findOrFail($request->input('session_id'));
            $absenceData['hour_start'] = $session->hour_start;
            $absenceData['hour_end'] = $session->hour_end;
        } else {
            $absenceData['hour_start'] = null; 
            $absenceData['hour_end'] = null;   
        }
    
        if ($request->hasFile('justify')) {
            $justify = $request->file('justify');
            $justifyPath = $justify->storeAs('justificantes', $justify->getClientOriginalName(), 'public');
            $absenceData['justify'] = $justifyPath;
        }
    
        User::disabledTeacher(auth()->user()->id);
    
        return Absence::createAbsence($absenceData)
            ? redirect()->route('teacher.home')->with('success', 'Ausencia notificada correctamente.')
            : redirect()->route('teacher.home')->with('error', 'Error al notificar ausencia.');
    }
    
    

    public function consultAbsence(): View {
        return view('user.consultAbsence');
    }
}
