<?php

namespace Database\Seeders;

use App\Models\serviceProvider;
use Illuminate\Database\Seeder;

class ServiceProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        serviceProvider::create([
            'name' => 'Procedure',
            'status' => 1
        ]);


        serviceProvider::create([
            'name' => 'Modifier',
            'status' => 1
        ]);

        serviceProvider::create([
            'name' => 'Medicine',
            'status' => 1
        ]);

        serviceProvider::create([
            'name' => 'Consumables',
            'status' => 1
        ]);
    }
}
