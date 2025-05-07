<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Database\Seeders\RolesTableSeeder;
use Database\Seeders\ReasonsTableSeeder;
use Database\Seeders\SessionsEvgTableSeeder;
use Database\Seeders\BookguardsTableSeeder;
use Database\Seeders\UsersTableSeeder;
use Database\Seeders\ClassesTableSeeder;
use Database\Seeders\ClassUsersTableSeeder;
use Database\Seeders\AbsencesTableSeeder;
use Database\Seeders\GuardsTableSeeder;


class DatabaseSeeder extends Seeder {
    public function run(): void {
        $this->call([
            RolesTableSeeder::class,
            ReasonsTableSeeder::class,
            SessionsEvgTableSeeder::class,
            ClassesTableSeeder::class,      
            BookguardsTableSeeder::class,      
            UsersTableSeeder::class,        
            ClassUsersTableSeeder::class,
        ]);
    } 
}
