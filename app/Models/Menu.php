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
        'menu_docs', 'category_id', 'menu_type', 'status'

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
	
	// get all menus
	public static function getMenus()
    {
        return Menu::get();
    }
}
