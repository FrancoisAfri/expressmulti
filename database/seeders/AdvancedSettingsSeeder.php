<?php

namespace Database\Seeders;

use App\Models\module_access;
use App\Models\module_ribbons;
use App\Models\modules;
use Illuminate\Database\Seeder;

class AdvancedSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $module =   modules::create([
            'active' => 1,
            'name' => 'Advanced Settings',
            'code_name' => 'advanced_Settings',
            'path' => 'users',
            'font_awesome' => 'fe-lock'
        ]);

        $ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 1;
        $ribbon->ribbon_name = 'Modules';
        $ribbon->description = 'Modules';
        $ribbon->ribbon_path = 'users/module';
        $ribbon->font_awesome = 'fe-command';
        $ribbon->access_level = 5;
        $module->addRibbon($ribbon);
		
		$ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 2;
        $ribbon->ribbon_name = 'Clients';
        $ribbon->description = 'Clients';
        $ribbon->ribbon_path = 'clients/profile_management';
        $ribbon->font_awesome = 'fe-list';
        $ribbon->access_level = 5;
        $module->addRibbon($ribbon);
		
		$ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 3;
        $ribbon->ribbon_name = 'Clients Approval';
        $ribbon->description = 'Clients Approval';
        $ribbon->ribbon_path = 'clients/approvals';
        $ribbon->font_awesome = 'fe-user-check';
        $ribbon->access_level = 5;
        $module->addRibbon($ribbon);
		
		$ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 4;
        $ribbon->ribbon_name = 'Packages';
        $ribbon->description = 'Packages';
        $ribbon->ribbon_path = 'clients/packages';
        $ribbon->font_awesome = 'fe-package';
        $ribbon->access_level = 5;
        $module->addRibbon($ribbon);

        $Access = new module_access();
        $Access->active = 1;
        $Access->module_id = $module->id;
        $Access->user_id = 1;
        $Access->access_level = 5;
        $Access->save(); 
		
		$Access = new module_access();
        $Access->active = 1;
        $Access->module_id = $module->id;
        $Access->user_id = 2;
        $Access->access_level = 5;
        $Access->save();
    }
}
