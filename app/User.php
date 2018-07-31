<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_code', 'name', 'mobile', 'email', 'password', 'user_role', 'remember_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function userRole($role) 
    {
        return static::where('user_role', $role);
    }

    public static function gicStaffs() 
    {
        return static::where('user_role', '!=', 'client')->get();
    }

}
