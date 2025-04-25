<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('classes')->insert([
            ['num_class' => 1, 'course' => 'ESO', 'code' => 'A', 'bookguard_id' => 1, 'enabled' => 1],
            ['num_class' => 2, 'course' => 'ESO', 'code' => 'B', 'bookguard_id' => 2, 'enabled' => 1],
            ['num_class' => 3, 'course' => 'ESO', 'code' => 'A', 'bookguard_id' => 3, 'enabled' => 1],
            ['num_class' => 3, 'course' => 'ESO', 'code' => 'B', 'bookguard_id' => 4, 'enabled' => 1],
            ['num_class' => 3, 'course' => 'ESO', 'code' => 'C', 'bookguard_id' => 5, 'enabled' => 1],
            ['num_class' => 4, 'course' => 'ESO', 'code' => 'A', 'bookguard_id' => 6, 'enabled' => 1],
            ['num_class' => 4, 'course' => 'ESO', 'code' => 'B', 'bookguard_id' => 7, 'enabled' => 1],
            ['num_class' => 4, 'course' => 'ESO', 'code' => 'C', 'bookguard_id' => 8, 'enabled' => 1],

            ['num_class' => 1, 'course' => 'BACH', 'code' => 'A', 'bookguard_id' => 9, 'enabled' => 1],
            ['num_class' => 1, 'course' => 'BACH', 'code' => 'B', 'bookguard_id' => 10, 'enabled' => 1],
            ['num_class' => 2, 'course' => 'BACH', 'code' => 'A', 'bookguard_id' => 11, 'enabled' => 1],
            ['num_class' => 2, 'course' => 'BACH', 'code' => 'B', 'bookguard_id' => 12, 'enabled' => 1],

            ['num_class' => 1, 'course' => 'DAW', 'code' => '-', 'bookguard_id' => 13, 'enabled' => 1],
            ['num_class' => 2, 'course' => 'DAW', 'code' => '-', 'bookguard_id' => 14, 'enabled' => 1],
            ['num_class' => 1, 'course' => 'SMR', 'code' => '-', 'bookguard_id' => 15, 'enabled' => 1],
        ]);
    }
}
