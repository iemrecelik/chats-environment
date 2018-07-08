<?php

namespace Tests\Browser;

use App\Models\Rooms;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function test_should_login()
    {
        $user = User::find(1);

        $this->browse(function (Browser $browser) use ($user) {

            $fullName = $user->name.' '.$user->surname;

            $browser->visit('/login')
                    ->assertTitle('Chats Application')
                    ->type('email', $user->email)
                    ->type('password', '123')
                    ->press('button[type="submit"].login-button')
                    ->assertPathIs('/')
                    ->assertSeeIn('div.selected-profile-img h6', $fullName)
                    ->assertSeeIn('#dropdownMenuButton', $fullName)
                    ->assertSeeIn(
                        'div.selected-profile-info ul li:first-child span:last-child', 
                        $user->nickname
                    );
            // dump($browser->text(''));
        });
    }
}
