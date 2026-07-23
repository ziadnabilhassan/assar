<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CategoryType;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Size;
use App\Models\Variant;
use Illuminate\Database\Seeder;

class DemoCatalogSeeder extends Seeder
{
    /**
     * Seed a small demo catalog for local app testing.
     */
    public function run(): void
    {
        if (Product::query()->exists()) {
            return;
        }

        $categoryType = CategoryType::firstOrCreate(
            ['title->en' => 'Fashion'],
            [
                'title' => ['en' => 'Fashion', 'ar' => 'Fashion'],
                'image' => 'assets/img/ecommerce/03.jpg',
                'show' => 1,
            ]
        );

        $category = Category::firstOrCreate(
            ['category_type_id' => $categoryType->id, 'title->en' => 'T-Shirts'],
            [
                'title' => ['en' => 'T-Shirts', 'ar' => 'T-Shirts'],
                'image' => 'website/assets/img/product/product1.png',
            ]
        );

        $color = Color::firstOrCreate(
            ['code' => '#FFFFFF'],
            [
                'name' => ['en' => 'White', 'ar' => 'White'],
            ]
        );

        $size = Size::firstOrCreate([
            'name' => 'M',
        ]);

        $product = Product::create([
            'name' => ['en' => 'Custom White T-Shirt', 'ar' => 'Custom White T-Shirt'],
            'description' => [
                'en' => 'Demo product for testing custom designs.',
                'ar' => 'Demo product for testing custom designs.',
            ],
            'image' => 'website/assets/img/product/product1.png',
            'category_id' => $category->id,
            'is_featured' => 1,
        ]);

        Variant::create([
            'product_id' => $product->id,
            'color_id' => $color->id,
            'size_id' => $size->id,
            'quantity' => 25,
            'price' => 350,
            'old_price' => 450,
        ]);

        ProductImage::create([
            'product_id' => $product->id,
            'color_id' => $color->id,
            'image' => 'website/assets/img/product/product1.png',
        ]);
    }
}
