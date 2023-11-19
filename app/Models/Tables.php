<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;

class Tables extends Model
{
    use HasFactory,
			Uuids,
        LogsActivity;

    protected $table = 'tables';

    protected $fillable = [
        'name', 'qr_code', 'area_id', 'number_customer',
        'status', 'employee_id'

    ];

    protected static $logName = 'Tables Details';

    protected function getDescriptionForEvent(string $eventName): string
    {
        return "Tables Details {$eventName} ";
    }

    /**
     * @return BelongsTo
     */
    public function areaName(): BelongsTo
    {
        return $this->belongsTo(areas::class, 'area_id', 'id');
    }
	
	public function employees(): BelongsTo
    {
        return $this->belongsTo(HRPerson::class, 'employee_id', 'id');
    }
	// get all tables
	public static function getTables()
    {
        return Tables::get();
    }
}
