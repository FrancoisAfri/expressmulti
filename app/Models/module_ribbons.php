<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Spatie\Activitylog\Traits\LogsActivity;

class module_ribbons extends Model
{
    use HasFactory, Uuids , LogsActivity;

    protected $table = 'security_module_ribbons';
    // Mass assignable fields
    protected $fillable = [
        'module_id', 'sort_order', 'ribbon_name','font_awesome', 'ribbon_path', 'description', 'access_level', 'active'
    ];

    //Relationship modules and ribbons
    public function ribbons()
    {
        return $this->belongsTo(modules::class, 'module_id');
    }


    protected static $logAttributes = [ 'module_id', 'ribbon_name', 'ribbon_path', 'description', 'access_level'];

    protected static $recordEvents = ['created', 'updated'];

    protected static $logName = 'Security Module Ribbon';


    /**
     * @param string $eventName
     * @return string
     */
    public function getDescriptionForEvent(string $eventName): string
    {
        return "Security Module Ribbon  {$eventName} ";
    }

    public static function userRights(): array
    {

        return array(
            0 => 'None',
            1 => 'Read',
            2 => 'Write',
            3 => 'Modify',
            4 => 'Admin',
            5 => 'SuperUser'
        );
    }

    public static function getAllModuleRibbons($id)
    {
        return module_ribbons::where(
            [
                'module_id' => $id
            ]
        )->orderBy(
            'ribbon_name', 'asc'
        )->get();
    }


}
