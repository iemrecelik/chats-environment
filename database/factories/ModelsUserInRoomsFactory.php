<?php

use Faker\Generator as Faker;

$factory->define(App\Models\UserInRooms::class, function (Faker $faker) {

	$roomsIds = DB::select('SELECT rm.id FROM rooms as rm');

    return [
        'user_id' => factory(App\User::class, 1)->create()
        ->each(function ($u) {
	        $u->images()
	        ->save(
	        	factory(App\Models\Images::class)->create([
	        		'owner_id' => $u->id
	        	])
	        );
	    })[0]->id,
        'rooms_id' => (array_rand($roomsIds, 1) + 1)
    ];
});