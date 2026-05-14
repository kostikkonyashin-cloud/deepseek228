<?php

namespace Tests\Feature\Cart;

use App\Models\Product;
use App\Models\User;
use Tests\TestCase;

class CartTest extends TestCase
{
    private $client_email = 'client1@example.com';

    public function test_authenticated_user_can_access_cart()
    {
        $client = User::where('email', $this->client_email)->first();
        $this->actingAs($client);

        $response = $this->get(route('cart.index'));
        $response->assertStatus(200);
    }

    public function test_guest_cannot_access_cart()
    {
        $response = $this->get(route('cart.index'));
        $response->assertRedirect(route('login.create'));
    }

    public function test_user_can_add_product_to_cart()
    {
        $client = User::where('email', $this->client_email)->first();
        $product = Product::first();

        $this->actingAs($client);

        $response = $this->post(route('product.store'), [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }
}
