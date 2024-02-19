<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $module = modules::create([
            'active' => 1,
            'name' => 'Restaurant',
            'code_name' => 'restaurant',
            'path' => 'restaurant',
            'font_awesome' => 'fe-shopping-bag'
        ]);


        //compmay profile
        $ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 1;
        $ribbon->ribbon_name = 'Menu';
        $ribbon->description = 'Menu';
        $ribbon->ribbon_path = 'restaurant/menu';
        $ribbon->font_awesome = 'fe-message-square';
        $ribbon->access_level = 4;
        $module->addRibbon($ribbon);

        $ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 2;
        $ribbon->ribbon_name = 'Categories';
        $ribbon->description = 'Categories';
        $ribbon->ribbon_path = 'restaurant/category';
        $ribbon->font_awesome = 'fe-settings';
        $ribbon->access_level = 4;
        $module->addRibbon($ribbon);

        $ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 3;
        $ribbon->ribbon_name = 'Seating Plan';
        $ribbon->description = 'Seating Plan';
        $ribbon->ribbon_path = 'restaurant/seating_plan';
        $ribbon->font_awesome = 'fe-message-circle';
        $ribbon->access_level = 4;
        $module->addRibbon($ribbon);

        $ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 4;
        $ribbon->ribbon_name = 'Service Type';
        $ribbon->description = 'Service Type ';
        $ribbon->ribbon_path = 'restaurant/service_type';
        $ribbon->font_awesome = 'fe-heart-on';
        $ribbon->access_level = 4;
        $module->addRibbon($ribbon);

        $ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 5;
        $ribbon->ribbon_name = 'Menu Type';
        $ribbon->description = 'Menu Type';
        $ribbon->ribbon_path = 'restaurant/menu-type';
        $ribbon->font_awesome = 'fe-clipboard';
        $ribbon->access_level = 4;
        $module->addRibbon($ribbon);
		
		$ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 6;
        $ribbon->ribbon_name = 'Reports';
        $ribbon->description = 'Reports';
        $ribbon->ribbon_path = 'restaurant/reports';
        $ribbon->font_awesome = 'fe-briefcase';
        $ribbon->access_level = 4;
        $module->addRibbon($ribbon);
		
		$ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 7;
        $ribbon->ribbon_name = 'Limits';
        $ribbon->description = 'Limits';
        $ribbon->ribbon_path = 'restaurant/setup';
        $ribbon->font_awesome = 'fe-settings';
        $ribbon->access_level = 4;
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
