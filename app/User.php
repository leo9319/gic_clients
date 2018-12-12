<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_code', 'name', 'mobile', 'email', 'password', 'user_role', 'status', 'remember_token'
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

    public function hasSpouse()
    {
        return $this->hasOne('App\ClientFileInfo', 'client_id')->where('spouse_name', '!=', '');
    }

    public function getAssignedRms()
    {
        return $this->hasMany('App\RmClient', 'client_id');
    }

    public function totalAmount()
    {
        return $this->hasMany('App\Payment', 'client_id');
    }

    public function getTotalAmountPaid()
    {
        // get the payment ids;

        $payment_ids = Payment::where('client_id', $this->id)->pluck('id');
        return PaymentType::whereIn('payment_id', $payment_ids)->sum('amount_paid');

    }

    public function payments()
    {
        return $this->hasMany('App\Payment', 'client_id');
    }

    public function getAdditionalInfo()
    {
        return $this->hasOne('App\ClientFileInfo', 'client_id');
    }

}
