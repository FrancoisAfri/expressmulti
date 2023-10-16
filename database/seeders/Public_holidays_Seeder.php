<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Public_holidays_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //insert public Holidays
        DB::table('public_holidays')->insert([
            'day' => 1482789600,
            'country_id' => 197,
            'year' => 0,
            'name' => 'Public Holiday',
        ]);
        DB::table('public_holidays')->insert([
            'day' => 1293228000,
            'country_id' => 197,
            'year' => 0,
            'name' => 'Christmas Day',
        ]);
        DB::table('public_holidays')->insert([
            'day' => 1285279200,
            'country_id' => 197,
            'year' => 0,
            'name' => 'Heritage Day',
        ]);
        DB::table('public_holidays')->insert([
            'day' => 1293314400,
            'country_id' => 197,
            'year' => 0,
            'name' => 'Day of Goodwill',
        ]);
        DB::table('public_holidays')->insert([
            'day' => 1269122400,
            'country_id' => 197,
            'year' => 0,
            'name' => 'Human Rights Day',
        ]);
        DB::table('public_holidays')->insert([
            'day' => 1272319200,
            'country_id' => 197,
            'year' => 0,
            'name' => 'Freedom Day',
        ]);
        DB::table('public_holidays')->insert([
            'day' => 1272664800,
            'country_id' => 197,
            'year' => 0,
            'name' => 'Workers Day',
        ]);
        DB::table('public_holidays')->insert([
            'day' => 1276639200,
            'country_id' => 197,
            'year' => 0,
            'name' => 'Youth Day',
        ]);
        DB::table('public_holidays')->insert([
            'day' => 1281304800,
            'country_id' => 197,
            'year' => 0,
            'name' => "National Women's Day",
        ]);
        DB::table('public_holidays')->insert([
            'day' => 1292450400,
            'country_id' => 197,
            'year' => 0,
            'name' => 'Day of Reconciliation',
        ]);
        DB::table('public_holidays')->insert([
            'day' => 1262296800,
            'country_id' => 197,
            'year' => 0,
            'name' => "New Year's Day",
        ]);
        DB::table('public_holidays')->insert([
            'day' => 1399413600,
            'country_id' => 197,
            'year' => 2014,
            'name' => 'Voting Day',
        ]);
    }
}
