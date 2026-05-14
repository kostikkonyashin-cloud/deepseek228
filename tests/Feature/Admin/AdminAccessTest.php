<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    private $admin_email = 'admin@example.com';
    private $manager_email = 'manager1@example.com';
    private $client_email = 'client1@example.com';

    public function test_admin_can_access_admin_panel()
    {
        $admin = User::where('email', $this->admin_email)->first();
        $this->actingAs($admin);

        $response = $this->get(route('admin.user.index'));
        $response->assertStatus(200);
    }

    public function test_manager_cannot_access_admin_panel()
    {
        $manager = User::where('email', $this->manager_email)->first();
        $this->actingAs($manager);

        $response = $this->get(route('admin.user.index'));
        $response->assertStatus(302);
    }

    public function test_client_cannot_access_admin_panel()
    {
        $client = User::where('email', $this->client_email)->first();
        $this->actingAs($client);

        $response = $this->get(route('admin.user.index'));
        $response->assertStatus(302);
    }

    public function test_guest_cannot_access_admin_panel()
    {
        $response = $this->get(route('admin.user.index'));
        $response->assertRedirect(route('login.create'));
    }

    public function test_manager_can_access_orders_management()
    {
        $manager = User::where('email', $this->manager_email)->first();
        $this->actingAs($manager);

        $response = $this->get(route('profile.management.index'));
        $response->assertStatus(200);
    }

    public function test_client_cannot_access_orders_management()
    {
        $client = User::where('email', $this->client_email)->first();
        $this->actingAs($client);

        $response = $this->get(route('profile.management.index'));
        $response->assertStatus(302);
    }

    public function test_authenticated_user_can_access_profile()
    {
        $client = User::where('email', $this->client_email)->first();
        $this->actingAs($client);

        $response = $this->get(route('profile.index'));
        $response->assertStatus(200);
    }

    public function test_guest_cannot_access_profile()
    {
        $response = $this->get(route('profile.index'));
        $response->assertRedirect(route('login.create'));
    }
}
