<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordSecurity extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function addExpiryDate($user){
       return PasswordSecurity::create([
            'user_id' => $user,
            'password_expiry_days' => 60,
            'password_updated_at' => Carbon::now(),
        ]);
    }
}
