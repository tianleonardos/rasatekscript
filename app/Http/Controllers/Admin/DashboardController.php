<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\Review;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalRevenue = Order::where('status', 'selesai')->sum('total_price');

        // Calculate average rating and total reviews using simple average
        $totalReviews = Review::count();
        if ($totalReviews > 0) {
            $averageRating = Review::avg('rating');
        } else {
            $averageRating = 0;
        }

        $recentOrders = Order::with('user')->latest()->take(5)->get();
        $lowStock = Product::where('stock', '<', 10)->get();

        // Chart data - Penjualan per bulan
        $salesByMonth = Order::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(total_price) as total')
        )
        ->whereYear('created_at', date('Y'))
        ->where('status', 'selesai')
        ->groupBy('month')
        ->get();

        return view('admin.dashboard', compact(
            'totalProducts', 'totalOrders', 'totalCustomers', 'totalRevenue',
            'averageRating', 'totalReviews', 'recentOrders', 'lowStock', 'salesByMonth'
        ));
    }
}