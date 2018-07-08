<?php

namespace Tests\Browser;

use App\Models\Rooms;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RegisterTest extends DuskTestCase
{    
    public function tearDown()
    {
        $del = User::where('email', 'a@a.com')->delete();
    }

    public function test_should_register()
    {
        $this->browse(function (Browser $browser){
            $name = 'emre';
            $nickname = 'kaya';
            $email = 'a@a.com';
            $password = '123456';

            $browser->visit('/register')
                    ->type('name', $name)
                    ->type('nickname', $nickname)
                    ->type('email', $email)
                    ->type('password', $password)
                    ->type('password_confirmation', $password)
                    ->press('button[type="submit"].register-button')
                    ->assertPathIs('/')
                    ->assertSeeIn('div.selected-profile-img h6', $name)
                    ->assertSeeIn('#dropdownMenuButton', $name)
                    ->assertSeeIn(
                        'div.selected-profile-info ul li:first-child span:last-child', 
                        $nickname
                    );

            // dump($browser->text(''));
        });
    }
}
