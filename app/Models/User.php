<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\Hash;

/**
 * Class User
 * Represents a user in the system.
 */
class User extends Authenticatable
{
    use Notifiable;
    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'callmebot_apikey',
        'dni',
        'available',
        'role_id',
        'google_id',
    ];

    /**
    * User model constructor.
    * Calls the parent constructor.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Get the role associated with the user.
     *
     * @return BelongsTo
     */
    public function role(): BelongsTo {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the reason associated with the user.
     *
     * @return BelongsTo
     */
    public static function getHomeTeacherById($id): User {
        return self::select('name', 'image_profile')
            ->where('id', $id)
            ->first();
    }

    /**
     * Get all enabled teachers.
     *
     * @return Collection
     */
    public static function getAllEnabledTeachers():  Collection {
        return self::where('role_id', 2)
            ->where('enabled', 1)
            ->get();
    }

    /**
     * Get all available teachers.
     *
     * @return Collection
     */
    public static function getAllAvailableTeachers(): Collection {
        return self::where('role_id', 2)
            ->where('available', 1)
            ->where('enabled', 1)
            ->get();
    }

    /**
     * Get all available teachers for a specific session and day.
     *
     * @param array $sessionIds
     * @param string $day
     * @return Collection
     */
    public static function getAvailableTeachersForSessions(array $sessionIds, string $day): Collection {
        return self::where('role_id', 2)
            ->where('available', 1)
            ->where('enabled', 1)
            ->whereHas('bookguards', function ($query) use ($sessionIds, $day) {
                $query->whereIn('session_id', $sessionIds)
                      ->where('day', $day);
            })
            ->with(['bookguards' => function ($query) use ($sessionIds, $day) {
                $query->whereIn('session_id', $sessionIds)
                      ->where('day', $day);
            }])
            ->get();
    }          
    
    /**
     * Get all available teachers for today.
     *
     * @param string $day
     * @return Collection
     */
    public static function getAvailableTeachersToday(string $day): Collection {
        return self::where('role_id', 2)
            ->where('available', 1)
            ->where('enabled', 1)
            ->get();
    }

    /**
     * Get the sessions associated with the user through bookguards.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function sessions(): HasManyThrough {
        return $this->hasManyThrough(Session::class, Bookguard::class, 'id', 'id', null, 'session_id');
    }

    /**
     * Get the bookguards associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function bookguards(): BelongsToMany{
        return $this->belongsToMany(Bookguard::class, 'bookguard_user', 'user_id', 'bookguard_id');
    }

    /**
     * Get the bookguards associated with the user for a specific session.
     *
     * @param int $sessionId
     * @return Collection
     */
    public function loadSessionIds(): void {
        $this->session_ids = $this->bookguards
            ->pluck('session_id')
            ->unique()
            ->values()
            ->toArray();
    }      

    /**
     * Get all enabled teachers with their names.
     *
     * @return Collection
     */
    public static function getNameEnabledTeachers(): Collection {
        return self::where('role_id', 2)
            ->where('enabled', 1)
            ->select('id', 'name')
            ->get();
    }

    /**
     * Get the count of all teachers.
     *
     * @return int
     */
    public static function getTeachersCount(): int {
        return self::where('role_id', 2)->count();
    }

    /**
     * Get all teachers.
     *
     * @return Collection
     */
    public static function deleteTeacher($id): bool {
        return self::where('id', $id)
            ->update(['enabled' => false]) > 0;
    }

    /**
     * Get a teacher by their ID.
     *
     * @param int $id
     * @return User|null
     */
    public static function getTeacherById($id): ?User {
        return self::where('id', $id)
            ->first();
    }

    /**
     * Get a teacher by their ID for guard purposes.
     *
     * @param int $id
     * @return User|null
     */
    public static function getTeacherByIdForGuard($id): ?User {
        return self::select('name','email','phone','callmebot_apikey')
            ->where('id', $id)
            ->first();
    }

    /**
     * Get data settings for a teacher by their ID.
     *
     * @param int $id
     * @return User|null
     */
    public static function getDataSettingTeacherById($id): ?User {
        return self::where('id', $id)
            ->select('name', 'email', 'phone','callmebot_apikey', 'dni', 'image_profile', 'google_id')
            ->first();
    }

    /**
     * Add a new teacher to the system.
     *
     * @param array $data
     * @return User
     */
    public static function addTeacher(array $data): self {
        return self::create([
            'name' => $data['name'],
            'email'    => $data['email'],
            'password'      => Hash::make($data['password']),
            'phone' => $data['phone'],
            'dni' => $data['dni'],
            'avatar' => $data['avatar'] ?? 'default.png',
            'available' => $data['available'] ?? 1,
            'role_id' => $data['role_id'] ?? 2,
        ]);
    }

    /**
     * Edit an existing teacher's details.
     *
     * @param User $teacher
     * @param array $data
     * @return bool
     */
    public static function editTeacher(User $teacher, array $data): bool {
        $changed = false;

        if (isset($data['name']) && $teacher->name !== $data['name']) {
            $teacher->name = $data['name'];
            $changed = true;
        }

        if (isset($data['email']) && $teacher->email !== $data['email']) {
            $teacher->email = $data['email'];
            $changed = true;
        }

        if (isset($data['phone']) && $teacher->phone !== $data['phone']) {
            $teacher->phone = $data['phone'];
            $changed = true;
        }
        if (isset($data['dni']) && $teacher->dni !== $data['dni']) {
            $teacher->dni = $data['dni'];
            $changed = true;
        }

        if(isset($data['callmebot_apikey']) && $teacher->callmebot_apikey !== $data['callmebot_apikey']) {
            $teacher->callmebot_apikey = $data['callmebot_apikey'];
            $changed = true;
        }

        return $changed ? $teacher->save() : true;
    }

    /**
     * Enable a teacher by their ID.
     *
     * @param int $id
     * @return bool
     */
    public static function disabledTeacher(int $id): bool {
        $teacher = self::find($id);

        if (!$teacher) {
            return false;
        }

        $updated = $teacher->update(['available' => 0]);

        return $updated > 0;
    }

}
