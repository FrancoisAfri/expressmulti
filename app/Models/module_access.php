<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Traits\LogsActivity;

class module_access extends Model
{
    use HasFactory, Uuids, LogsActivity;

    protected $table = 'security_module_access';
    /*
   *  Mass assignable fields
   */
    protected $fillable = [
        'module_id', 'user_id', 'active', 'access_level'
    ];
    /*
     *  Relationship modules and ribbons
     */

    protected static $logAttributes = ['module_id', 'user_id', 'active', 'access_level'];

    protected static $recordEvents = ['created', 'updated'];

    protected static $logName = 'Security Module Access';


    /**
     * @param string $eventName
     * @return string
     */
    public function getDescriptionForEvent(string $eventName): string
    {
        return "Security Module Access {$eventName} ";
    }


    public function ribbons()
    {
        return $this->belongsTo(modules::class, 'module_id');
    }

    public function moduleAcess()
    {
        return $this->belongsTo(HRPerson::class, 'user_id');
    }

    public function getAdminUsers($id)
    {
        return DB::table('security_module_access')
            ->select('security_module_access.user_id')
            ->leftJoin('security_modules', 'security_module_access.module_id', '=', 'security_modules.id')
            ->where('security_modules.code_name', 'security')
            ->where('security_module_access.access_level', '>=', 4)
            ->where('security_module_access.user_id', $id)
            ->pluck('user_id')
            ->first();
    }

    public static function getAllModuleAccess()
    {
        return module_access::orderBy('id', 'asc')->get();
    }
}
