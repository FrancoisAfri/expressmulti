<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;

class MenuType extends Model
{
    use HasFactory,
			Uuids,
        LogsActivity;

    protected $table = 'menu_types';
			
    protected $fillable = [
        'name', 'status', 'description', 'sequence'

    ];

    protected static $logName = 'Menu Types Details';

    protected function getDescriptionForEvent(string $eventName): string
    {
        return "Menu Types Details {$eventName} ";
    }
	// get all service type
	public static function getMenuTypes()
    {
        return MenuType::where('status',1)->orderBy('sequence','asc')->get();
	}
}
