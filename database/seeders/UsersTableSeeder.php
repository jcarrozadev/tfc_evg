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
            ['name' => 'Isabel Muñoz',     'email' => 'imunoz@fundacionloyola.es',      'phone' => '612345678', 'dni' => '12345678Z'],
            ['name' => 'Ernesto Gonzalez', 'email' => 'egonzalez@fundacionloyola.es',   'phone' => '622345678', 'dni' => '87654321X'],
            ['name' => 'Alberto Dominguez','email' => 'adominguez@fundacionloyola.es',  'phone' => '632345678', 'dni' => '11223344M'],
            ['name' => 'Francisco Garcia', 'email' => 'fgarcia@fundacionloyola.es',     'phone' => '642345678', 'dni' => '22334455S'],
            ['name' => 'Magdalena Sanchez','email' => 'msanchez@fundacionloyola.es',    'phone' => '652345678', 'dni' => '33445566L'],
            ['name' => 'Manuel Merino',    'email' => 'mmerino@fundacionloyola.es',     'phone' => '662345678', 'dni' => '44556677H'],
            ['name' => 'Miguel Jaque',     'email' => 'mjaque@fundacionloyola.es',      'phone' => '672345678', 'dni' => '55667788Y'],
            ['name' => 'Zeus Martin',      'email' => 'zmartinllera1@gmail.com',        'phone' => '682345678', 'dni' => '66778899P'],
            ['name' => 'Magdalena Pozo',   'email' => 'mpozo@fundacionloyola.es',       'phone' => '692345678', 'dni' => '77889900G'],
            ['name' => 'Gema Lopez',       'email' => 'glopez@fundacionloyola.es',      'phone' => '702345678', 'dni' => '88990011T'],
            ['name' => 'Tomas Garcia',     'email' => 'tgarcia@fundacionloyola.es',     'phone' => '712345678', 'dni' => '99001122A'],
            ['name' => 'Ana Moreno',       'email' => 'amoreno@fundacionloyola.es',     'phone' => '722345678', 'dni' => '10112233C'],
            ['name' => 'Lucia Romero',     'email' => 'lromero@fundacionloyola.es',     'phone' => '732345678', 'dni' => '19283746K'],
            ['name' => 'Jorge Navarro',    'email' => 'jnavarro@fundacionloyola.es',    'phone' => '742345678', 'dni' => '38475629N'],
            ['name' => 'Elena Serrano',    'email' => 'eserrano@fundacionloyola.es',    'phone' => '752345678', 'dni' => '47586932R'],
        ];

        $now = now();
        $insertedUserIds = [];

        foreach ($users as $user) {
            $userId = DB::table('users')->insertGetId([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make('password'),
                'phone' => $user['phone'],
                'dni' => $user['dni'],
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

