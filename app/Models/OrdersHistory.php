<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;

class OrdersHistory extends Model
{
    use HasFactory,
			Uuids,
        LogsActivity;
		
	protected $table = 'orders_histories';

    protected $fillable = [
        'action', 'comment', 'table_id', 'order_id'

    ];

    protected static $logName = 'Orders Histories Details';

    protected function getDescriptionForEvent(string $eventName): string
    {
        return "Orders Histories Details {$eventName} ";
    }
	
	// relationship between order and order products	
	public function order(): BelongsTo
    {
        return $this->belongsTo(Orders::class, 'order_id', 'id');
    }
	// relationship between order and order products	
	public function tables(): BelongsTo
    {
        return $this->belongsTo(Tables::class, 'table_id', 'id');
    }
	
		// get all history
	public static function getAllHistory()
    {
        return OrdersHistory::with('tables','order')
					->get();
    }
	// get history by table
	// get all order per ID
	public static function getHistoryBytableId($id)
    {
        return OrdersHistory::with('order','tables')
					->where(['table_id' => $id])
					->get();
    }
}
