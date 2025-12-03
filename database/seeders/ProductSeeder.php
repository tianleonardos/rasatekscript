<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Clear existing products to avoid duplicates
        Product::query()->delete();

        // Reset auto-increment to start from 1
        \DB::statement('ALTER TABLE products AUTO_INCREMENT = 1');

        $products = [
            [
                'category_id' => 1,
                'name' => 'Chocolate Chip Cookies',
                'description' => 'Cookies cokelat klasik dengan choco chips premium',
                'price' => 25000,
                'stock' => 50,
                'image' => 'chocolate-chip.jpg',
            ],
            [
                'category_id' => 2,
                'name' => 'Red Velvet Cookies',
                'description' => 'Cookies red velvet dengan cream cheese',
                'price' => 30000,
                'stock' => 30,
                'image' => 'red-velvet.jpg',
            ],
            [
                'category_id' => 3,
                'name' => 'Oatmeal Raisin Cookies',
                'description' => 'Cookies oatmeal sehat dengan kismis',
                'price' => 22000,
                'stock' => 40,
                'image' => 'oatmeal-raisin-cookies.jpg',
            ],
            [
                'category_id' => 2,
                'name' => 'Matcha Cookies',
                'description' => 'Cookies green tea matcha original Jepang',
                'price' => 28000,
                'stock' => 25,
                'image' => 'matcha-cookies.jpg',
            ],
            [
                'category_id' => 1,
                'name' => 'Double Chocolate Cookies',
                'description' => 'Cookies cokelat ganda dengan potongan cokelat ekstra',
                'price' => 27000,
                'stock' => 35,
                'image' => 'double-chocolate.jpg',
            ],
            [
                'category_id' => 3,
                'name' => 'Peanut Butter Cookies',
                'description' => 'Cookies kacang dengan selai kacang alami',
                'price' => 24000,
                'stock' => 45,
                'image' => 'peanut-butter.jpg',
            ],
            [
                'category_id' => 2,
                'name' => 'Strawberry Cookies',
                'description' => 'Cookies stroberi dengan potongan buah segar',
                'price' => 26000,
                'stock' => 28,
                'image' => 'strawberry-cookies.jpg',
            ],
            [
                'category_id' => 1,
                'name' => 'White Chocolate Cookies',
                'description' => 'Cookies cokelat putih dengan almond',
                'price' => 29000,
                'stock' => 32,
                'image' => 'white-chocolate.jpg',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}