<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function create($orderId, $productId)
    {
        $order = Order::where('user_id', auth()->id())
            ->where('id', $orderId)
            ->where('status', 'selesai')
            ->firstOrFail();

        // Cek apakah produk ada di order
        $orderItem = $order->orderItems()->where('product_id', $productId)->first();
        if (!$orderItem) {
            abort(404, 'Produk tidak ditemukan dalam pesanan ini.');
        }

        // Cek apakah sudah pernah review
        $existingReview = Review::where('order_id', $orderId)
            ->where('product_id', $productId)
            ->where('user_id', auth()->id())
            ->first();

        if ($existingReview) {
            return redirect()->back()->with('error', 'Anda sudah memberikan review untuk produk ini!');
        }

        $product = $orderItem->product;

        return view('customer.reviews.create', compact('order', 'product'));
    }

    public function store(Request $request, $orderId, $productId)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        $order = Order::where('user_id', auth()->id())
            ->where('id', $orderId)
            ->where('status', 'selesai')
            ->firstOrFail();

        // Cek apakah sudah pernah review
        $existingReview = Review::where('order_id', $orderId)
            ->where('product_id', $productId)
            ->where('user_id', auth()->id())
            ->first();

        if ($existingReview) {
            return redirect()->back()->with('error', 'Anda sudah memberikan review untuk produk ini!');
        }

        Review::create([
            'user_id' => auth()->id(),
            'product_id' => $productId,
            'order_id' => $orderId,
            'rating' => $validated['rating'],
            'comment' => $validated['comment']
        ]);

        return redirect()->route('customer.orders.show', $orderId)
            ->with('success', 'Review berhasil ditambahkan!');
    }
}