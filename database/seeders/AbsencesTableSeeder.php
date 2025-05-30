<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AbsencesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('absences')->insert([
            [
                'date' => now()->toDateString(),
                'hour_start' => '09:15:00',
                'hour_end' => '10:15:00',
                'justify' => 'Justificación médica',
                'info_task' => 'Los ejercicios están en el classroom',
                'user_id' => 1,
                'reason_id' => 1,
                'reason_description' => null,
                'status' => false,
            ],
            [
                'date' => now()->toDateString(),
                'hour_start' => '10:15:00',
                'hour_end' => '11:15:00',
                'justify' => null,
                'info_task' => null,
                'user_id' => 2,
                'reason_id' => 2,
                'reason_description' => null,
                'status' => false,
            ],
            [
                'date' => now()->toDateString(),
                'hour_start' => '11:15:00',
                'hour_end' => '12:15:00',
                'justify' => null,
                'info_task' => null,
                'user_id' => 3,
                'reason_id' => 3,
                'reason_description' => null,
                'status' => false,
            ],
            [
                'date' => now()->toDateString(),
                'hour_start' => '12:15:00',
                'hour_end' => '13:15:00',
                'justify' => null,
                'info_task' => null,
                'user_id' => 4,
                'reason_id' => 4,
                'reason_description' => null,
                'status' => false,
            ],
            [
                'date' => now()->toDateString(),
                'hour_start' => '13:15:00',
                'hour_end' => '14:15:00',
                'justify' => null,
                'info_task' => null,
                'user_id' => 5,
                'reason_id' => 5,
                'reason_description' => null,
                'status' => false,
            ],
            [
                'date' => now()->toDateString(),
                'hour_start' => '14:15:00',
                'hour_end' => '15:15:00',
                'justify' => null,
                'info_task' => null,
                'user_id' => 6,
                'reason_id' => 6,
                'reason_description' => null,
                'status' => false,
            ],
            [
                'date' => now()->toDateString(),
                'hour_start' => '15:15:00',
                'hour_end' => '16:15:00',
                'justify' => null,
                'info_task' => null,
                'user_id' => 7,
                'reason_id' => 7,
                'reason_description' => null,
                'status' => false,
            ],
            [
                'date' => now()->toDateString(),
                'hour_start' => '16:15:00',
                'hour_end' => '17:15:00',
                'justify' => null,
                'info_task' => null,
                'user_id' => 8,
                'reason_id' => 8,
                'reason_description' => null,
                'status' => false,
            ],
            [
                'date' => now()->toDateString(),
                'hour_start' => '17:15:00',
                'hour_end' => '18:15:00',
                'justify' => null,
                'info_task' => null,
                'user_id' => 9,
                'reason_id' => 9,
                'reason_description' => null,
                'status' => false,
            ],
            [
                'date' => now()->toDateString(),
                'hour_start' => '18:15:00',
                'hour_end' => '19:15:00',
                'justify' => null,
                'info_task' => null,
                'user_id' => 10,
                'reason_id' => 10,
                'reason_description' => null,
                'status' => false,
            ],
            [
                'date' => now()->toDateString(),
                'hour_start' => '19:15:00',
                'hour_end' => '20:15:00',
                'justify' => null,
                'info_task' => null,
                'user_id' => 11,
                'reason_id' => 11,
                'reason_description' => null,
                'status' => false,
            ],
            [
                'date' => now()->toDateString(),
                'hour_start' => '20:15:00',
                'hour_end' => '21:15:00',
                'justify' => null,
                'info_task' => null,
                'user_id' => 12,
                'reason_id' => 12,
                'reason_description' => null,
                'status' => false,
            ],
            [
                'date' => now()->toDateString(),
                'hour_start' => '21:15:00',
                'hour_end' => '22:15:00',
                'justify' => null,
                'info_task' => null,
                'user_id' => 13,
                'reason_id' => 13,
                'reason_description' => null,
                'status' => false,
            ],
            [
                'date' => now()->toDateString(),
                'hour_start' => '22:15:00',
                'hour_end' => '23:15:00',
                'justify' => null,
                'info_task' => null,
                'user_id' => 14,
                'reason_id' => 14,
                'reason_description' => null,
                'status' => false,
            ],
            [
                'date' => now()->toDateString(),
                'hour_start' => '23:15:00',
                'hour_end' => '00:15:00',
                'justify' => null,
                'info_task' => null,
                'user_id' => 15,
                'reason_id' => 15,
                'reason_description' => null,
                'status' => false,
            ],
        ]);
    }
}
