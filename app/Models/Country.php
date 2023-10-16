<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Country extends Model
{
    use HasFactory, Uuids, LogsActivity;

    public $table = 'countries';

    protected $fillable = [
        'iso', 'name', 'a2_code', 'a3_code', 'iso3', 'numcode', 'phonecode', 'abbreviation'
    ];


    protected function getDescriptionForEvent(string $eventName): string
    {
        return "Country {$eventName} ";
    }

    //Define Country - Province relationship
    public function province()
    {
        return $this->hasMany(Province::class, 'country_id');
    }

    //Function to save Country's hr Province
    public function addProvince(Province $province)
    {
        return $this->province()->save($province);
    }

    public static function getAllCountriesByName()
    {
        return Country::orderBy('name')->get();
    }

    public static function getAllCountries()
    {
        return Country::select(
            'id',
            'name',
            'iso3'
        )->paginate(300);
    }
}
