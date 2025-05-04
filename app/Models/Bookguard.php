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

    public static function storeFromWeeklyInput(array $guardias): void
    {
        foreach ($guardias as $day => $slots) {
            foreach ($slots as $slot => $entries) {
                $session = Session::where('hour', $slot)->first();
                if (!$session) continue;

                $bookguard = self::create([
                    'day' => strtoupper(substr($day, 0, 1)), // L, M, X, J, V
                    'session_id' => $session->id,
                ]);

                foreach ($entries as $entry) {
                    if (!isset($entry['profesor']) || empty($entry['profesor'])) continue;

                    BookguardUser::create([
                        'bookguard_id' => $bookguard->id,
                        'user_id' => $entry['profesor'],
                        'class_id' => $entry['clase'] ?? null,
                    ]);
                }
            }
        }
    }
}

