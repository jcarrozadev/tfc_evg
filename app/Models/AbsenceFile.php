<?php

namespace App\Models;

use App\Models\Absence;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AbsenceFile extends Model
{
    protected $table = 'absence_files';

    protected $fillable = [
        'absence_id',
        'file_path',
        'original_name',
    ];


    /**
     * Get the absence associated with the file.
     *
     * @return BelongsTo
     */
    public function absence(): BelongsTo
    {
        return $this->belongsTo(Absence::class);
    }


    /**
     * Store a new absence file.
     *
     * @param array $data
     * @return AbsenceFile
     */
    public static function storeFile(array $data): AbsenceFile
    {
        return self::create([
                    'absence_id' => $data['id'],
                    'file_path' => $data['file_path'],
                    'original_name' => $data['file']->getClientOriginalName(),
                ]);
    }
}