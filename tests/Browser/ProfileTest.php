<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProfileTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testUpdateProfile()
    {
        $user = User::find(1);
        
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/profile')
                    ->type('name', $user->name)
                    ->type('surname', $user->surname)
                    ->type('nickname', $user->nickname)
                    ->type('brief', $user->brief)
                    ->type('email', $user->email)
                    ->type('mobile', $user->mobile)
                    ->type('password', '1234')
                    ->type('hideAccount', 1)
                    ->press('button[type="submit"].profile-update')
                    ->assertSeeIn('.alert-success', 'succeed profile update');
        });
    }

    public function testChangePassword()
    {
        $this->browse(function (Browser $browser){
            $browser->visit('/profile?tab=change-password')
                    ->type('password', '123')
                    ->type('newPassword', '1234')
                    ->type('repeatPassword', '1234')
                    ->press('button[type="submit"].change-password')
                    ->assertSeeIn('.alert-success', 'succeed profile update');
        });
    }

    public function testUpdateImage()
    {
        $this->browse(function (Browser $browser){
            $browser->visit('/profile')
                    ->type('file', '/img/p1.jpg')
                    ->press('button[type="submit"].update-profile-image')
                    ->assertSeeIn('div.user-info .alert-success', 'succeed profile image update');
        });
    }
}
