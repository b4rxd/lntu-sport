<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
    use HasFactory, Notifiable;

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
        'roles',
        'access_list',
        'location_access_list',
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
        'roles' => 'array',
        'access_list' => 'array',
        'location_access_list' => 'array',
        'is_verified' => 'boolean',
        'enabled' => 'boolean',
        'password' => 'hashed',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
