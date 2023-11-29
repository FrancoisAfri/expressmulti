<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;

class WaiterAudits extends Model
{
    use HasFactory,
			Uuids,
        LogsActivity;
		
	protected $table = 'waiter_audits';
			
    protected $fillable = [
        'table_id', 'employee_id', 'user_id', 'comment'

    ];

    protected static $logName = 'Waiter Audits Details';

    protected function getDescriptionForEvent(string $eventName): string
    {
        return "Waiter Audits Details {$eventName} ";
    }
	// relationships
	
	public function employees()
    {
        return $this->belongsTo(HRPerson::class, 'employee_id');
    }
	public function users()
    {
        return $this->belongsTo(HRPerson::class, 'user_id');
    }
	public function tables()
    {
        return $this->belongsTo(Tables::class, 'table_id');
    }
	
	// get all service type
	public static function getAllAudits()
    {
        return WaiterAudits::::with('employees','users','tables')
								->get();
}
