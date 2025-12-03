<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'description', 'price', 'stock', 'image'
    ];

    protected static function booted()
    {
        static::deleting(function ($product) {
            // Delete all reviews when product is deleted
            $product->reviews()->delete();
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function averageRating()
    {
        $v = $this->totalReviews();

        if ($v == 0) {
            return 0; // Return 0 for products with no reviews
        }

        $R = $this->reviews()->avg('rating') ?? 0;
        $m = 10;

        // Calculate C: average rating of all products
        $C = \App\Models\Product::join('reviews', 'products.id', '=', 'reviews.product_id')
            ->avg('reviews.rating') ?? 3.0;

        return ($v / ($v + $m)) * $R + ($m / ($v + $m)) * $C;
    }

    public function totalReviews()
    {
        return $this->reviews()->count();
    }
}