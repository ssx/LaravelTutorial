<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'user_roles';

    public function role()
    {
        return $this->hasOne(Role::class);
    }
}
