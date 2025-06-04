<?php

namespace App\Http\Controllers;

use App\Models\AbsenceFile;
use App\Models\User;
use App\Models\Absence;
use App\Models\Guard;

use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\TeacherValidatorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * TeacherController
 * Handles the management of teachers in the application.
 */
class TeacherController extends Controller
{
    /**
     * TeacherController constructor.
     * Initializes the controller.
     */
    public function __construct() {}

    /**
     * Display the teachers management page.
     *
     * @return View
     */
    public function index(): View
    {
        $teachers = (new \App\Services\TeacherService())->getAllTeachers();
        return view('admin.teacher', compact('teachers'));
    }

    /**
     * Display the form to create a new teacher.
     *
     * @return View
     */
    public function create(): RedirectResponse
    {
        $validatedData = TeacherValidatorController::validateTeacherData(request()->all());

        $success = (new \App\Services\TeacherService())->createTeacher($validatedData);

        return redirect()->back()->with($success ? 'success' : 'error', $success
            ? 'Profesor creado correctamente.'
            : 'Error al crear el profesor.');
    }

    /**
     * Display the form to edit a teacher.
     *
     * @param int $id
     * @return View
     */
    public function edit($id): JsonResponse
    {
        $request = request();
        $success = (new \App\Services\TeacherService())->updateTeacher($id, $request->all());

        return response()->json($success
            ? ['success' => 'Profesor editado correctamente.']
            : ['error' => 'Error al editar profesor.']);
    }

    /**
     * Delete a teacher by ID.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $success = (new \App\Services\TeacherService())->deleteTeacher($id);

        return response()->json($success
            ? ['success' => 'Profesor eliminado correctamente.']
            : ['error' => 'Error al eliminar profesor.']);
    }

    /**
     * Display the home page for the authenticated teacher.
     *
     * @return View
     */
    public function home(): View {
        $user = User::getHomeTeacherById(Auth::user()->id);
        $guard = Guard::hasGuardByUserId(Auth::user()->id);

        $view = view('user.home')->with('user', $user);

        if ($guard) {
            $view->with('guard', $guard);
        }

        return $view;
    }

    /**
     * Display the guards assigned for today.
     *
     * @return View
     */
    public function guardsToday(): View {
        $service = new \App\Services\ScheduleService();

        $user = $service->getUserData(Auth::user()->id);
        $guards = $service->getGuardsToday();

        return view('user.guardsToday')->with('user', $user)->with('guards', $guards);
    }

    /**
     * Display the personal guards for the authenticated user.
     *
     * @return View
     */
    public function personalGuard(): View {
        $guards = (new \App\Services\ScheduleService())->getPersonalGuards(Auth::user()->id);

        return view('user.personalGuard')->with('guards', $guards);
    }

    /**
     * Display the personal schedule for the authenticated user.
     *
     * @return View
     */
    public function personalSchedule(): View {
        $userId = Auth::user()->id;

        $data = (new \App\Services\ScheduleService())->getScheduleDataForUser($userId);

        return view('user.schedule', $data);
    }

    /**
     * Display the teacher settings.
     *
     * @return View
     */
    public function settings(): View {
        $user = User::getDataSettingTeacherById(Auth::user()->id);

        return view('user.setting')->with('user', $user);
    }

    /**
     * Update teacher settings.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSettings(): RedirectResponse {
        $request = request();

        $user = User::getTeacherById(Auth::user()->id);

        return User::editTeacher($user, $request->all())
            ? redirect()->route('teacher.home')->with('success', 'Datos actualizados correctamente.')
            : redirect()->route('teacher.home')->with('error', 'Error al actualizar los datos.');
    }

    /**
     * Display the form to update the password.
     *
     * @return View
     */
    public function updatePassword(): RedirectResponse {
        $request = request();
        $user = Auth::user();

        $success = (new \App\Services\PasswordService())->updatePassword($request, $user);

        return $success
            ? redirect()->route('teacher.home')->with('success', 'Contraseña actualizada correctamente.')
            : redirect()->route('teacher.home')->with('error', 'No puedes cambiar la contraseña de una cuenta de Google.');
    }

    /**
     * Display the form to upload an avatar.
     *
     * @return View
     */
    public function uploadAvatar(): RedirectResponse {
        $request = request();
        $user = Auth::user();

        (new \App\Services\ProfileService())->updateAvatar($request, $user);

        return redirect()->back()->with('success', 'Imagen de perfil actualizada.');
    }

    /**
     * Display the absence notification form.
     *
     * @return View
     */
    public function notifyAbsence(): View {
        $data = (new \App\Services\AbsenceService())->getReasonsAndSessions();
        return view('user.notifyAbsence', $data);
    }

    /**
     * Store the absence notification.
     *
     * @return RedirectResponse
     */
    public function storeNotifyAbsence(): RedirectResponse {
        $request = request();
        $service = new \App\Services\AbsenceService();

        $success = $service->notifyAbsence($request);

        return redirect()->route('teacher.home')->with($success
            ? 'success'
            : 'error',
            $success
                ? 'Ausencia(s) notificadas correctamente.'
                : 'Error al notificar alguna ausencia.');
    }

    /**
     * Consult absences for today.
     *
     * @return View
     */
    public function consultAbsence(): View {
        $user = \App\Services\ScheduleService::getUserData(Auth::user()->id);
        $absences = (new \App\Services\AbsenceService())->getAbsencesTodayWithDetails();

        return view('user.consultAbsence')->with(compact('user', 'absences'));
    }

    /**
     * Sort absences by date.
     *
     * @return JsonResponse
     */
    public function updateInfo(Request $request, Absence $absence): JsonResponse
    {
        $request->validate([
            'info' => 'nullable|string',
            'substitute_files.*' => 'nullable|file|max:5000', 
        ]);

        $absence->info_task = $request->input('info');
        $absence->save();

        if ($request->hasFile('substitute_files')) {
            foreach ($request->file('substitute_files') as $file) {
                $path = $file->store('substitute_files', 'public');

                AbsenceFile::storeFile([
                    'id' => $absence->id,
                    'file_path' => $path,
                    'file' => $file,
                ]);
            }
        }

        return response()->json([
            'info' => $absence->info_task,
            'files' => $absence->files->map(fn($file) => [
                'id' => $file->id,
                'name' => $file->original_name,
                'url' => Storage::url($file->file_path),
            ]),
        ]);
    }


    /**
     * Sort absences by date.
     *
     * @return JsonResponse
     */
    public function deleteFile(AbsenceFile $file): JsonResponse
    {
        $user = Auth::user();

        if ($file->absence->user_id !== $user->id) {
            abort(403);
        }

        if (Storage::disk('local')->exists($file->file_path)) {
            Storage::disk('local')->delete($file->file_path);
        }

        $file->delete();

        return response()->json(['message' => 'Archivo eliminado']);
    }

}
