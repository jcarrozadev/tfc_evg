<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookguardsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('bookguards')->insert([
            ['id' => 1, 'day' => 'L', 'session_id' => 1],
            ['id' => 2, 'day' => 'L', 'session_id' => 2],
            ['id' => 3, 'day' => 'L', 'session_id' => 3],
            ['id' => 4, 'day' => 'L', 'session_id' => 4],
            ['id' => 5, 'day' => 'L', 'session_id' => 5],
            ['id' => 6, 'day' => 'L', 'session_id' => 6],
            ['id' => 7, 'day' => 'L', 'session_id' => 7],
            ['id' => 8, 'day' => 'L', 'session_id' => 7],
            ['id' => 9, 'day' => 'M', 'session_id' => 1],
            ['id' => 10, 'day' => 'M', 'session_id' => 2],
            ['id' => 11, 'day' => 'M', 'session_id' => 3],
            ['id' => 12, 'day' => 'M', 'session_id' => 4],
            ['id' => 13, 'day' => 'X', 'session_id' => 5],
            ['id' => 14, 'day' => 'X', 'session_id' => 6],
            ['id' => 15, 'day' => 'X', 'session_id' => 7],
        ]);
        
    }
}
