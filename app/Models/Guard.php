<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Guard extends Model
{
    protected $table = 'guards';

    protected $fillable = [
        'date', 'text_guard', 'hour', 'user_sender_id', 'absence_id',
    ];

    public function absence(): BelongsTo{
        return $this->belongsTo(Absence::class);
    }

    public function teacher(): BelongsTo{
        return $this->belongsTo(User::class);
    }

    public static function getWeeklySummary(): array {
        $startOfWeek = Carbon::now()->startOfWeek(); 
        $endOfWeek = Carbon::now()->startOfWeek()->addDays(4); 

        $guards = self::selectRaw('DAYNAME(date) as dia, COUNT(*) as total')
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->groupBy('dia')
            ->pluck('total', 'dia');

        $dayOrders = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

        $resum = [];
        foreach ($dayOrders as $day) {
            $resum[] = $guards[$day] ?? 0;
        }

        return $resum;
    }

    public static function getTodaySummary(): int {
        return self::whereDate('date', Carbon::today())->count();
    }

    public static function getGuardByAbsenceId($absenceId): ?Guard {
        dd(self::where('absence_id', $absenceId)->first());
    }
}

