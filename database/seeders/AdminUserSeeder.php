<?php

namespace Database\Seeders;

use App\Models\HRPerson;
use App\Models\OldPasswords;
use App\Models\PasswordHistory;
use App\Models\PasswordSecurity;
use Illuminate\Database\Seeder;
use App\Models\User;
use \App\Services\Modules\ModuleService;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User;
        $user->name = 'francois';
        $user->email = 'info@mkhayamk.co.za';
        $user->password = Hash::make('CharlesNgameni2035!@');
        $user->type = 5;
        $user->lockout_time = 25;
        $user->phone_number = '0638943843';
        $user->status = 1;
        $user->save();

        $person = new HRPerson();
        $person->first_name = 'Francois';
        $person->surname = 'keou';
        $person->initial = 'FC';
        $person->email = 'info@mkhayamk.co.za';
        $person->phone_number = '0638943843';
        $person->status = 1;
        $user->addPerson($person);

        PasswordHistory::createPassword($user->id, Hash::make('CharlesNgameni2035!@'));
        PasswordSecurity::addExpiryDate($user->id);

        //access level
        ModuleService::giveUserAccess($user->id, 1, 5);

        $role = Role::create(['name' => 'Admin']);
        $permissions = Permission::pluck('id', 'id')->all();
        $role->syncPermissions($permissions);
        $user->assignRole([$role->id]);
		
		####### renju
		$user = new User;
        $user->name = 'Renju';
        $user->email = 'renju@xpresserv.co.za';
        $user->password = Hash::make('XpresservAstra@1');
        $user->type = 5;
        $user->lockout_time = 25;
        $user->phone_number = '0641683882';
        $user->status = 1;
        $user->save();

        $person = new HRPerson();
        $person->first_name = 'Renju';
        $person->surname = 'Mathew';
        $person->initial = 'R';
        $person->email = 'renju@xpresserv.co.za';
        $person->phone_number = '0641683882';
        $person->status = 1;
        $user->addPerson($person);

        PasswordHistory::createPassword($user->id, Hash::make('XpresservAstra@1'));
        PasswordSecurity::addExpiryDate($user->id);

        //access level
        ModuleService::giveUserAccess($user->id, 1, 5);

        //$role = Role::create(['name' => 'Admin']);
        $permissions = Permission::pluck('id', 'id')->all();
        $role->syncPermissions($permissions);
        $user->assignRole([$role->id]);
	
		##### Shraaven
		$user = new User;
        $user->name = 'Shraveen';
        $user->email = 'shraveen@xpresserv.co.za';
        $user->password = Hash::make('XpresservAstra@2');
        $user->type = 5;
        $user->lockout_time = 25;
        $user->phone_number = '0634042750';
        $user->status = 1;
        $user->save();

        $person = new HRPerson();
        $person->first_name = 'Shraveen';
        $person->surname = 'Ramdhar';
        $person->initial = 'S';
        $person->email = 'shraveen@xpresserv.co.za';
        $person->phone_number = '0634042750';
        $person->status = 1;
        $user->addPerson($person);

        PasswordHistory::createPassword($user->id, Hash::make('XpresservAstra@2'));
        PasswordSecurity::addExpiryDate($user->id);

        //access level
        ModuleService::giveUserAccess($user->id, 1, 5);

        //$role = Role::create(['name' => 'Admin']);
        $permissions = Permission::pluck('id', 'id')->all();
        $role->syncPermissions($permissions);
        $user->assignRole([$role->id]);
		
        $user = new User;
        $user->name = 'Mkhaya';
        $user->email = 'support@mkhayamk.co.za';
        $user->password = Hash::make('tempassword!');
        $user->type = 5;
        $user->lockout_time = 25;
        $user->phone_number = '0638943842';
        $user->status = 1;
        $user->save();

        $person = new HRPerson();
        $person->first_name = 'Mkhaya';
        $person->initial = 'MS';
        $person->surname = 'Support';
        $person->email = 'support@mkhayamk.co.za';
        $person->status = 1;
        $person->phone_number = '0638943842';
        $user->addPerson($person);

        PasswordHistory::createPassword($user->id, Hash::make('tempassword!'));
        PasswordSecurity::addExpiryDate($user->id);

        $permissions = Permission::pluck('id', 'id')->all();
        $role->syncPermissions($permissions);
        $user->assignRole([$role->id]);

        $user = new User;
        $user->name = 'User001';
        $user->email = 'user@mkhayamk.co.za';
        $person->initial = 'U';
        $user->password = Hash::make('userPassword01');
        $user->type = 0;
        $user->status = 1;
        $user->save();

        $person = new HRPerson();
        $person->first_name = 'User001';
        $person->surname = 'User001';
        $person->email = 'user@mkhayamk.co.za';
        $person->status = 1;
        $user->addPerson($person);

        PasswordHistory::createPassword($user->id, Hash::make('userPassword01'));
        PasswordSecurity::addExpiryDate($user->id);

        $role = Role::create(['name' => 'User']);
        $permissions = Permission::pluck('id', 'id')->all();
        $role->syncPermissions($permissions);
        $user->assignRole([$role->id]);

    }
}
