<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Carbon;

class EventsServices extends Model
{
    use HasFactory,
			Uuids,
        LogsActivity;

    protected $table = 'events_services';

    protected $fillable = [
        'table_id', 'scan_id', 'service_type', 'requested_time',
        'status', 'completed_time', 'service', 'comment', 'item_id', 'waiter'

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
	public function waiters(): BelongsTo
    {
        return $this->belongsTo(HRPerson::class, 'waiter', 'id');
    }
	public function scans(): BelongsTo
    {
        return $this->belongsTo(TableScans::class, 'scan_id', 'id');
    }
	// get requests
	public static function getRequests()
    {
        return EventsServices::with('tables')
            ->where('status', 1)
			->orderBy('id', 'DESC')
            ->get();
    }
	// get waiter requests
	public static function getWaiterRequests($waiter)
    {
        return EventsServices::with('tables')
            ->where('status', 1)
            ->where('waiter', $waiter)
            ->get();
    }
	// get requests
	public static function getUserRequests($tableID, $can)
    {
        return EventsServices::where('table_id', $tableID)
            ->where('scan_id', $can)
            ->get();
    }
	// get requests reports 
	public static function getRequestsReports($startDate, $endDate)
    {
       
		$query = EventsServices::with('tables', 'waiters')
			->whereBetween('created_at', [$startDate, $endDate]);
			if (!empty($employee_id)) {
				$query->where('waiter', $employee_id);
			}
            
		 return  $query->get();
    }
	// get requests graphs 
	public static function getRequestsGraphs($startDate, $endDate, $employee_id)
    {
		
		$requests = EventsServices::select('completed_time', 'requested_time')
		->whereBetween('created_at', [$startDate, $endDate])
		->where('waiter',$employee_id)->where('status',2)->get();
		// get total numbers of transactions
		$totalTransactions = EventsServices::whereBetween('created_at', [$startDate, $endDate])
		->where('waiter',$employee_id)->where('status',2)->count();
		// get total minutes
		$totalMinutes = 0;
		foreach ($requests as $request) {
			$startTime = !empty($request->requested_time) ? $request->requested_time : 0;
			$endTime = !empty($request->completed_time) ? $request->completed_time : 0;

			$start = Carbon::parse($startTime);
			$end = Carbon::parse($endTime);

			$minute = $end->diffInMinutes($start);
			$totalMinutes = $totalMinutes + $minute;
        }

		if (!empty($totalTransactions) && !empty($totalMinutes))
			$avg = $totalMinutes / $totalTransactions;
		else $avg = 0;

		return $avg;
	}
	// get request per table
	// get requests graphs 
	public static function getRequestsPerTableGraphs($startDate, $endDate, $tableID)
    {
		
		$requests = EventsServices::select('completed_time', 'requested_time')
		->whereBetween('created_at', [$startDate, $endDate])
		->where('table_id',$tableID)->where('status',2)->get();
		// get total numbers of transactions
		$totalTransactions = EventsServices::whereBetween('created_at', [$startDate, $endDate])
		->where('table_id',$tableID)->where('status',2)->count();
		// get total minutes
		$totalMinutes = 0;
		foreach ($requests as $request) {
			$startTime = !empty($request->requested_time) ? $request->requested_time : 0;
			$endTime = !empty($request->completed_time) ? $request->completed_time : 0;

			$start = Carbon::parse($startTime);
			$end = Carbon::parse($endTime);

			$minute = $end->diffInMinutes($start);
			$totalMinutes = $totalMinutes + $minute;
        }

		if (!empty($totalTransactions) && !empty($totalMinutes))
			$avg = $totalMinutes / $totalTransactions;
		else $avg = 0;

		return $avg;
	}
	// get requests graphs 
	public static function getRestaurantGraphs($todayDate)
    {
		
		$requests = EventsServices::select('completed_time', 'requested_time')
		->whereDate('created_at', $todayDate)->where('status',2)->get();
		// get total numbers of transactions
		$totalTransactions = EventsServices::whereDate('created_at', $todayDate)
		->where('status',2)->count();
		// get total minutes
		$totalMinutes = 0;
		foreach ($requests as $request) {
			$startTime = !empty($request->requested_time) ? $request->requested_time : 0;
			$endTime = !empty($request->completed_time) ? $request->completed_time : 0;

			$start = Carbon::parse($startTime);
			$end = Carbon::parse($endTime);

			$minute = $end->diffInMinutes($start);
			$totalMinutes = $totalMinutes + $minute;
        }

		if (!empty($totalTransactions) && !empty($totalMinutes))
			$avg = $totalMinutes / $totalTransactions;
		else $avg = 0;

		return $avg;
	}
}
 

     