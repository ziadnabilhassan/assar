<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\CategoryType;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use App\Models\User;
use App\Models\Variant;
use App\Models\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class WishlistApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_product_to_wishlist(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();

        Sanctum::actingAs($user);

        $this->postJson('/api/wishlist', [
            'product_id' => $product->id,
        ])
            ->assertOk()
            ->assertJsonPath('message', 'Product added to wishlist');

        $this->assertDatabaseHas('wishlists', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_user_can_fetch_favorite_products(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct([
            'name' => ['en' => 'Athar Hoodie', 'ar' => 'Athar Hoodie'],
            'image' => 'products/hoodie.png',
        ], [
            'price' => 299,
            'quantity' => 4,
        ]);

        Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        Sanctum::actingAs($user);

        $this->getJson('/api/wishlist')
            ->assertOk()
            ->assertJsonPath('data.0.product_id', $product->id)
            ->assertJsonPath('data.0.name', 'Athar Hoodie')
            ->assertJsonPath('data.0.price', 299)
            ->assertJsonPath('data.0.image', 'http://athar_back.test/products/hoodie.png')
            ->assertJsonPath('data.0.in_stock', true);
    }

    public function test_duplicate_wishlist_items_are_not_created(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();

        Sanctum::actingAs($user);

        $this->postJson('/api/wishlist', ['product_id' => $product->id])->assertOk();
        $this->postJson('/api/wishlist', ['product_id' => $product->id])->assertOk();

        $this->assertDatabaseCount('wishlists', 1);
    }

    public function test_user_can_remove_product_from_wishlist(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();

        Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        Sanctum::actingAs($user);

        $this->deleteJson("/api/wishlist/{$product->id}")
            ->assertOk()
            ->assertJsonPath('message', 'Product removed from wishlist');

        $this->assertDatabaseMissing('wishlists', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_wishlist_requires_authenticated_user(): void
    {
        $this->getJson('/api/wishlist')->assertUnauthorized();
        $this->postJson('/api/wishlist', ['product_id' => 1])->assertUnauthorized();
        $this->deleteJson('/api/wishlist/1')->assertUnauthorized();
    }

    public function test_product_id_is_required_and_must_exist(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/wishlist', [])->assertUnprocessable();
        $this->postJson('/api/wishlist', ['product_id' => 999])->assertUnprocessable();
    }

    private function createProduct(array $productOverrides = [], array $variantOverrides = []): Product
    {
        $categoryType = CategoryType::create([
            'title' => 'Clothes',
            'image' => 'category-types/clothes.png',
        ]);

        $category = Category::create([
            'title' => ['en' => 'Hoodies', 'ar' => 'Hoodies'],
            'category_type_id' => $categoryType->id,
            'image' => 'categories/hoodies.png',
        ]);

        $product = Product::create(array_merge([
            'name' => ['en' => 'Product name', 'ar' => 'Product name'],
            'image' => 'products/product.png',
            'description' => ['en' => 'Description', 'ar' => 'Description'],
            'category_id' => $category->id,
        ], $productOverrides));

        $color = Color::create([
            'name' => ['en' => 'Black', 'ar' => 'Black'],
            'code' => '#000000',
        ]);

        $size = Size::create([
            'name' => 'M',
        ]);

        Variant::create(array_merge([
            'product_id' => $product->id,
            'color_id' => $color->id,
            'size_id' => $size->id,
            'quantity' => 1,
            'price' => 299,
        ], $variantOverrides));

        return $product;
    }
}
