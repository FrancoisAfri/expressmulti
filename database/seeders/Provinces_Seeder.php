<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Province;
use Illuminate\Database\Seeder;

class Provinces_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //insert default country
        $country = new Country;
        $country->name = 'South Africa';
        $country->a2_code = 'ZA';
        $country->a3_code = 'ZAF';
        $country->numcode = 710;
        $country->phonecode = '27';
        $country->abbreviation = 'RSA';
        $country->save();

        //insert default country's provinces
        $province = new Province();
        $province->name = 'Eastern Cape';
        $country->addProvince($province);

        $province = new Province();
        $province->name = 'Free State';
        $country->addProvince($province);

        $province = new Province();
        $province->name = 'Gauteng';
        $province->abbreviation = 'GP';
        $country->addProvince($province);

        $province = new Province();
        $province->name = 'KwaZulu-Natal';
        $province->abbreviation = 'KZN';
        $country->addProvince($province);

        $province = new Province();
        $province->name = 'Limpopo';
        $country->addProvince($province);

        $province = new Province();
        $province->name = 'Mpumalanga';
        $country->addProvince($province);

        $province = new Province();
        $province->name = 'North West';
        $country->addProvince($province);
//
        $province = new Province();
        $province->name = 'Northern Cape';
        $country->addProvince($province);

        $province = new Province();
        $province->name = 'Western Cape';
        $country->addProvince($province);
    }
}
