<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class, 20)
        ->create()
        ->each(function ($u) {
	        $u->images()
	        ->save(
	        	factory(App\Models\Images::class)->create([
	        		'owner_id' => $u->id
	        	])
	        );
	    });
    }
}
