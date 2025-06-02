<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Absence;
use App\Models\Guard;

use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\TeacherValidatorController;

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
        $user = User::getHomeTeacherById(auth()->user()->id);
        $guard = Guard::hasGuardByUserId(auth()->user()->id);

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

        $user = $service->getUserData(auth()->id());
        $guards = $service->getGuardsToday();

        return view('user.guardsToday')->with('user', $user)->with('guards', $guards);
    }

    /**
     * Display the personal guards for the authenticated user.
     *
     * @return View
     */
    public function personalGuard(): View {
        $guards = (new \App\Services\ScheduleService())->getPersonalGuards(auth()->id());

        return view('user.personalGuard')->with('guards', $guards);
    }

    /**
     * Display the personal schedule for the authenticated user.
     *
     * @return View
     */
    public function personalSchedule(): View {
        $userId = auth()->id();

        $data = (new \App\Services\ScheduleService())->getScheduleDataForUser($userId);

        return view('user.schedule', $data);
    }

    /**
     * Display the teacher settings.
     *
     * @return View
     */
    public function settings(): View {
        $user = User::getDataSettingTeacherById(auth()->user()->id);

        return view('user.setting')->with('user', $user);
    }

    /**
     * Update teacher settings.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSettings(): RedirectResponse {
        $request = request();

        $user = User::getTeacherById(auth()->user()->id);

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
        $user = auth()->user();

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
        $user = auth()->user();

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
        $user = \App\Services\ScheduleService::getUserData(auth()->id());
        $absences = (new \App\Services\AbsenceService())->getAbsencesTodayWithDetails();

        return view('user.consultAbsence')->with(compact('user', 'absences'));
    }

    /**
     * Sort absences by date.
     *
     * @return JsonResponse
     */
    public function updateInfo(Absence $absence): JsonResponse {
        $request = request();
        $request->validate(['info' => 'required|string|max:255']);

        (new \App\Services\AbsenceService())->updateTaskInfo($absence, $request->input('info'));

        return response()->json([
            'message' => 'Descripción actualizada',
            'info' => $absence->info_task,
        ]);
    }

}
