<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;

class Cart extends Model
{
    use HasFactory,
			Uuids,
        LogsActivity;

    protected $table = 'carts';
			
    protected $fillable = [
        'table_id','scan_id', 'product_id', 'quantity', 'price',
        'status','comment'

    ];

    protected static $logName = 'Carts Details';

    protected function getDescriptionForEvent(string $eventName): string
    {
        return "Carts Details {$eventName} ";
    }

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'product_id', 'id');
    }
	
	
	// get all menus
	public static function getCart($tableID)
    {
		return Cart::with('product')->where('table_id',$tableID)->get();
       
		 	
    }
	
	public static function getQuantity($id, $table)
    {
		
		$cart = Cart::where('product_id', $id)
			->where('table_id', $table)
            ->first();
		if (!empty($cart->quantity))
			return $cart->quantity;
		else return 0;
    }
	
	public static function getComment($id, $table)
    {
		
		$cart = Cart::where('product_id', $id)
			->where('table_id', $table)
            ->first();
		if (!empty($cart->comment))
			return $cart->comment;
		else return '';
    }
}
