<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SessionsEvgTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('sessions_evg')->insert([
            ['hour_start' => '08:15:00', 'hour_end' => '09:10:00'],
            ['hour_start' => '09:10:00', 'hour_end' => '10:05:00'],
            ['hour_start' => '10:05:00', 'hour_end' => '11:00:00'],
            ['hour_start' => '11:30:00', 'hour_end' => '12:25:00'],
            ['hour_start' => '12:25:00', 'hour_end' => '13:20:00'],
            ['hour_start' => '13:20:00', 'hour_end' => '14:15:00'],
        ]);
    }
}
