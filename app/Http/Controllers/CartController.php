<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('cart.index', compact('cart', 'total'));
    }

    public function add(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        
        // 1. Cek Stok (Jika habis)
        if ($product->stock < 1) {
            // Jika request AJAX (dari tombol tanpa reload)
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Stok produk habis!'
                ]);
            }
            // Jika request biasa
            return redirect()->back()->with('error', 'Stok produk habis!');
        }

        $cart = session()->get('cart', []);

        // 2. Cek jika barang sudah ada di keranjang
        if (isset($cart[$productId])) {
            // Cek apakah jumlah melebihi stok
            if ($cart[$productId]['quantity'] >= $product->stock) {
                if ($request->ajax()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Jumlah melebihi stok tersedia!'
                    ]);
                }
                return redirect()->back()->with('error', 'Jumlah melebihi stok tersedia!');
            }
            $cart[$productId]['quantity']++;
        } else {
            // Barang baru
            $cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image,
                'quantity' => 1,
                'stock' => $product->stock
            ];
        }

        session()->put('cart', $cart);

        // 3. Respon Sukses
        // INI BAGIAN PENTING: Mengirim data JSON ke Javascript agar halaman tidak reload
        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Produk berhasil ditambahkan ke keranjang!',
                'total_cart' => count($cart) // Mengirim jumlah item terbaru untuk update badge navbar
            ]);
        }

        // Fallback untuk browser lama (tetap redirect)
        return redirect()->back()->with('success', 'Produk ditambahkan ke keranjang!');
    }
    public function update(Request $request, $productId)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$productId])) {
            $quantity = $request->quantity;
            
            if ($quantity > $cart[$productId]['stock']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah melebihi stok tersedia!'
                ]);
            }
            
            if ($quantity <= 0) {
                unset($cart[$productId]);
            } else {
                $cart[$productId]['quantity'] = $quantity;
            }
            
            session()->put('cart', $cart);
            
            return response()->json([
                'success' => true,
                'subtotal' => $cart[$productId]['price'] * $quantity,
                'total' => array_sum(array_map(function($item) {
                    return $item['price'] * $item['quantity'];
                }, $cart))
            ]);
        }

        return response()->json(['success' => false]);
    }

    public function remove($productId)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Produk dihapus dari keranjang!');
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->back()->with('success', 'Keranjang dikosongkan!');
    }
}