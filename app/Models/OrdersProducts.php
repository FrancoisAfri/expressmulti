<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\DB;

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
        return OrdersProducts::whereMonth('created_at',$month)
				->where('status',2)->get();
    }
	//
	public static function totalPaidThisMonth($year,$month)
    {

        $totalPaid =  OrdersProducts::whereYear('created_at', '=', $year)
            ->whereMonth('created_at', '=', $month)
            ->where('status', 2)
            ->get();

        if (isset($totalPaid))
            return $totalPaid->sum('amount');
        else return 0;

    }
	public static function totalPaidThisYear($year)
    {

        $totalPaid =  OrdersProducts::whereYear('created_at', '=', $year)
			->where('status', 2)
            ->get();

        if (isset($totalPaid))
            return $totalPaid->sum('amount');
        else return 0;

    }
	
	// Incomplete order
	public static function totalUnpaidThisMonth($year,$month)
    {

        $totalPaid =  OrdersProducts::whereYear('created_at', '=', $year)
            ->whereMonth('created_at', '=', $month)
			->where('status', 1)
            ->get();

        if (isset($totalPaid))
            return $totalPaid->sum('amount');
        else return 0;

    }
	public static function totalUnpaidThisYear($year)
    {

        $totalPaid =  OrdersProducts::whereYear('created_at', '=', $year)
            ->where('status', 1)
            ->get();

        if (isset($totalPaid))
            return $totalPaid->sum('amount');
        else return 0;

    }
	
	// get sum amount per orders
	public static function totalAmountOrder($id)
    {

        $totalAmount =  OrdersProducts::where('order_id', $id)->get();

        if (isset($totalAmount))
            return $totalAmount->sum('amount');
        else return 0;

    }
	// get most sold items
	public static function popularDishes()
    {

        $query = OrdersProducts::with('item')->select('product_id', DB::raw('SUM(quantity) as total_sold'))
                ->groupBy('product_id')
                ->orderByDesc('total_sold')
                ->take(30);
       
		 return  $query->get();

    }
}
