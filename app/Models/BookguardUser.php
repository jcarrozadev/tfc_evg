<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

class BookguardUser extends Model
{
    protected $table = 'bookguard_user';
    protected $fillable = ['bookguard_id', 'user_id', 'class_id'];

    public static function getAllBookguardUsers(): array {
        return self::select( 'bookguard_id', 'user_id', 'class_id')
            ->orderBy('bookguard_id')
            ->get()
            ->toArray();
    }

    public function bookguard(): BelongsTo {
        return $this->belongsTo(Bookguard::class, 'bookguard_id');
    }

    public static function getByUser(int $userId): array {
        return self::where('user_id', $userId)
            ->join('bookguards', 'bookguard_user.bookguard_id', '=', 'bookguards.id')
            ->join('sessions_evg', 'bookguards.session_id', '=', 'sessions_evg.id')
            ->get([
                'bookguards.day',
                'bookguards.session_id',
                'sessions_evg.hour_start',
                'sessions_evg.hour_end',
            ])
            ->map(function ($item) {
                return [
                    'day'         => $item->day,
                    'session_id'  => $item->session_id,
                    'hour_start'  => $item->hour_start,
                    'hour_end'    => $item->hour_end,
                    'type'        => 'guard',
                    'label'       => 'GUARDIA',
                ];
            })
            ->toArray();
    }

    public static function assignUserToClass(int $bookguardId, int $userId, ?int $classId): void {
        $exists = self::where('bookguard_id', $bookguardId)
            ->where('user_id', $userId)
            ->first();

        if ($exists) {
            $exists->update(['class_id' => $classId]);
        } else {
            self::create([
                'bookguard_id' => $bookguardId,
                'user_id' => $userId,
                'class_id' => $classId,
            ]);
        }
        Log::info('Guardia asignada en libro', [
            'bookguard_id' => $bookguardId,
            'user_id' => $userId,
            'class_id' => $classId,
        ]);
    }

    public static function deleteClassFromBookguard(int $bookguardId, int $userId): void {
        Log::info('Intentando poner class_id a null', [
            'bookguard_id' => $bookguardId,
            'user_id' => $userId,
        ]);
        self::where('bookguard_id', $bookguardId)
            ->where('user_id', $userId)
            ->update(['class_id' => null]);
    }


}
