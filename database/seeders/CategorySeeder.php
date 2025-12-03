<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Classic', 'description' => 'Cookies klasik yang selalu favorit'],
            ['name' => 'Premium', 'description' => 'Cookies premium dengan bahan pilihan'],
            ['name' => 'Healthy', 'description' => 'Cookies sehat rendah gula'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}