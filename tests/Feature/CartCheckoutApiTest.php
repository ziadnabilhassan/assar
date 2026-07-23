<?php

namespace Tests\Feature;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\SavedDesign;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CartCheckoutApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_custom_design_to_cart(): void
    {
        $user = User::factory()->create();
        $design = SavedDesign::create([
            'user_id' => $user->id,
            'name' => 'Custom Shirt',
            'preview_image' => 'assets/images/design/t-shirt.png',
            'design_data' => [
                'canvas' => ['width' => 300],
                'layers' => [
                    ['type' => 'text', 'text' => 'Athar'],
                ],
            ],
        ]);

        Sanctum::actingAs($user);

        $payload = [
            'product_id' => null,
            'name' => 'T-shirt مخصص',
            'price' => 299,
            'quantity' => 1,
            'color' => 'أبيض',
            'size' => 'M',
            'image_url' => 'assets/images/design/t-shirt.png',
            'is_custom_design' => true,
            'design_id' => $design->id,
            'design_data' => [
                'product' => ['type' => 'shirt'],
                'template' => ['id' => 7],
                'canvas' => ['width' => 300],
                'layers' => [
                    ['type' => 'text', 'text' => 'Athar'],
                ],
            ],
        ];

        $this->postJson('/api/cart/items', $payload)
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.item.product_id', null)
            ->assertJsonPath('data.item.is_custom_design', true)
            ->assertJsonPath('data.item.preview_image_url', 'http://athar_back.test/assets/images/design/t-shirt.png')
            ->assertJsonPath('data.item.design_data.layers.0.text', 'Athar')
            ->assertJsonPath('data.cart.subtotal', 299)
            ->assertJsonPath('data.cart.total', 349);

        $this->getJson('/api/cart')
            ->assertOk()
            ->assertJsonPath('data.items.0.name', 'T-shirt مخصص')
            ->assertJsonPath('data.items.0.design_data.template.id', 7);
    }

    public function test_order_copies_custom_design_data_and_clears_cart(): void
    {
        $user = User::factory()->create([
            'phone' => '01000000000',
        ]);

        Sanctum::actingAs($user);

        CartItem::create([
            'user_id' => $user->id,
            'product_id' => null,
            'name' => 'Custom T-shirt',
            'price' => 299,
            'quantity' => 2,
            'color' => 'White',
            'size' => 'M',
            'image_url' => 'assets/images/design/t-shirt.png',
            'is_custom_design' => true,
            'design_data' => [
                'canvas' => ['width' => 320],
                'layers' => [
                    ['type' => 'image', 'src' => 'logo.png'],
                ],
            ],
        ]);

        $this->postJson('/api/orders', [
            'first_name' => 'Athar',
            'last_name' => 'User',
            'phone' => '01000000000',
            'city' => 'Cairo',
            'address' => 'Nasr City',
        ])
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.order.total', 648)
            ->assertJsonPath('data.order.items.0.is_custom_design', true)
            ->assertJsonPath('data.order.items.0.design_data.layers.0.src', 'logo.png')
            ->assertJsonPath('data.cart.items', []);

        $this->assertDatabaseCount('cart_items', 0);
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'total' => 648,
        ]);

        $order = Order::with('products')->first();
        $this->assertTrue((bool) $order->products->first()->is_custom_design);
    }

    public function test_custom_cart_item_can_snapshot_saved_design_by_id(): void
    {
        $user = User::factory()->create();
        $design = SavedDesign::create([
            'user_id' => $user->id,
            'name' => 'Saved Designer Snapshot',
            'preview_image_url' => 'assets/images/design/preview.png',
            'design_data' => [
                'canvas' => ['width' => 320],
                'layers' => [
                    ['type' => 'text', 'text' => 'Saved'],
                ],
            ],
        ]);

        Sanctum::actingAs($user);

        $this->postJson('/api/cart/items', [
            'product_id' => null,
            'name' => 'Designer Product',
            'price' => 199,
            'quantity' => 1,
            'is_custom_design' => true,
            'design_id' => $design->id,
        ])
            ->assertCreated()
            ->assertJsonPath('data.item.design_id', $design->id)
            ->assertJsonPath('data.item.preview_image_url', 'assets/images/design/preview.png')
            ->assertJsonPath('data.item.design_data.layers.0.text', 'Saved');
    }

    public function test_custom_cart_item_requires_design_id_or_design_data(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/cart/items', [
            'product_id' => null,
            'name' => 'Designer Product',
            'price' => 199,
            'quantity' => 1,
            'is_custom_design' => true,
        ])->assertUnprocessable();
    }

    public function test_user_can_list_and_show_own_orders_with_custom_design_data(): void
    {
        $user = User::factory()->create([
            'phone' => '01000000000',
        ]);

        Sanctum::actingAs($user);

        CartItem::create([
            'user_id' => $user->id,
            'product_id' => null,
            'name' => 'T-shirt مخصص',
            'price' => 299,
            'quantity' => 1,
            'color' => 'أبيض',
            'size' => 'M',
            'image_url' => 'assets/images/design/t-shirt.png',
            'preview_image_url' => 'assets/images/design/preview.png',
            'is_custom_design' => true,
            'design_data' => [
                'layers' => [
                    ['type' => 'text', 'text' => 'History'],
                ],
            ],
        ]);

        $orderId = $this->postJson('/api/orders', [
            'first_name' => 'Athar',
            'last_name' => 'User',
            'phone' => '01000000000',
            'city' => 'Cairo',
            'address' => 'Nasr City',
            'payment_method' => 'cashOnDelivery',
        ])
            ->assertCreated()
            ->json('data.order.id');

        $this->getJson('/api/orders')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.orders.0.id', $orderId)
            ->assertJsonPath('data.orders.0.items_count', 1);

        $this->getJson("/api/orders/{$orderId}")
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $orderId)
            ->assertJsonPath('data.subtotal', 299)
            ->assertJsonPath('data.delivery_fee', 50)
            ->assertJsonPath('data.total', 349)
            ->assertJsonPath('data.payment_method', 'cashOnDelivery')
            ->assertJsonPath('data.items.0.is_custom_design', true)
            ->assertJsonPath('data.items.0.preview_image_url', 'assets/images/design/preview.png')
            ->assertJsonPath('data.items.0.design_data.layers.0.text', 'History');
    }

    public function test_cart_requires_authenticated_user(): void
    {
        $this->getJson('/api/cart')->assertUnauthorized();
    }

    public function test_quantity_must_be_positive(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/cart/items', [
            'name' => 'Custom T-shirt',
            'price' => 299,
            'quantity' => 0,
            'is_custom_design' => true,
            'design_data' => ['layers' => []],
        ])->assertUnprocessable();
    }
}
