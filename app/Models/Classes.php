<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model {

    protected $table = 'classes';

    protected $fillable = [
        'num_class',
        'course',
        'code',
        'bookguard_id'
    ];

    public static function getAllClasses(): Collection {
        return self::select('id', 'num_class', 'course', 'code')
            ->get();
    }

    public static function getEnabledClasses(): Collection {
        return self::select('id', 'num_class', 'course', 'code')
            ->where('enabled', 1)
            ->get();
    }

    public static function getClassesCount(): int {
        return self::all()->count();
    }

    public static function getClassById($id): ?Classes {
        return self::where('id', $id)
            ->first();
    }

    public static function addClass(array $data): self {
        return self::create([
            'num_class' => $data['num_class'],
            'course'    => $data['course'],
            'code'      => $data['code'],
        ]);
    }

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

    public static function destroyClass($id): bool {
        return self::where('id', $id)
            ->update(['enabled' => 0]) > 0;
    }

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
}
