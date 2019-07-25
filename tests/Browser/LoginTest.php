<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\Pages\LoginPage;
use ProcessMaker\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginTest extends DuskTestCase
{

    use DatabaseMigrations;

    public function test_login_page_loads()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new LoginPage)
                ->assertRouteIs('login');
        });
    }

    public function test_login_page_works()
    {

        $user = factory(User::class)->create([
            'username' => 'testuser',
            'password' => Hash::make('secret')
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit(new LoginPage)
                ->type('@username', $user->username)
                ->type('@password', 'secret')
                ->press('@login')
                ->assertRouteIs('requests.index')
                ->logout();
        });
    }
}