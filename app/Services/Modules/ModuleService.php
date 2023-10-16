<?php

namespace App\Services\Modules;

use App\Models\module_access;
use App\Models\module_ribbons;
use App\Models\modules;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\ModuleRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ModuleService
{

    /*
     * Create new module Request
     */
    public function createModule(Request $request): modules
    {
        return modules::create([
            'active' => 1,
            'code_name' => $request['code_name'],
            'font_awesome' => $request['font_awesome'],
            'name' => $request['name'],
            'path' => $request['path'],
        ]);
    }

    public function editModule(Request $request, $module)
    {

        $Module = modules::find($module);
        $Module->update($request->all());
        return $Module;
    }

    public function ActivateModule(modules $mod)
    {
        $mod['active'] == 1 ? $status = 0 : $status = 1;
        $mod['active'] = $status;
        $mod->update();

    }


    public function createRibbon(Request $request)
    {

        return module_ribbons::create([
            'active' => 1,
            'module_id' => $request['module_id'],
            'sort_order' => $request['sort_order'],
            'ribbon_name' => $request['ribbon_name'],
            'ribbon_path' => $request['ribbon_path'],
            'access_level' => $request['access_level'],
            'description' => $request['description'],
            'font_awesome' => $request['font_awesome'],
        ]);

    }

    public function editRibbon(Request $request, $ribbon)
    {

        $Ribbon = module_ribbons::find($ribbon);
        $Ribbon->update($request->all());
        return $Ribbon;
    }


    public function ActivateRibbon(module_ribbons $ribbon)
    {
        $ribbon['active'] == 1 ? $status = 0 : $status = 1;
        $ribbon['active'] = $status;
        $ribbon->update();

    }

    public function getAllActiveUsers()
    {
        return User::getAllActiveUsers();
    }

    /**
     * @return Collection
     */
    public function getAllUsersByModuleAccess()
    {
        $name =  DB::table('hr_people')
            ->select('hr_people.*', 'hr_people.user_id as uid',
                'security_module_access.access_level')
            ->leftJoin('security_module_access',
                'hr_people.user_id', '=',
                'security_module_access.user_id')
            ->orderBy('hr_people.first_name', 'asc')
            //->limit(15)
            ->get();

       return $unique = $name->unique();

    }

    /**
     * @param $request
     * @return void
     */
    public function persistUserAccessRights($request)
    {
        $moduleID = $request->input('module_id');
        $accessLevels = $request->input('access_level');

        if (count($accessLevels) > 0) {
            foreach ($accessLevels as $userID => $accessLevel) {
				// delete current rights
				module_access::where('module_id', $moduleID)->where('user_id', $userID)->delete();
                // save rights in the database
				$access = new module_access();
                $access->user_id = $userID;
                $access->module_id = $moduleID;
                $access->access_level = $accessLevel;
                $access->save();
            }
        }
    }

    public static function giveUserAccess($userID, $moduleID, $accessLevel){
		// delete current rights
		module_access::where('module_id', $moduleID)->where('user_id', $userID)->delete();
		// save rights in the database
		$access = new module_access();
		$access->user_id = $userID;
		$access->module_id = $moduleID;
		$access->access_level = $accessLevel;
		$access->save();
    }
}
