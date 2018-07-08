<?php

namespace Tests\Feature\Chats;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testShowProfile()
    {
    	$user = User::find(1);

        $response = $this->actingAs($user)->get('/profile');
        $response->assertStatus(200);
    }
}
