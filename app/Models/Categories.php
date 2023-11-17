<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;

class Categories extends Model
{
    use HasFactory,
			Uuids,
        LogsActivity;

    protected $table = 'categories';

    protected $fillable = [
        'name', 'status'

    ];

    protected static $logName = 'categories  Details';

    protected function getDescriptionForEvent(string $eventName): string
    {
        return "categories Details {$eventName} ";
    }

    /**
     * @return BelongsTo
     */
    public function tablesName(): HasMany
    {
        return $this->hasMany(Menu::class, 'category_id', 'id');
    }
	// get all categories
	public static function getCategories()
    {
        return Categories::get();
    }
}
