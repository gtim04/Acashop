<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence($nbWords = 3, $variableNbWords = true),
        'price' => $faker->randomNumber($nbDigits = 3, $strict = false),
        'stock' => $faker->randomDigit,
        'description' => $faker->sentence($nbWords = 10, $variableNbWords = true), // password
        'image' => '1ASC.png',
        'user_id' => factory(App\User::class)->create(),
    ];
});
