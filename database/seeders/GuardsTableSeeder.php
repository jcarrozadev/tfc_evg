<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GuardsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('guards')->insert([
            [
                'date' => now()->toDateString(),
                'text_guard' => 'Guardia realizada sin incidencias',
                'hour' => '09:15:00',
                'user_sender_id' => 2,
                'absence_id' => 1,
            ],
            [
                'date' => now()->toDateString(),
                'text_guard' => 'Guardia realizada con incidencias',
                'hour' => '10:15:00',
                'user_sender_id' => 3,
                'absence_id' => 2,
            ],
            [
                'date' => now()->toDateString(),
                'text_guard' => 'Guardia realizada sin incidencias',
                'hour' => '11:15:00',
                'user_sender_id' => 4,
                'absence_id' => 3,
            ],
            [
                'date' => now()->toDateString(),
                'text_guard' => 'Guardia realizada con incidencias',
                'hour' => '12:15:00',
                'user_sender_id' => 5,
                'absence_id' => 4,
            ],
            [
                'date' => now()->toDateString(),
                'text_guard' => 'Guardia realizada sin incidencias',
                'hour' => '13:15:00',
                'user_sender_id' => 6,
                'absence_id' => 5,
            ],
            [
                'date' => now()->toDateString(),
                'text_guard' => 'Guardia realizada con incidencias',
                'hour' => '14:15:00',
                'user_sender_id' => 7,
                'absence_id' => 6,
            ],
        ]);
    }
}
