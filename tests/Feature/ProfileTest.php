<?php

namespace Tests\Feature;


use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_view_profile()
    {
        $user = User::factory()->create();
        $this->actingAs($user)->get('/home');
        $sessionID = \App\Models\Session::first()->id;
        Session::shouldReceive('getId')->andReturn($sessionID);
        Session::shouldReceive('get')->andReturn(false);
        Session::shouldReceive('token')->andReturn(false);

        $response = $this->actingAs($user)->get('/profile')->assertOk()->assertViewIs('profile');
        $response->assertViewHasAll(['other_sessions', 'current_session']);


    }
}
