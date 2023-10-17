<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Notifications\ResetPasswordNotification;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;

class Packages extends Model
{
    use HasFactory,
		Uuids,
        Notifiable,
        LogsActivity;
	protected $table = 'packages';
	
	protected $fillable = [
        'package_name', 'no_table', 'status'
    ];
	protected static $logName = 'Client Profile';

    protected function getDescriptionForEvent(string $eventName): string
    {
        return "Client Profile {$eventName} ";
    }
	// get all packages
	 public static function getPackages()
    {
        return Packages::get();
    }
}
