<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;

class TableScans extends Model
{
    use HasFactory,
			Uuids,
        LogsActivity;
	
	protected $table = 'table_scans';

    protected $fillable = [
        'ip_address', 'status', 'table_id'

    ];

    protected static $logName = 'Table Scans Details';

    protected function getDescriptionForEvent(string $eventName): string
    {
        return "Tables Scans Details {$eventName} ";
    }

    /**
     * @return BelongsTo
     */
    public function table(): BelongsTo
    {
        return $this->belongsTo(Tables::class, 'table_id', 'id');
    }
}
