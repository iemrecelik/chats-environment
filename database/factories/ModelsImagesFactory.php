<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Images::class, function (Faker $faker) {
    return [
        'path' => $faker->regexify('^\/img\/p[1-4]{1}\.jpg$'),
    ];
});
