<?php

namespace App\Services;

use App\Models\Absence;
use App\Models\Classes;
use App\Models\Session;
use App\Models\User;
use Illuminate\Support\Facades\Http;

/**
 * Class NotificationService
 * Handles sending notifications via email and WhatsApp for guard assignments.
 */
class NotificationService
{
    /**
     * Send emails to teachers assigned to guard duties.
     *
     * @param array $assignments
     * @return array
     */
    public function sendEmails(array $assignments): array
    {
        if (empty($assignments)) {
            return [
                'success' => false,
                'message' => 'No hay asignaciones para enviar.',
            ];
        }

        $mailer = new GoogleMailService();

        foreach ($assignments as $assignment) {
            $guardTeacher = User::getTeacherByIdForGuard($assignment['teacher_id']);
            $dataAbsence = Absence::getUserAndClassByAbsenceId($assignment['absence_id']);
            $absenceTeacher = User::getTeacherByIdForGuard($dataAbsence['user_id']);
            $class = Classes::getClassById($dataAbsence['class_id']);
            $session = Session::getSessionById($assignment['session_id']);

            $hourStart = substr($session->hour_start, 0, 5);
            $hourEnd = substr($session->hour_end, 0, 5);
            $fecha = now()->translatedFormat('j \d\e F \d\e Y');

            $clase = "{$class['num_class']}{$class['course']}" .
                    ((isset($class['code']) && $class['code'] !== '-') ? " {$class['code']}" : '');

            $text = "👨‍🏫 Hola {$guardTeacher->name}, estás cubriendo la guardia de {$absenceTeacher->name} en {$clase} el día {$fecha} de {$hourStart} a {$hourEnd}.";

            $html = "<p>Hola <strong>{$guardTeacher->name}</strong>,</p>
                    <p>Estás cubriendo la guardia de <strong>{$absenceTeacher->name}</strong> en <strong>{$clase}</strong> el día <strong>{$fecha}</strong> de <strong>{$hourStart}</strong> a <strong>{$hourEnd}</strong>.</p>";

            $mailer->send(
                $guardTeacher->email,
                'Gestión de guardia',
                strip_tags($text),
                $html
            );
        }

        return [
            'success' => true,
            'message' => 'Correos enviados correctamente a los profesores asignados.',
        ];
    }

    /**
     * Send WhatsApp messages to teachers assigned to guard duties.
     *
     * @param array $assignments
     * @return array
     */
    public function sendWhatsapps(array $assignments): array
    {
        if (empty($assignments)) {
            return [
                'success' => false,
                'message' => 'No hay asignaciones para enviar.',
            ];
        }

        $sentCount = 0;
        $failedCount = 0;

        foreach ($assignments as $assignment) {
            $guardTeacher = User::getTeacherByIdForGuard($assignment['teacher_id']);
            $dataAbsence = Absence::getUserAndClassByAbsenceId($assignment['absence_id']);
            $absenceTeacher = User::getTeacherByIdForGuard($dataAbsence['user_id']);
            $class = Classes::getClassById($dataAbsence['class_id']);
            $session = Session::getSessionById($assignment['session_id']);

            if (
                !$guardTeacher || !$absenceTeacher || !$session ||
                !$guardTeacher->phone || !$guardTeacher->callmebot_apikey
            ) {
                $failedCount++;
                continue;
            }

            $hourStart = substr($session->hour_start, 0, 5);
            $hourEnd = substr($session->hour_end, 0, 5);

            $mensaje = '👨‍🏫 Hola ' . $guardTeacher->name . ', estás cubriendo la guardia de ' . $absenceTeacher->name . ' en '
                . $class['num_class']
                . $class['course']
                . (isset($class['code']) && $class['code'] !== '-' ? ' ' . $class['code'] : '')
                . ' el día '
                . now()->translatedFormat('j \d\e F \d\e Y')
                . ' de ' . $hourStart . ' a ' . $hourEnd . '.';

            $success = $this->sendWhatsAppMessage($guardTeacher->phone, $mensaje, $guardTeacher->callmebot_apikey);

            $success ? $sentCount++ : $failedCount++;
        }

        return [
            'success' => $sentCount > 0,
            'message' => "WhatsApps enviados: $sentCount. Fallidos: $failedCount.",
        ];
    }

    /**
     * Send a WhatsApp message using CallMeBot API.
     *
     * @param string $phone
     * @param string $message
     * @param string $apikey
     * @return bool
     */
    private function sendWhatsAppMessage(string $phone, string $message, string $apikey): bool
    {
        $url = "https://api.callmebot.com/whatsapp.php";

        $params = [
            'phone' => '34' . ltrim($phone, '+'),
            'text' => $message,
            'apikey' => $apikey,
        ];

        $response = Http::get($url, $params);
        return !$response->failed();
    }
}
