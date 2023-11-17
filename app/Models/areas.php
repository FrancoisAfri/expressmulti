<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\HasMany;

class areas extends Model
{
    use HasFactory,
			Uuids,
        LogsActivity;

    protected $table = 'areas';

    protected $fillable = [
        'name', 'status'

    ];

    protected static $logName = 'Areas  Details';

    protected function getDescriptionForEvent(string $eventName): string
    {
        return "Areas Details {$eventName} ";
    }

    /**
     * @return BelongsTo
     */
    public function tablesName(): HasMany
    {
        return $this->hasMany(Tables::class, 'area_id', 'id');
    }
}
