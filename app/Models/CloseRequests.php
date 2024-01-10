<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;

class CloseRequests extends Model
{
    use HasFactory,
			Uuids,
        LogsActivity;
	
	protected $table = 'close_requests';

    protected $fillable = [
        'table_id', 'status', 'scan_id'

    ];

    protected static $logName = 'Close Requests Details';

    protected function getDescriptionForEvent(string $eventName): string
    {
        return "Close Requests Details {$eventName} ";
    }
}
