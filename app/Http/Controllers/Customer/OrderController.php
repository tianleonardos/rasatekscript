<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function checkout()
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong!');
        }

        $total = array_sum(array_map(function($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));

        return view('customer.checkout', compact('cart', 'total'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => [
                'required',
                'string',
                'regex:/^(08[0-9]{8,11}|\+62[0-9]{8,10})$/',
                'min:10',
                'max:13'
            ],
            'payment_method' => 'required|in:e-wallet',
            'e_wallet_type' => 'required_if:payment_method,e-wallet|in:dana,gopay,shopeepay',
            'notes' => 'nullable|string'
        ], [
            'customer_name.required' => 'Nama lengkap wajib diisi.',
            'customer_name.string' => 'Nama lengkap harus berupa teks.',
            'customer_name.max' => 'Nama lengkap maksimal 255 karakter.',
            'customer_phone.required' => 'Nomor telepon wajib diisi.',
            'customer_phone.string' => 'Nomor telepon harus berupa teks.',
            'customer_phone.regex' => 'Nomor telepon harus dimulai dengan 08 atau +62 dan hanya berisi angka.',
            'customer_phone.min' => 'Nomor telepon minimal 10 digit.',
            'customer_phone.max' => 'Nomor telepon maksimal 13 digit.',
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
            'payment_method.in' => 'Metode pembayaran tidak valid.',
            'e_wallet_type.required_if' => 'Jenis e-wallet wajib dipilih jika memilih E-Wallet.',
            'e_wallet_type.in' => 'Jenis e-wallet tidak valid.',
            'notes.string' => 'Catatan harus berupa teks.',
        ]);

        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong!');
        }

        DB::beginTransaction();
        try {
            $total = 0;
            foreach ($cart as $item) {
                $total += $item['price'] * $item['quantity'];
            }

            // Buat order
            $order = Order::create([
                'user_id' => auth()->id(),
                'total_price' => $total,
                'status' => 'pending',
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'notes' => $validated['notes'] ?? null,
                'payment_method' => $validated['payment_method'],
                'e_wallet_type' => $validated['e_wallet_type'] ?? null
            ]);

            // Buat order items dan kurangi stok
            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);

                // Kurangi stok
                $product = Product::find($item['id']);
                $product->decrement('stock', $item['quantity']);
            }

            DB::commit();

            // Kosongkan keranjang
            session()->forget('cart');

            return redirect()->route('customer.orders.show', $order->id)
                ->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with('orderItems.product')
            ->latest()
            ->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Pastikan order milik user yang login
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('orderItems.product');
        return view('customer.orders.show', compact('order'));
    }
}