<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;

class EventsServices extends Model
{
    use HasFactory,
			Uuids,
        LogsActivity;

    protected $table = 'events_services';

    protected $fillable = [
        'table_id', 'scan_id', 'service_type', 'requested_time',
        'status', 'completed_time', 'service', 'comment', 'item_id'

    ];
	/**
     * status constants
     */
    const SERVICES_SELECT = [
        1 => 'Service Request',
        2 => 'Order Request',
        3 => 'Close Request',
    ];
	
    protected static $logName = 'Events Services Details';

    protected function getDescriptionForEvent(string $eventName): string
    {
        return "Events Services Details {$eventName} ";
    }
	
	public function tables(): BelongsTo
    {
        return $this->belongsTo(Tables::class, 'table_id', 'id');
    }
	// get requests
	public static function getRequests()
    {
        return EventsServices::with('tables')
            ->where('status', 1)
            ->get();
    }
	// get requests
	public static function getUserRequests($tableID, $can)
    {
        return EventsServices::where('table_id', $tableID)
            ->where('scan_id', $can)
            ->get();
    }
}
