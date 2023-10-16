<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'type', 'notifiable_type', 'notifiable_id', 'data', 'read_at','name', 'email','role_id','title'
    ];

    public static function getAllUnreadNotifications()
    {
        return Notification::where('read_at', null)->paginate(10);
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function getUnreadNotificationsByUser($id)
    {
        return Notification::where('notifiable_id', $id)
            ->where('read_at', null)
            ->get();
    }

    /**
     * @return int
     */
    public static function countNotifications(){
        return count(Notification::getAllUnreadNotifications());
    }
}
