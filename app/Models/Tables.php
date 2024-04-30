<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
	// relationship between table and table scan
	public function scans(): HasMany
    {
        return $this->hasMany(TableScans::class, 'table_id', 'id');
    }
	// get all tables
	public static function getTables()
    {
        return Tables::get();
    }
	
	// get table details
	public static function getTableDetails($id)
    {
        return Tables::with('employees')
            ->where('id', $id)
            ->first();
    }
	// get all tables with scans
	public static function getTablesScans()
    {
        return Tables::with('scans')->where('status',1)
				->get();
    }
	// get all tables per waiter
	public static function getTablesWaiter($waiter)
    {
        return Tables::where('employee_id',$waiter)
				->get();
    }
}
