<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absence extends Model
{
    protected $table = 'absences';

    protected $fillable = [
        'date',
        'hour_start',
        'hour_end',
        'justify',
        'info_task',
        'user_id',
        'reason_id',
        'reason_description',
        'status',
    ];

    public static function createAbsence(array $data){
        $absence = new self();

        return $absence::create($data);
    }
}
