<?php

use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->firstname,
        'surname' => $faker->lastname,
        'nickname' => $faker->unique()->userName,
        'email' => $faker->unique()->safeEmail,
        'bio' => $faker->paragraph(3, true),
        'brief' => $faker->sentence(6, true),
        'mobile' => $faker->tollFreePhoneNumber,
        'hide_account' => $faker->boolean,
        'gender' => $faker->randomElement(['f', 'm']),
        'online' => $faker->randomElement(['0', '1', '2']),
        'language' => $faker->country,
        'date_of_birth' => $faker->unixTime,
        'country' => $faker->country,
        'province' => $faker->city,
        'password' => Hash::make(123),
        'remember_token' => str_random(10),
    ];
});
