<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Rooms::class, function (Faker $faker) {
    return [
        'room_name' => $faker->unique()->domainWord,
    ];
});
