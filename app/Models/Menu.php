<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;

class Menu extends Model
{
    use HasFactory,
			Uuids,
        LogsActivity;

    protected $table = 'menus';
			
    protected $fillable = [
        'name','description', 'ingredients', 'image', 'video',
        'menu_docs', 'category_id', 'menu_type', 'status', 'calories', 'price'

    ];

    protected static $logName = 'Menu Details';

    protected function getDescriptionForEvent(string $eventName): string
    {
        return "Menu Details {$eventName} ";
    }

    /**
     * @return BelongsTo
     */
    public function categories(): BelongsTo
    {
        return $this->belongsTo(Categories::class, 'category_id', 'id');
    }
	
	public function menuType(): BelongsTo
    {
        return $this->belongsTo(MenuType::class, 'menu_type', 'id');
    }
	
	// get all menus per categories and menu type
	public static function getMenus($type, $categoty)
    {
		$query = Menu::where('status',1);
        // return only from asset type table if  selection from asset type
        if ($type > 0) {
            $query->where('menu_type', $type);
        } 
		if ($categoty > 0) {
            $query->where('category_id', $categoty);
        }
		$query->orderBy('menu_type','asc')->orderBy('category_id','asc');
       
	   return $query->get();
    }
	/// get all menu
	// get all menus
	public static function getAllMenus()
    {
		$query = Menu::get();
       
	   return $query;	
    }
}
