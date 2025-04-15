<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
        'role_id'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public static function getAllTeachers()
    {
        return self::where('role_id', 2)
            ->get();
    }

    public static function getTeachersCount()
    {
        return self::where('role_id', 2)->count();
    }
}
