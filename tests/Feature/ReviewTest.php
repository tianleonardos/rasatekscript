<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_review()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'selesai'
        ]);

        // Create order item
        \App\Models\OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $product->price
        ]);

        $this->actingAs($user);

        $response = $this->post(route('reviews.store', ['order' => $order->id, 'product' => $product->id]), [
            'rating' => 5,
            'comment' => 'Great product!'
        ]);

        $response->assertRedirect(route('customer.orders.show', $order->id));
        $this->assertDatabaseHas('reviews', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'order_id' => $order->id,
            'rating' => 5,
            'comment' => 'Great product!'
        ]);
    }

    public function test_user_cannot_review_twice()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'selesai'
        ]);

        \App\Models\OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $product->price
        ]);

        Review::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'order_id' => $order->id,
            'rating' => 4,
            'comment' => 'Good'
        ]);

        $this->actingAs($user);

        $response = $this->post(route('reviews.store', ['order' => $order->id, 'product' => $product->id]), [
            'rating' => 5,
            'comment' => 'Great product!'
        ]);

        $response->assertRedirect()->assertSessionHas('error');
        $this->assertDatabaseCount('reviews', 1);
    }
}
