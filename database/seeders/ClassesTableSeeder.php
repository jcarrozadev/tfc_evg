<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('classes')->insert([
            ['num_class' => 1, 'course' => 'ESO', 'code' => 'A', 'enabled' => 1],
            ['num_class' => 2, 'course' => 'ESO', 'code' => 'B', 'enabled' => 1],
            ['num_class' => 3, 'course' => 'ESO', 'code' => 'A', 'enabled' => 1],
            ['num_class' => 3, 'course' => 'ESO', 'code' => 'B', 'enabled' => 1],
            ['num_class' => 3, 'course' => 'ESO', 'code' => 'C', 'enabled' => 1],
            ['num_class' => 4, 'course' => 'ESO', 'code' => 'A', 'enabled' => 1],
            ['num_class' => 4, 'course' => 'ESO', 'code' => 'B', 'enabled' => 1],
            ['num_class' => 4, 'course' => 'ESO', 'code' => 'C', 'enabled' => 1],
            ['num_class' => 1, 'course' => 'BACH', 'code' => 'A', 'enabled' => 1],
            ['num_class' => 1, 'course' => 'BACH', 'code' => 'B', 'enabled' => 1],
            ['num_class' => 2, 'course' => 'BACH', 'code' => 'A', 'enabled' => 1],
            ['num_class' => 2, 'course' => 'BACH', 'code' => 'B', 'enabled' => 1],
            ['num_class' => 1, 'course' => 'DAW', 'code' => '-', 'enabled' => 1],
            ['num_class' => 2, 'course' => 'DAW', 'code' => '-', 'enabled' => 1],
            ['num_class' => 1, 'course' => 'SMR', 'code' => '-', 'enabled' => 1],
        ]);
    }
}
