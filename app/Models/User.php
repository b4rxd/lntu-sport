<?php

namespace App\Models;

use App\Enums\UserRole;
use App\Enums\Permission;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\Models\User
 *
 * @property string $id
 * @property string $email
 * @property string $first_name
 * @property string $last_name
 * @property string $roles
 * @property array $access_list
 * @property array $location_access_list
 * @property string|null $password
 * @property bool $is_verified
 * @property bool $enabled
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use HasUuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * UUID
     *
     * @var string
     */
    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'first_name',
        'last_name',
        'role',
        'access_list',
        'password',
        'is_verified',
        'enabled',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'access_list' => 'array',
        'is_verified' => 'boolean',
        'enabled' => 'boolean',
        'password' => 'hashed',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN->value;
    }

    public function hasPermission(Permission|string $permission): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        return in_array(
            $permission instanceof Permission ? $permission->value : $permission,
            $this->access_list ?? []
        );
    }

    public function hasAnyPermission(array $permissions): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        return count(array_intersect(
            collect($permissions)->map(fn ($p) => $p instanceof Permission ? $p->value : $p)->toArray(),
            $this->access_list ?? []
        )) > 0;
    }
}
