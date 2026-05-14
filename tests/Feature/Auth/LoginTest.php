<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Auth;
use Tests\TestCase;

class LoginTest extends TestCase
{
    private $user_email = 'client4@example.com';
    private $user_password = 'password123';

    public function test_user_can_view_login_page(): void
    {
        $response = $this->get(route('login.create'));
        $response->assertStatus(200);
    }

    public function test_user_can_login_with_valid_credentials()
    {
        $response = $this->post(route('login.store'), [
            'email' => $this->user_email,
            'password' => $this->user_password,
        ]);

        $response->assertStatus(302);
        $this->assertAuthenticated();
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        $response = $this->post(route('login.store'), [
            'email' => 'nonexistent@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHas('error');
        $this->assertGuest();
    }

    public function test_user_can_logout()
    {
        $user = User::where('email', $this->user_email)->first();

        Auth::login($user);

        $response = $this->delete(route('profile.destroy'));

        $response->assertStatus(302);
        $this->assertGuest();
    }
}
