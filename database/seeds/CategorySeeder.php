<?php

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$default = ['Technology', 'Laptop', 'Desktop', 'Mobile'];
    	foreach ($default as $value) {
    		DB::table('categories')->insert([
	            'category' => $value,
	            'created_at' => now(),
	            'updated_at' => now(),
	        ]);
    	}
    }
}
