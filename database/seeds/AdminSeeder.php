<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
	            'name' => 'Admin',
	            'role' => 'admin',
	            'email' => 'admin@email.com',
	            'password' => Hash::make('Welcome1'),
	            'created_at' => now(),
	            'updated_at' => now(),
        ]);
    }
}
