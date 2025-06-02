<?php

namespace App\Services;

use App\Models\BookguardUser;
use App\Models\Guard;
use App\Models\Session;
use App\Models\TeacherSchedule;
use App\Models\User;

/**
 * Class ScheduleService
 * Handles the retrieval of schedule data for users.
 */
class ScheduleService
{
    /**
     * Get the schedule data for a specific user.
     *
     * @param int $userId
     * @return array
     */
    public function getScheduleDataForUser(int $userId): array
    {
        $user = User::getDataSettingTeacherById($userId);
        $sessions = Session::getAllSessions();
        $schedule = TeacherSchedule::getByUser($userId);
        $guardias = BookguardUser::getByUser($userId);

        $merged = collect($schedule)
            ->merge($guardias)
            ->keyBy(fn ($item) => $item['day'] . '|' . $item['session_id'])
            ->values()
            ->all();

        return [
            'user' => $user,
            'sessions' => $sessions,
            'full' => $merged,
        ];
    }

    /**
     * Get the personal guards for a specific user.
     *
     * @param int $userId
     * @return array
     */
    public function getPersonalGuards(int $userId): array
    {
        return Guard::getGuardsTodayById($userId);
    }

    /**
     * Get all guards assigned for today.
     *
     * @return array
     */
    public function getGuardsToday(): array
    {
        return Guard::getGuardsToday();
    }

    /**
     * Get all teachers available for the given session IDs and today's letter.
     *
     * @param array $sessionIds
     * @param string $todayLetter
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getUserData(int $userId): ?User
    {
        return User::getDataSettingTeacherById($userId);
    }
}
