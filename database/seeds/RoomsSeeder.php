<?php

use Illuminate\Database\Seeder;

class RoomsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Rooms::class, 1)->create([
            'room_name' => 'tea',
        ]);

        factory(App\Models\Rooms::class, 1)->create([
            'room_name' => 'general',
        	'active' => '1',
        ]);

        factory(App\Models\Rooms::class, 1)->create([
        	'room_name' => 'lejyon',
        ]);

        factory(App\Models\Rooms::class, 1)->create([
        	'room_name' => 'Developers',
        ]);

        factory(App\Models\Rooms::class, 1)->create([
        	'room_name' => 'Computer Meeting',
        ]);

        factory(App\Models\Rooms::class, 4)->create();
    }
}
