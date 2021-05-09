<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function guest_can_view_register_page()
    {
        $this->get('/register')->assertSuccessful()->assertViewIs('auth.register');
    }

    /** @test */
    public function guest_can_view_login_page()
    {
        $this->get('/login')->assertSuccessful()->assertViewIs('auth.login');
    }

    /** @test */
    public function user_cant_view_register_page()
    {
        $user = User::factory()->make();
        $this->actingAs($user)->get('/register')->assertRedirect('/dashboard');
    }

    /** @test */
    public function user_cant_view_login_page()
    {
        $user = User::factory()->make();
        $this->actingAs($user)->get('/login')->assertRedirect('/dashboard');
    }

    /** @test */
    public function user_can_register()
    {
        $this->post('/register', [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertRedirect('/dashboard');
        $this->assertDatabaseCount('users', 1);
    }

    /** @test */
    public function user_cant_register_with_same_email()
    {
        $user = User::factory()->create();
        $this->from('/register')->post('/register', [
            'name' => $this->faker->name,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertRedirect('/register')->assertSessionHasErrors(['email']);
        $this->assertGuest()->assertDatabaseCount('users', 1);
    }

    /** @test */
    public function valid_user_can_login()
    {
        Notification::fake();
        $user = User::factory()->create();
        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function invalid_user_cant_login()
    {
        $this->from('/login')->post('/login', [
            'email' => 'wrong_email@gmail.com',
            'password' => 'wrongPassword',
        ])->assertRedirect('/login')->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

}
