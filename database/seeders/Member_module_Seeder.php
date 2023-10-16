<?php

namespace Database\Seeders;

use App\Models\module_access;
use App\Models\module_ribbons;
use App\Models\modules;
use Illuminate\Database\Seeder;

class Member_module_Seeder extends Seeder
{
    /**
     * Run the database seeds
     * @return void
     */
    public function run()
    {
        $module = modules::create([
            'active' => 1,
            'name' => 'Restaurant Management',
            'code_name' => 'clients_profile',
            'path' => 'clients',
            'font_awesome' => 'fe-heart-on'
        ]);


        //compmay profile
        $ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 1;
        $ribbon->ribbon_name = 'Manage Profile';
        $ribbon->description = 'Client details';
        $ribbon->ribbon_path = 'clients/profile_management';
        $ribbon->font_awesome = 'fe-message-circle';
        $ribbon->access_level = 3;
        $module->addRibbon($ribbon);

        $ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 2;
        $ribbon->ribbon_name = 'Communications';
        $ribbon->description = 'Client communications';
        $ribbon->ribbon_path = 'clients/send-message';
        $ribbon->font_awesome = 'fe-message-square ';
        $ribbon->access_level = 3;
        $module->addRibbon($ribbon);

        $Access = new module_access();
        $Access->active = 1;
        $Access->module_id = $module->id;
        $Access->user_id = 2;
        $Access->access_level = 5;
        $Access->save();


        $Access = new module_access();
        $Access->active = 1;
        $Access->module_id = $module->id;
        $Access->user_id = 1;
        $Access->access_level = 5;
        $Access->save();

    }
}
