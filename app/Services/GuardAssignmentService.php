<?php

namespace App\Services;

use App\Models\Absence;
use App\Models\Bookguard;
use App\Models\BookguardUser;
use App\Models\Guard;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * Class GuardAssignmentService
 * Handles the assignment and removal of guards for absences.
 */
class GuardAssignmentService
{
    /**
     * Assign guards to absences based on the provided assignments.
     *
     * @param array $assignments
     * @return array
     */
    public function assignGuards(array $assignments): array
    {
        $saved = [];
        $skipped = [];

        foreach ($assignments as $assignment) {
            $absenceId = $assignment['absence_id'] ?? null;
            $sessionId = $assignment['session_id'] ?? null;
            $teacherId = $assignment['teacher_id'] ?? null;

            if (!$absenceId || !$sessionId || !$teacherId) {
                $skipped[] = $assignment;
                continue;
            }

            $absence = Absence::find($absenceId);
            $session = DB::table('sessions_evg')->where('id', $sessionId)->first();

            if (!$absence || !$session) {
                $skipped[] = $assignment;
                continue;
            }

            $alreadyExists = Guard::where('absence_id', $absenceId)
                ->where('hour', $session->hour_start)
                ->exists();

            if ($alreadyExists) {
                $skipped[] = $assignment;
                continue;
            }

            Guard::assignToAbsence($absence, $session, $teacherId);

            $carbonDate = Carbon::parse($absence->date);
            $dayLetter = match ($carbonDate->dayOfWeek) {
                1 => 'L', 2 => 'M', 3 => 'X', 4 => 'J', 5 => 'V', default => null,
            };

            $bookguard = Bookguard::findByDayAndSession($dayLetter, $session->id);

            if ($bookguard) {
                Log::info('Bookguard encontrado', [
                    'day' => $dayLetter,
                    'session_id' => $session->id,
                    'bookguard_id' => $bookguard->id,
                ]);
                BookguardUser::assignUserToClass($bookguard->id, $teacherId, $absence->class_id);
            } else {
                Log::warning('NO se encontró bookguard', [
                    'day' => $dayLetter,
                    'session_id' => $session->id,
                ]);
            }

            $saved[] = $assignment;
        }

        return compact('saved', 'skipped');
    }

    /**
     * Remove guards based on the provided removals.
     *
     * @param array $removals
     * @return array
     */
    public function removeGuards(array $removals): array
    {
        $deleted = [];

        foreach ($removals as $removal) {
            $absenceId = $removal['absence_id'] ?? null;
            $sessionId = $removal['session_id'] ?? null;

            $session = DB::table('sessions_evg')->where('id', $sessionId)->first();

            if (!$absenceId || !$session) continue;

            $teacherId = $removal['teacher_id'] ?? Guard::getRemovalUserIdByAbsenceId($absenceId, $session->hour_start);

            $deletedRows = Guard::deleteGuardByAbsenceId($absenceId, $session->hour_start);

            if ($deletedRows > 0) {
                $deleted[] = $removal;
            }

            if ($teacherId) {
                $absence = Absence::find($absenceId);
                if ($absence) {
                    $dayLetter = match (Carbon::parse($absence->date)->dayOfWeek) {
                        1 => 'L', 2 => 'M', 3 => 'X', 4 => 'J', 5 => 'V', default => null,
                    };

                    $bookguard = Bookguard::findByDayAndSession($dayLetter, $session->id);
                    if ($bookguard) {
                        BookguardUser::deleteClassFromBookguard($bookguard->id, $teacherId);
                    }
                }
            }
        }

        return $deleted;
    }
}
