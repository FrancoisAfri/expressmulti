<?php

namespace Database\Seeders;

use App\Models\module_access;
use App\Models\module_ribbons;
use App\Models\modules;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
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
            'name' => 'Settings',
            'code_name' => 'Settings',
            'path' => 'users',
            'font_awesome' => 'fe-lock'
        ]);
        $ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 2;
        $ribbon->ribbon_name = 'Setup';
        $ribbon->description = 'Setup';
        $ribbon->ribbon_path = 'users/setup';
        $ribbon->font_awesome = 'fe-settings';
        $ribbon->access_level = 5;
        $module->addRibbon($ribbon);

        $ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 2;
        $ribbon->ribbon_name = 'Edit company Details';
        $ribbon->description = 'edit_company_details';
        $ribbon->ribbon_path = 'users/company_details';
        $ribbon->font_awesome = 'fe-settings';
        $ribbon->access_level = 5;
        $module->addRibbon($ribbon);

        $ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 3;
        $ribbon->ribbon_name = 'Users Access';
        $ribbon->description = 'Users Access';
        $ribbon->ribbon_path = 'users/users-access';
        $ribbon->font_awesome = 'fe-sunrise';
        $ribbon->access_level = 5;
        $module->addRibbon($ribbon);

        $ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 4;
        $ribbon->ribbon_name = 'Manage Users';
        $ribbon->description = 'manage users';
        $ribbon->ribbon_path = 'users/manage';
        $ribbon->font_awesome = 'fe-users';
        $ribbon->access_level = 5;
        $module->addRibbon($ribbon);

        $ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 5;
        $ribbon->ribbon_name = 'Audits';
        $ribbon->description = 'audits';
        $ribbon->ribbon_path = 'users/audits';
        $ribbon->font_awesome = 'fe-chevrons-up';
        $ribbon->access_level = 5;
        $module->addRibbon($ribbon);

        $Access = new module_access();
        $Access->active = 1;
        $Access->module_id = $module->id;
        $Access->user_id = 2;
        $Access->access_level = 5;
        $Access->save();
    }
}
