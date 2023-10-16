<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Spatie\Activitylog\Traits\LogsActivity;

class modules extends Model
{
    use HasFactory, Uuids , LogsActivity;

    protected $table = 'security_modules';

    protected $fillable = [
        'name', 'path', 'active', 'font_awesome', 'code_name'
    ];


    protected static $logName = 'Modules';


    protected static $logAttributes = ['name', 'path', 'active', 'code_name'];

    protected static $ignoreChangedAttributes = ['updated_at'];

    protected static $recordEvents = ['created', 'updated'];

    protected function getDescriptionForEvent(string $eventName):string
    {
        return "Module {$eventName} ";
    }


    //Relationship module and ribbon
    public function moduleRibbon() {
        return $this->hasmany(module_ribbons::class, 'module_id');
    }
    //Relationship module and access
    public function moduleAccess() {
        return $this->hasone(module_access::class, 'module_id');
    }
    //Function to save module's ribbon
    public function addRibbon(module_ribbons $ribbon) {
        return $this->moduleRibbon()->save($ribbon);
    }

    //Function to save module access
    public function addModuleAccess(module_access $module) {
        return $this->moduleAccess()->save($module);
    }

    public static function getAllModules(): Collection
    {
        return modules::orderBy('id', 'asc')->get();

    }

    public static function getAllModulesOrderedByName(){
        return modules::orderBy('name', 'asc')->get();
    }

    public static function getModuleByUuid($id){
      return  modules::where(
            [
                'uuid' => $id
            ]
        )->first();
    }
}
