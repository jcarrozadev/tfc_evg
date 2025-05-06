<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bookguard extends Model
{
    protected $table = 'bookguards';
    protected $fillable = ['day', 'session_id'];

    public function users() {
        return $this->belongsToMany(User::class, 'bookguard_user');
    }

    public static function getAllBookguards() {
        return self::select('id', 'day', 'session_id')
            ->orderBy('id')
            ->get();
    }

    public static function storeFromWeeklyInput(array $guards): bool
    {
        BookguardUser::query()->delete();
        Bookguard::query()->delete();
    
        foreach ($guards as $day => $slots) {
            foreach ($slots as $timeRange => $entries) {
    
                [$start, $end] = explode('-', $timeRange);
                $start = self::formatTime($start);
                $end = self::formatTime($end);
    
                $session = Session::where('hour_start', $start)
                    ->where('hour_end', $end)
                    ->first();
    
                if (!$session) {
                    continue;
                }
    
                $bookguard = self::create([
                    'day' => $day[0], // 'L', 'M', etc.
                    'session_id' => $session->id,
                ]);
    
                foreach ($entries as $entry) {
                    if (!isset($entry['user_id']) || $entry['user_id'] === '-') {
                        continue;
                    }
    
                    BookguardUser::create([
                        'bookguard_id' => $bookguard->id,
                        'user_id' => $entry['user_id'],
                        'class_id' => $entry['class_id'] ?? null,
                    ]);
                }
            }
        }
    
        return true;
    }
    
    protected static function formatTime(string $short): string {
        // De '8:15' a '08:15:00'
        [$h, $m] = explode(':', $short);
        return sprintf('%02d:%02d:00', $h, $m);
    }

}

