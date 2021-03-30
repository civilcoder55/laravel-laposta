<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutTest extends TestCase
{

    use RefreshDatabase;


    // override TestCase class to make session persist on all requests
    public function setUp(): void
    {
        // call TestCase original setup
        parent::setUp();
        // get session cookies to persist it ['laravel_session'=> session_id ]
        $this->session_id = session()->getId();
        $this->session_cookie = [session()->getName() => $this->session_id];
    }

    public function call($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
    {
        // set session_id cookie on every request cookies
        $cookies = array_merge($cookies, $this->session_cookie);
        // return original call with modified params
        return parent::call($method, $uri, $parameters, $cookies, $files, $server, $content);
    }

    /** @test */
    public function user_can_logout()
    {
        $user = User::factory()->create();
        $this->actingAs($user)->get('/home')->assertOk();
        $this->assertAuthenticatedAs($user);
        $this->assertDatabaseHas('sessions', [
            'user_id' => $user->id,
        ]);
        $this->post('/logout')->assertStatus(302);
        $this->assertGuest();
        $this->assertDatabaseMissing('sessions', [
            'user_id' => $user->id,
        ]);

    }
}
