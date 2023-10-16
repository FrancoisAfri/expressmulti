<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicHolidays extends Model
{
    use HasFactory;

    protected $table = 'public_holidays';
    protected $fillable = [
        'name', 'day', 'country_id', 'year'
    ];

    protected static $logAttributes = ['name', 'day', 'country_id', 'year'];

    protected static $recordEvents = ['created', 'updated'];

    protected static $logName = 'Public Holidays';


    /**
     * @param string $eventName
     * @return string
     */
    public function getDescriptionForEvent(string $eventName): string
    {
        return "Holiday {$eventName}  ";
    }

    public static function getAllHolidays()
    {
        return PublicHolidays::orderBy('name')->get();
    }


}
