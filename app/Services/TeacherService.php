<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;

/**
 * Class TeacherService
 * Handles operations related to teachers, such as fetching available teachers and preparing teacher cards.
 */
class TeacherService
{
    /**
    * Get available teachers for the given session IDs and today's letter.
     * @param array $sessionIds
     * @param string $todayLetter
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAvailableTeachers(array $sessionIds, string $todayLetter): Collection
    {
        $teachers = User::getAvailableTeachersForSessions($sessionIds, $todayLetter);

        foreach ($teachers as $teacher) {
            $teacher->loadSessionIds(); 
        }

        return $teachers;
    }

    /**
     * Prepare teacher cards for the given collection of teachers and session IDs.
     *
     * @param Collection $teachers
     * @param array $sessionIds
     * @return Collection
     */
    public function prepareTeacherCards(Collection $teachers, array $sessionIds): Collection
    {
        $cards = collect();

        foreach ($teachers as $teacher) {
            foreach ($teacher->session_ids as $sessionId) {
                if (!in_array($sessionId, $sessionIds)) continue;

                $cards->push((object)[
                    'id' => $teacher->id,
                    'name' => $teacher->name,
                    'session_id' => $sessionId,
                    'image_profile' => $teacher->image_profile,
                ]);
            }
        }

        return $cards->sortBy('session_id')->values();
    }
}
