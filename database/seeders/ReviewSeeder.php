<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;

class ReviewSeeder extends Seeder
{
    public function run()
    {
        // Get existing data
        $customers = User::where('role', 'customer')->get();
        $products = Product::all();

        if ($customers->isEmpty() || $products->isEmpty()) {
            return;
        }

        // Create some completed orders with reviews
        foreach ($customers as $customer) {
            // Create a completed order
            $order = Order::create([
                'user_id' => $customer->id,
                'status' => 'selesai',
                'total_price' => 50000,
                'payment_method' => 'transfer'
            ]);

            // Add order items
            foreach ($products->take(2) as $product) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => 1,
                    'price' => $product->price
                ]);

                // Create review for this product
                Review::create([
                    'user_id' => $customer->id,
                    'product_id' => $product->id,
                    'order_id' => $order->id,
                    'rating' => 5, // Always 5-star rating
                    'comment' => 'Produk bagus, recommended!'
                ]);
            }
        }
    }
}
