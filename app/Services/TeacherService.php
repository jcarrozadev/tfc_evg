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

    /**
     * Get all teachers.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllTeachers(): Collection
    {
        return User::getAllEnabledTeachers()->map(function ($teacher): User {
            $teacher->available = $teacher->available === 1 ? 'Sí' : 'No';
            return $teacher;
        });
    }

    /**
     * Create a new teacher.
     *
     * @param array $data
     * @return User
     */
    public function createTeacher(array $data): User
    {
        return User::addTeacher($data);
    }

    /**
     * Edit an existing teacher.
     *
     * @param int $id 
     * @param array $data
     * @return bool
     */
    public function updateTeacher(int $id, array $data): bool
    {
        $teacher = User::getTeacherById($id);
        return $teacher && User::editTeacher($teacher, $data);
    }

    /**
     * Delete a teacher by setting their enabled status to false.
     *
     * @param int $id
     * @return bool
     */
    public function deleteTeacher(int $id): Collection
    {
        return User::deleteTeacher($id);
    }
}
