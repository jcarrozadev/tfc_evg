<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Classes
 * Represents a class record in the system.
 */
class Reason extends Model {
    protected $table = 'reasons';
    protected $fillable = ['name'];

    /**
     * Get all reasons.
     *
     * @return Collection
     */
    public static function getAllReasons(): Collection {

        $reasons = new self();

        return $reasons->select('id','name')
            ->orderBy('id')
            ->get();
    }
}
