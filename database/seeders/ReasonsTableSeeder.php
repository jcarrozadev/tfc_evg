<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReasonsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('reasons')->insert([
            ['name' => 'Enfermedad'],
            ['name' => 'Reunión'],
            ['name' => 'Formación'],
            ['name' => 'Permiso'],
            ['name' => 'Cita Médica'],
            ['name' => 'Otros'],
            ['name' => 'Motivos personales'],
            ['name' => 'Falta injustificada'],
            ['name' => 'Vacaciones'],
            ['name' => 'Permiso por estudios'],
            ['name' => 'Baja médica'],
            ['name' => 'Problemas familiares'],
            ['name' => 'Cita médica urgente'],
            ['name' => 'Tareas admin'],
            ['name' => 'Otros motivos'],
        ]);        
    }
}
