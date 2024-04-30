<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;

class OrdersServices extends Model
{
    use HasFactory,
			Uuids,
        LogsActivity;
		
	protected $table = 'orders_services';

    protected $fillable = [
        'service_id', 'comment', 'table_id', 'status', 'scan_id'

    ];

    protected static $logName = 'Orders Services Details';

    protected function getDescriptionForEvent(string $eventName): string
    {
        return "Orders Services Details {$eventName} ";
    }
	
	// relationship between order and order products	
	public function services(): BelongsTo
    {
        return $this->belongsTo(ServiceType::class, 'service_id', 'id');
    }
	// relationship between Tables and order products	
	public function tables(): BelongsTo
    {
        return $this->belongsTo(Tables::class, 'table_id', 'id');
    }
	
		// get all history
	public static function getAllRequest()
    {
        return OrdersServices::with('tables','services')->where('status',1)
					->get();
    }
	// get all services order per table and scanID
	public static function getServicesByTable($table, $scan)
    {
        return OrdersServices::with('services')
					->where(['table_id' => $table])
					->where(['scan_id' => $scan])
					->get();
    }
	// most popular services requests
	public static function mostPopularServices($startDate, $endDate, $serviceID)
    {
		$totalTransactions = OrdersServices::whereBetween('created_at', [$startDate, $endDate])
		->where('service_id',$serviceID)->where('status',2)->count();

		return $totalTransactions;
    }
}
