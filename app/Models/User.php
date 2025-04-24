<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use Notifiable;
    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'dni',
        'role_id',
        'google_id',
    ];

    public function __construct() {
        parent::__construct();
    }

    public function role(): BelongsTo {
        return $this->belongsTo(Role::class);
    }

    public static function getAllEnabledTeachers():  Collection {
        return self::where('role_id', 2)
            ->where('enabled', 1)
            ->get();
    }

    public static function getTeachersCount(): int {
        return self::where('role_id', 2)->count();
    }

    public static function deleteTeacher($id): bool {
        return self::where('id', $id)
            ->update(['enabled' => false]) > 0;
    }

    public static function getTeacherById($id): ?User {
        return self::where('id', $id)
            ->first();
    }

    public static function addTeacher(array $data): self {
        return self::create([
            'name' => $data['name'],
            'email'    => $data['email'],
            'password'      => Hash::make($data['password']),
            'phone' => $data['phone'],
            'dni' => $data['dni'],
            'avatar' => $data['avatar'] ?? 'default.png',
            'available' => $data['available'] ?? 1,
            'role_id' => $data['role_id'] ?? 2,
        ]);
    }

    public static function editTeacher(User $teacher, array $data): bool {
        $changed = false;

        if (isset($data['name']) && $teacher->name !== $data['name']) {
            $teacher->name = $data['name'];
            $changed = true;
        }

        if (isset($data['email']) && $teacher->email !== $data['email']) {
            $teacher->email = $data['email'];
            $changed = true;
        }

        if (isset($data['phone']) && $teacher->phone !== $data['phone']) {
            $teacher->phone = $data['phone'];
            $changed = true;
        }
        if (isset($data['dni']) && $teacher->dni !== $data['dni']) {
            $teacher->dni = $data['dni'];
            $changed = true;
        }

        return $changed ? $teacher->save() : true;
    }

}
