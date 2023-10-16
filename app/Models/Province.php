<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory ,  Uuids;

    protected $table = 'provinces';

    protected $fillable = [
        'name', 'abbreviation'
    ];

    protected static $logAttributes = ['name', 'abbreviation'];

    protected static $recordEvents = ['created', 'updated'];

    protected static $logName = 'Provinces';


    /**
     * @param string $eventName
     * @return string
     */
    public function getDescriptionForEvent(string $eventName): string
    {
        return "Province {$eventName}  ";
    }

    //Province - Country relationship
    public function country() {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public static function getAllProvinces(){
        return Province::OrderBy('name', 'desc')->get();
    }
}
