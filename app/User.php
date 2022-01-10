<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Permissions\HasPermissionsTrait;
use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable
{
    use Notifiable;
    use HasPermissionsTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    // protected $fillable = [
    //     'name', 'email','phone','gender','age', 'password',
    // ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

   

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designations_id');
    }

    public function message()
    {
        return $this->belongsTo('App\Message');
    }


    public function sendPasswordResetNotifiction($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

   
}
