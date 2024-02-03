<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;

class RestaurantSetup extends Model
{
    use HasFactory,
			Uuids,
        LogsActivity;
	
	protected $table = 'restaurant_setup';

    protected $fillable = [
        'colour_one', 'colour_two', 'colour_three', 'mins_one', 'mins_two', 'mins_three'

    ];

    protected static $logName = 'Restaurant Setup Details';

    protected function getDescriptionForEvent(string $eventName): string
    {
        return "Restaurant Setup Details {$eventName} ";
    }
}
