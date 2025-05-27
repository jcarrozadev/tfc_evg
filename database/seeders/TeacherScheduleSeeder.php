<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class TeacherScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $days     = ['L', 'M', 'X', 'J', 'V'];
        $now      = Carbon::now();
        $classIds = range(1, 15); 

        for ($userId = 1; $userId <= 15; $userId++) {
            foreach ($days as $day) {

                $numSessions = rand(3, 5);

                $sessions = collect(range(1, 6))->shuffle()->take($numSessions);

                foreach ($sessions as $sessionId) {
                    $classId = $classIds[array_rand($classIds)];

                    DB::table('teacher_schedules')->insert([
                        'user_id'    => $userId,
                        'day'        => $day,
                        'session_id' => $sessionId,
                        'class_id'   => $classId,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }
        }
    }
}
