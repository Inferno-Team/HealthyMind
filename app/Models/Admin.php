<?php

namespace App\Models;

class Admin extends User
{


    public static function boot()
    {
        parent::boot();
        static::creating(function (User $user) {
            $user->forceFill(['type' => 'admin']);
        });
    }
}
