<?php

namespace App\Services;

use App\Models\Absence;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class AbsenceService
 * Handles operations related to absences, such as fetching today's absences and collecting session IDs.
 */
class AbsenceService
{
    /**
     * Get all absences for today.
     *
     * @return Collection
     */
    public function getAbsencesForToday(): Collection
    {
        return Absence::withSessionsForToday();
    }

    /**
     * Collect all session IDs based on the provided absences.
     *
     * @param Collection $absences
     * @return array
     */
    public function collectAllSessionIds(Collection $absences): array
    {
        $hasFullDayAbsence = $absences->contains(function ($absence) {
            return is_null($absence->hour_start) || is_null($absence->hour_end);
        });

        if ($hasFullDayAbsence) {
            return DB::table('sessions_evg')->pluck('id')->unique()->toArray();
        }

        $hours = $absences->map(function ($absence) {
            return [
                'start' => $absence->hour_start,
                'end' => $absence->hour_end,
            ];
        });

        return DB::table('sessions_evg')
            ->where(function ($q) use ($hours) {
                foreach ($hours as $pair) {
                    $q->orWhere(function ($subQ) use ($pair) {
                        $subQ->where('hour_start', '>=', $pair['start'])
                            ->where('hour_end', '<=', $pair['end']);
                    });
                }
            })
            ->pluck('id')
            ->unique()
            ->toArray();
    }
}
