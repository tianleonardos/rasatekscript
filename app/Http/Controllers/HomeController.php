<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->get();
        $categories = Category::all();
        return view('home', compact('products', 'categories'));
    }

    public function productDetail($id)
    {
        $product = Product::with(['category', 'reviews.user'])->findOrFail($id);
        $averageRating = $product->averageRating();
        return view('product-detail', compact('product', 'averageRating'));
    }

    public function filterByCategory($categoryId)
    {
        $products = Product::where('category_id', $categoryId)->get();
        $categories = Category::all();
        $selectedCategory = Category::find($categoryId);
        return view('home', compact('products', 'categories', 'selectedCategory'));
    }
}