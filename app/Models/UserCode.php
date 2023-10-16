<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCode extends Model
{
    use HasFactory;

    public $table = "user_codes";

    protected $fillable = [
        'user_id','code','expire_at'
    ];


    /**
     * @param $code
     * @return mixed
     */
    public static function getOtpCode($code){
        return UserCode::where('code', $code)
            ->first();
    }



    public static function getCode($userId){
        return UserCode::where('user_id', $userId)
            ->where('updated_at', '>=', now()->subMinutes(15))
            ->first();
    }

}
