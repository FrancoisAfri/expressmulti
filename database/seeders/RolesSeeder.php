<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use PhpParser\Node\Expr\Array_;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $roles = array('Billing Manager','Reception','Practice Manager');
            foreach ($roles as $key => $user_roles){
//                $user = User::find(2);
                $role = Role::create(['name' => $user_roles]);
                $permissions = Permission::pluck('id', 'id')->all();
                $role->syncPermissions($permissions);
        }
    }
}
