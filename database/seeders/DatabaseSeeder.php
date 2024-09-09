<?php

namespace Database\Seeders;

use App\Http\Controllers\Modules\ModuleController;
use Illuminate\Database\Seeder;
use const http\Client\Curl\Versions\CURL;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//         \App\Models\User::factory(10)->create();
        $this->call(PermissionTableSeeder::class);
        $this->call(AdminUserSeeder::class);
        $this->call(ModuleSeeder::class);
        $this->call(Public_holidays_Seeder::class);
        $this->call(Provinces_Seeder::class);
        $this->call(RestaurantSeeder::class);
        $this->call(CountriesTableSeeder::class);
        $this->call(RolesSeeder::class);
        $this->call(AdvancedSettingsSeeder::class);

    }
}
