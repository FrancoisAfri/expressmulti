<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;

class Orders extends Model
{
    use HasFactory,
			Uuids,
        LogsActivity;
	
	protected $table = 'orders';

    protected $fillable = [
        'order_no', 'comment', 'status', 'table_id', 'scan_id'

    ];

    protected static $logName = 'Orders Details';

    protected function getDescriptionForEvent(string $eventName): string
    {
        return "Orders Details {$eventName} ";
    }
	
	// relationship between orders and order products
	public function products(): HasMany
    {
        return $this->hasMany(OrdersProducts::class, 'order_id', 'id');
    }
	// relationship between order and table	
	public function table(): BelongsTo
    {
        return $this->belongsTo(Tables::class, 'table_id', 'id');
    }
	// get all oders
	public static function getOrders()
    {
        return Orders::with('products','table')
					->get();
    }
	
	// get all order per ID
	public static function getOderById($id)
    {
        return Orders::with('products','table')
					->where(['id' => $id])
					->first();
    }
	
	// get all order per table and scanID
	public static function getOderByTable($table, $scan)
    {
        return Orders::with('products')
					->where(['table_id' => $table])
					->where(['scan_id' => $scan])
					->get();
    }
}
