<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingNotification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'notifiable_type' . 'notifiable_id', 'notifiable_type',
        'notifiable_id', 'role_id', 'title', 'name',
        'email', 'message', 'url', 'read_at',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'id');
    }


    public static function getAllUnreadNotifications()
    {
        return BookingNotification::where('read_at', null)->paginate(10);
    }


    public static function countNotifications(){
        return count(BookingNotification::getAllUnreadNotifications());
    }
}
