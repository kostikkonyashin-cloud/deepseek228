<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    private $unique_email;
    private $unique_login;

    protected function setUp(): void
    {
        parent::setUp();

        $this->unique_email = 'test_' . Str::random(10) . '@example.com';
        $this->unique_login = 'user_' . Str::random(10);
    }

    public function test_user_can_view_register_page()
    {
        $response = $this->get(route('register.create'));
        $response->assertStatus(200);
    }

    public function test_user_can_register_with_valid_data()
    {
        $response = $this->post(route('register.store'), [
            'full_name' => 'Test User',
            'login' => $this->unique_login,
            'email' => $this->unique_email,
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
            'role_id' => 1
        ]);

        $response->assertRedirect(route('profile.index'));
        $this->assertAuthenticated();

        User::where('email', $this->unique_email)->delete();
    }

    public function test_user_cannot_register_with_existing_email()
    {
        $response = $this->post(route('register.store'), [
            'full_name' => 'Test User',
            'login' => 'newuser123',
            'email' => 'client1@example.com',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_user_cannot_register_with_existing_login()
    {
        $response = $this->post(route('register.store'), [
            'full_name' => 'Test User',
            'login' => 'client',
            'email' => 'newemail@example.com',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
            'role_id' => 1,
        ]);

        $response->assertSessionHasErrors('login');
        $this->assertGuest();
    }

    public function test_registration_requires_password_confirmation()
    {
        $response = $this->post(route('register.store'), [
            'full_name' => 'Test User',
            'login' => 'testuser123',
            'email' => 'test@example.com',
            'password' => 'Password123',
            'password_confirmation' => 'different',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertGuest();
    }
}
