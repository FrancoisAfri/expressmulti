<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\Traits\LogsActivity;

class OrdersProducts extends Model
{
    use HasFactory,
			Uuids,
        LogsActivity;
		
	protected $table = 'orders_products';

    protected $fillable = [
        'product_id', 'comment', 'status', 'table_id', 'order_id', 'price'
		, 'quantity', 'amount'

    ];

    protected static $logName = 'Orders Products Details';

    protected function getDescriptionForEvent(string $eventName): string
    {
        return "Orders Products Details {$eventName} ";
    }
	
	// relationship between order and order products	
	public function order(): BelongsTo
    {
        return $this->belongsTo(Orders::class, 'order_id', 'id');
    }
	// relationship between ordersProducts and products
	public function item(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'product_id', 'id');
    }
	// get daily incme
	public static function getDailySummary(){
        return OrdersProducts::where('status',2)
            ->whereDate(
                'created_at', Carbon::today()
            )
            ->get();
    }
	// get monthly income
	public static function getSummaryByMonth($month){
        return OrdersProducts::whereMonth(
            'created_at',$month
        )->where('status',2)->get();
    }
}
