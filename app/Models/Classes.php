<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Classes
 * Represents a class record in the system.
 */
class Classes extends Model {

    protected $table = 'classes';

    protected $fillable = [
        'num_class',
        'course',
        'code',
        'bookguard_id'
    ];

    /**
     * Get all classes.
     *
     * @return Collection
     */
    public static function getAllClasses(): Collection {
        return self::select('id', 'num_class', 'course', 'code')
            ->get();
    }

    /**
     * Get all enabled classes.
     *
     * @return Collection
     */
    public static function getEnabledClasses(): Collection {
        return self::select('id', 'num_class', 'course', 'code')
            ->where('enabled', 1)
            ->get();
    }

    /**
     * Get the count of all classes.
     *
     * @return int
     */
    public static function getClassesCount(): int {
        return self::all()->count();
    }

    /**
     * Get a class by its ID.
     *
     * @param int $id
     * @return Classes|null
     */
    public static function getClassById($id): ?Classes {
        return self::where('id', $id)
            ->first();
    }

    /**
     * Create a new class with the provided data.
     *
     * @param array $data
     * @return Classes
     */
    public static function addClass(array $data): self {
        return self::create([
            'num_class' => $data['num_class'],
            'course'    => $data['course'],
            'code'      => $data['code'],
        ]);
    }

    /**
     * Update an existing class with new data.
     *
     * @param Classes $class
     * @param array $data
     * @return bool
     */
    public static function editClass(Classes $class, array $data): bool {
        $changed = false;

        if (isset($data['num_class']) && $class->num_class !== $data['num_class']) {
            $class->num_class = $data['num_class'];
            $changed = true;
        }

        if (isset($data['course']) && $class->course !== $data['course']) {
            $class->course = $data['course'];
            $changed = true;
        }

        if (isset($data['code']) && $class->code !== $data['code']) {
            $class->code = $data['code'];
            $changed = true;
        }

        return $changed ? $class->save() : true;
    }

    /**
     * Soft delete a class by setting enabled to 0.
     *
     * @param int $id
     * @return bool
     */
    public static function destroyClass($id): bool {
        return self::where('id', $id)
            ->update(['enabled' => 0]) > 0;
    }

    /**
     * Check if a class exists based on num_class, course, and code.
     *
     * @param string $numClass
     * @param string $course
     * @param string $code
     * @return bool
     */
    public static function existClass($numClass, $course, $code): bool {
        $class = self::where('num_class', $numClass)
            ->where('course', $course)
            ->where('code', $code)
            ->first();

        if ($class) {
            $class->enabled = 1;
            $class->save();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the display name for the class.
     *
     * @return string
     */
    public function getDisplayNameAttribute(): string {

        $prefix = $this->num_class . $this->course;

        return $this->code !== '-' ? "$prefix $this->code" : $prefix;
    }

}
