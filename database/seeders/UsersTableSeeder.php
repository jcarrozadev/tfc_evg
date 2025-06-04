<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Isabel Muñoz',     'email' => 'imunoz@fundacionloyola.es'],
            ['name' => 'Ernesto Gonzalez', 'email' => 'egonzalez@fundacionloyola.es'],
            ['name' => 'Alberto Dominguez','email' => 'albertodominguez@fundacionloyola.es'],
            ['name' => 'Francisco Garcia', 'email' => 'fgarcia@fundacionloyola.es'],
            ['name' => 'Magdalena Sanchez','email' => 'msanchez@fundacionloyola.es'],
            ['name' => 'Manuel Merino',    'email' => 'mmerino@fundacionloyola.es'],
            ['name' => 'Miguel Jaque',     'email' => 'mjaque@fundacionloyola.es'],
            ['name' => 'Magdalena Pozo',   'email' => 'mpozo@fundacionloyola.es'],
            ['name' => 'Gema Lopez',       'email' => 'glopez@fundacionloyola.es'],
            ['name' => 'Tomas Garcia',     'email' => 'tgarcia@fundacionloyola.es'],
            ['name' => 'Ana Moreno',       'email' => 'amoreno@fundacionloyola.es'],
            ['name' => 'Lucia Romero',     'email' => 'lromero@fundacionloyola.es'],
            ['name' => 'Jorge Navarro',    'email' => 'jnavarro@fundacionloyola.es'],
            ['name' => 'Elena Serrano',    'email' => 'eserrano@fundacionloyola.es'],
            ['name' => 'Elia Ruiz',      'email' => 'eortiz@fundacionloyola.es'],
        ];

        $now = now();
        $insertedUserIds = [];

        foreach ($users as $user) {
            $userId = DB::table('users')->insertGetId([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make('Guardsevg2425'),
                'avatar' => 'default.jpg',
                'available' => true,
                'role_id' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $insertedUserIds[] = $userId;
        }

        $bookguardIds = DB::table('bookguards')->pluck('id')->toArray();

        $relations = [];

        for ($i = 0; $i < count($insertedUserIds); $i++) {
            $relations[] = [
                'user_id' => $insertedUserIds[$i],
                'bookguard_id' => $bookguardIds[$i % count($bookguardIds)],
                'class_id' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('bookguard_user')->insert($relations);
    }
}

