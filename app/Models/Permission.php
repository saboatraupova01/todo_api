<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\TaskStatus;

class Permission extends Model
{

    protected $fillable = [
        'name',
        'code',
        'description',
    ];
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
