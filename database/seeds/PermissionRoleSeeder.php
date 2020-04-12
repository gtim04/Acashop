<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	//setting array of default permission
        $permissions = ['addedit product', 'delete product', 'add user', 'add role', 'manage admin', 'manage user', 'manage role', 'view admin', 'view main', 'order product'];

        //setting array for default roles
        $roles = ['admin', 'moderator', 'manager', 'user'];

        //creating supadmin role
        $supadmin = User::create([
                    'name' => 'Owner',
                    'email' => 'owner@email.com',
                    'password' => Hash::make('Welcome1'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

        //iteration
        foreach ($roles as $role) {
        	Role::create(['name' => $role]);
        }

        //iterating over and setting supadmin permissions
        foreach ($permissions as $value) {
        	$permission = Permission::create(['name' => $value]);
        	$supadmin->givePermissionTo($permission);
        }

        //giving user permission to view the main page
        Role::findByName('user')->givePermissionTo('view main' , 'order product');
    }
}