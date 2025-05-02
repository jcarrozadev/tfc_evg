<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public static function createAbsence(array $data): Absence{
        $absence = new self();

        return $absence::create($data);
    }

    public static function getAbsencesTodayWithDetails(): Collection {
        $absence = new self();

        return $absence::join('users', 'absences.user_id', '=', 'users.id')
            ->join('reasons', 'absences.reason_id', '=', 'reasons.id')
            ->whereDate('absences.date', now()->toDateString())
            ->where('absences.status', 0)
            ->select(
                'absences.id',
                'users.name as user_name',
                DB::raw("DATE_FORMAT(absences.hour_start, '%H:%i') as hour_start"),
                DB::raw("DATE_FORMAT(absences.hour_end, '%H:%i') as hour_end"),
                'reasons.name as reason_name'
            )
            ->get();
    }

    public static function getAbsenceById($id): ?Absence {
        return self::where('id', $id)->first();
    }

}
