<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
