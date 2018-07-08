<?php

namespace Tests\Feature\Chats;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ChatsControllerTest extends TestCase
{
	// use RefreshDatabase;
    use DatabaseTransactions; 

    public function testShowChatsEnv(){

    	$rooms = factory(\App\Models\Rooms::class, 1)->create([
        	'room_name' => 'tea',
        ]);

        $uir = factory(\App\Models\UserInRooms::class, 1)->create([
        	'rooms_id' => $rooms[0]->id,
        ]);

        $user = $rooms[0]->users[0];

        $response = $this->actingAs($user)->get('/');
        $response->assertStatus(200);
    }
}
