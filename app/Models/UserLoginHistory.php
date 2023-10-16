<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLoginHistory extends Model
{
    use HasFactory;

    public $table = 'user_login_history';

    //Mass assignable fields
    protected $fillable = [
        'userId',
        'email',
        'ip_address',
        'mac_address',
        'login_time',
    ];
}
