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
        'ip_address', 'status', 'table_id', 'nickname', 'scan_time', 'closed_time'
		, 'comment', 'q_four', 'q_three', 'q_two', 'q_one', 'waiter'

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
	public function waiters(): BelongsTo
    {
        return $this->belongsTo(HRPerson::class, 'waiter', 'id');
    } 
	
	//getTableStatus
	
	public static function getTableStatus($id)
    {
		
		$scan = TableScans::where('table_id', $id)
			->where('status', 1)
			->orderBy('id', 'desc')
            ->first();
		if (!empty($scan->status) && $scan->status == 1)
			return 1;
		else return 0;
    }
	// get table name
	public static function getTableName($id)
    {
		
		$scan = TableScans::where('table_id', $id)
			->where('status', 1)
			->orderBy('id', 'desc')
            ->first();
		if (!empty($scan->nickname))
			return $scan->nickname;
		else return '';
    }
	// get reports
	public static function getReports($startDate, $endDate)
    {
		
		$query = TableScans::with('table', 'waiters')
			->whereBetween('created_at', [$startDate, $endDate])
			->whereNotNull('q_one');
            
		 return  $query->get();
    }
	// get usage reports
	public static function getUsageReports($todayDate)
    {
		
		$totalTransactions = TableScans::whereDate('created_at', $todayDate)
		->where('status',2)->count();
            
		 return  $totalTransactions;
    }
	// get usage reports
	public static function getRatingsQoneReports($i,$startDate, $endDate)
    {
		
		$totalTransactions = TableScans::whereBetween('created_at', [$startDate, $endDate])
		->where('q_one', $i)
		->where('status',2)
		->count();
            
		 return  $totalTransactions;
    }
	// get usage reports
	public static function getRatingsQtwoReports($i,$startDate, $endDate)
    {
		
		$totalTransactions = TableScans::whereBetween('created_at', [$startDate, $endDate])
		->where('q_two', $i)
		->where('status',2)
		->count();
            
		 return  $totalTransactions;
    }
	// get usage reports
	public static function getRatingsQthreeReports($i,$startDate, $endDate)
    {
		
		$totalTransactions = TableScans::whereBetween('created_at', [$startDate, $endDate])
		->where('q_three', $i)
		->where('status',2)
		->count();
            
		 return  $totalTransactions;
    }
	// get usage reports
	public static function getRatingsQfourReports($i,$startDate, $endDate)
    {
		
		$totalTransactions = TableScans::whereBetween('created_at', [$startDate, $endDate])
		->where('q_four', $i)
		->where('status',2)
		->count();
            
		 return  $totalTransactions;
    }
}
