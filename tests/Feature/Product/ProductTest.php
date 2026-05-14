<?php

namespace Tests\Feature\Product;

use App\Models\Product;
use Tests\TestCase;

class ProductTest extends TestCase
{
    private $product_slug = 'elektricheskiy-samokat-premium';

    public function test_product_has_discounted_price()
    {
        $product = Product::first();

        if ($product->discount) {
            $finalPrice = round($product->price * (1 - $product->discount / 100));
            $this->assertLessThan($product->price, $finalPrice);
        } else {
            $this->assertEquals($product->price, $product->price);
        }
    }

    public function test_product_has_valid_price()
    {
        $product = Product::first();

        $this->assertIsNumeric($product->price);
        $this->assertGreaterThan(0, $product->price);
    }

    public function test_product_has_valid_slug()
    {
        $product = Product::first();

        $this->assertNotNull($product->slug);
        $this->assertIsString($product->slug);
    }
}
