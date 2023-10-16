<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;

class ContactsCommunication extends Model
{
    use HasFactory,
        LogsActivity;

    protected $table = 'contacts_communication';

    protected $fillable = [
        'communication_type', 'company_id', 'message',
        'status', 'sent_by', 'communication_date',
    ];

    protected static $logName = 'ContactsCommunication  Details';

    protected function getDescriptionForEvent(string $eventName): string
    {
        return "ContactsCommunication Details {$eventName} ";
    }


}
