<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;

class ServiceType extends Model
{
    use HasFactory,
			Uuids,
        LogsActivity;

    protected $table = 'service_types';
			
    protected $fillable = [
        'name', 'status', 'image', 'turn_around_time'

    ];

    protected static $logName = 'ServiceTypes Details';

    protected function getDescriptionForEvent(string $eventName): string
    {
        return "ServiceTypes Details {$eventName} ";
    }
	// get all service type
	public static function getServices()
    {
        return ServiceType::get();
    }
}
