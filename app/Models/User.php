<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
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

    public static function getAllTeachers()
    {
        return self::where('role_id', 2)
            ->get();
    }
}
