<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
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
        'menu_docs', 'category_id', 'menu_type', 'status', 'calories', 'price', 'sequence'

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
		$query = DB::table('menus')
            ->select('menus.*', 'menu_types.sequence as type_sequence', 'menu_types.name as type_name')
			->where('menus.status',1)
            ->leftJoin('menu_types', 'menus.menu_type', '=', 'menu_types.id');
			if ($type > 0) {
				$query->where('menus.menu_type', $type);
			}
			if ($categoty > 0) {
				$query->where('menus.category_id', $categoty);
			}
            $query->orderBy('menu_types.sequence', 'ASC');
            $query->orderBy('menus.sequence', 'ASC');
            //->get()
       //
	   return $query->get();
    }
	/// get all menu
	// get all menus
	public static function getAllMenus()
    {
		$query = Menu::orderBy('sequence','asc')->get();
       
	   return $query;	
    }
}
