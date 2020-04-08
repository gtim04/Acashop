<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	//setting array of permission
        $permissions = ['add user', 'add role', 'manage user', 'manage role'];
        
        //creating supadmin role
        $admin = Role::create(['name' => 'admin']);

        //setting array for the rest of default roles
        $roles = ['moderator', 'user'];
        
        //iteration
        foreach ($roles as $role) {
        	Role::create(['name' => $role]);
        }

        //iterating over and setting supadmin permissions
        foreach ($permissions as $value) {
        	$permission = Permission::create(['name' => $value]);
        	$admin->givePermissionTo($permission);
        }
    }
}
