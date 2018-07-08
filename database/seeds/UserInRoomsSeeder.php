<?php

use Illuminate\Database\Seeder;

class UserInRoomsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\UserInRooms::class, 32)->create();
    }
}
