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
            ['name' => 'Juan Pérez', 'email' => 'juan@example.com', 'phone' => '612345678', 'dni' => '12345678A'],
            ['name' => 'Ana López', 'email' => 'ana@example.com', 'phone' => '622345678', 'dni' => '87654321B'],
            ['name' => 'Carlos García', 'email' => 'carlos@example.com', 'phone' => '632345678', 'dni' => '11223344C'],
            ['name' => 'Laura Martínez', 'email' => 'laura@example.com', 'phone' => '642345678', 'dni' => '22334455D'],
            ['name' => 'Pedro Sánchez', 'email' => 'pedro@example.com', 'phone' => '652345678', 'dni' => '33445566E'],
            ['name' => 'Marta Pérez', 'email' => 'marta@example.com', 'phone' => '662345678', 'dni' => '44556677F'],
            ['name' => 'José Gómez', 'email' => 'jose@example.com', 'phone' => '672345678', 'dni' => '55667788G'],
            ['name' => 'Elena Ruiz', 'email' => 'elena@example.com', 'phone' => '682345678', 'dni' => '66778899H'],
            ['name' => 'David Fernández', 'email' => 'david@example.com', 'phone' => '692345678', 'dni' => '77889900I'],
            ['name' => 'Sofia López', 'email' => 'sofia@example.com', 'phone' => '702345678', 'dni' => '88990011J'],
            ['name' => 'Javier Martín', 'email' => 'javier@example.com', 'phone' => '712345678', 'dni' => '99001122K'],
            ['name' => 'Raquel García', 'email' => 'raquel@example.com', 'phone' => '722345678', 'dni' => '10112233L'],
            ['name' => 'Luis Torres', 'email' => 'luis@example.com', 'phone' => '732345678', 'dni' => '11223344M'],
            ['name' => 'Julia Sánchez', 'email' => 'julia@example.com', 'phone' => '742345678', 'dni' => '22334455N'],
            ['name' => 'Antonio Ruiz', 'email' => 'antonio@example.com', 'phone' => '752345678', 'dni' => '33445566O'],
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

