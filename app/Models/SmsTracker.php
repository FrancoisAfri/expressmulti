<?php

namespace App\Models;

use App\Notifications\SmsAddedNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Notifications\Notifiable;

class SmsTracker extends Model
{
    use HasFactory , LogsActivity , Notifiable;

    public $table = 'sms_tracker';

    // Mass assignable fields
    protected $fillable = ['sms_count', 'is_active'];

    protected static $logName = 'sms_tracker';

    protected static $logAttributes = ['*'];


    protected static $logOnlyDirty = true;

    protected static $submitEmptyLogs = false;

    protected function getDescriptionForEvent(string $eventName):string
    {
        return "sms_tracker {$eventName} ";
    }

    public function sendRegisterBookingNotification( $user='')
    {
        $this->notify(new SmsAddedNotification($user));
    }


}
