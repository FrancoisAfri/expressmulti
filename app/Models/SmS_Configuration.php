<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class SmS_Configuration extends Model
{
    use HasFactory , LogsActivity;

    public $table = 'contacts_setup';

    // Mass assignable fields
    protected $fillable = [
        'sms_provider', 'sms_username', 'sms_password'];

    protected static $logName = 'Communication';

    protected static $logAttributes = ['*'];

    protected static $logAttributesToIgnore = [ 'sms_password'];

    protected static $logOnlyDirty = true;

    protected static $submitEmptyLogs = false;

    protected function getDescriptionForEvent(string $eventName):string
    {
        return "Communication {$eventName} ";
    }

}
