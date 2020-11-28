<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

//خليت الموديل extend  Authenticatable زى اليوزر علشان طبقت عليه الميدل وير
class Admin extends Authenticatable
{

    use Notifiable;
    protected $table='admins';
    protected $fillable = [
        'name', 'email','image','password', 'created_at','updated_at',
    ];


    protected $hidden = [
        'password', 'remember_token',
    ];


}
