<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class PasswordHistory extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function post()
    {
        return $this->belongsTo(User::class);
    }

    public static function createPassword($user , $password){
       return PasswordHistory::create([
            'user_id' => $user,
            'password' => $password,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
