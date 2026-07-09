<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Passport\HasApiTokens;
use App\Models\Task;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'user_role',
            'user_id',
            'role_id'
        );
    }
    public function hasRole(string $role): bool
    {
        return $this->roles()
            ->where('code', $role)
            ->exists();
    }

    public function hasPermission(string $permission): bool
    {
        $hasDirectPermission = $this->permissions()
            ->where('code', $permission)
            ->exists();

        if ($hasDirectPermission) {
            return true;
        }

        return $this->roles()
            ->whereHas('permissions', function ($query) use ($permission) {
                $query->where('code', $permission);
            })
            ->exists();
    }

    public function permissions()
    {
        return $this->belongsToMany(
            Permission::class,
            'user_permission'
        );
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }


}

