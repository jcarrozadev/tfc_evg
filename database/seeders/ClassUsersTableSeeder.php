<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassUsersTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('class_users')->insert([
            ['user_id' => 1, 'num_class' => 1, 'course' => 'ESO', 'code' => 'A'],
            ['user_id' => 2, 'num_class' => 2, 'course' => 'ESO', 'code' => 'B'],
            ['user_id' => 3, 'num_class' => 3, 'course' => 'ESO', 'code' => 'A'],
            ['user_id' => 4, 'num_class' => 3, 'course' => 'ESO', 'code' => 'B'],
            ['user_id' => 5, 'num_class' => 3, 'course' => 'ESO', 'code' => 'C'],
            ['user_id' => 6, 'num_class' => 4, 'course' => 'ESO', 'code' => 'A'],
            ['user_id' => 7, 'num_class' => 4, 'course' => 'ESO', 'code' => 'B'],
            ['user_id' => 8, 'num_class' => 4, 'course' => 'ESO', 'code' => 'C'],

            ['user_id' => 9, 'num_class' => 1, 'course' => 'BACH', 'code' => 'A'],
            ['user_id' => 10, 'num_class' => 1, 'course' => 'BACH', 'code' => 'B'],
            ['user_id' => 11, 'num_class' => 2, 'course' => 'BACH', 'code' => 'A'],
            ['user_id' => 12, 'num_class' => 2, 'course' => 'BACH', 'code' => 'B'],

            ['user_id' => 13, 'num_class' => 1, 'course' => 'DAW', 'code' => '-'],
            ['user_id' => 14, 'num_class' => 2, 'course' => 'DAW', 'code' => '-'],
            ['user_id' => 15, 'num_class' => 1, 'course' => 'SMR', 'code' => '-'],
        ]);
    }
}

