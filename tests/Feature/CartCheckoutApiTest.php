<?php

namespace Tests\Feature;

use App\Models\CartItem;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\PromoCode;
use App\Models\SavedDesign;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

    public function test_user_can_apply_promo_code_to_mobile_cart(): void
    {
        $user = User::factory()->create();
        $delivery = Delivery::create([
            'name' => ['en' => 'Cairo Standard', 'ar' => 'Cairo Standard'],
            'cost' => 60,
        ]);
        PromoCode::create([
            'code' => 'ATHAR10',
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'max_uses' => 10,
            'uses_count' => 0,
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
            'is_active' => true,
        ]);

        Sanctum::actingAs($user);

        CartItem::create([
            'user_id' => $user->id,
            'product_id' => null,
            'name' => 'Custom T-shirt',
            'price' => 300,
            'quantity' => 2,
            'is_custom_design' => true,
            'design_data' => ['layers' => []],
        ]);

        $this->postJson('/api/promocodes/apply', [
            'code' => 'ATHAR10',
            'delivery_id' => $delivery->id,
        ])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.promo_code.code', 'ATHAR10')
            ->assertJsonPath('data.cart.subtotal', 600)
            ->assertJsonPath('data.cart.discount', 60)
            ->assertJsonPath('data.cart.delivery_fee', 60)
            ->assertJsonPath('data.cart.total', 600);

        $this->assertDatabaseHas('promo_codes', [
            'code' => 'ATHAR10',
            'uses_count' => 0,
        ]);
    }

    public function test_order_applies_promo_code_and_increments_usage(): void
    {
        $user = User::factory()->create([
            'phone' => '01000000000',
        ]);
        PromoCode::create([
            'code' => 'WELCOME50',
            'discount_type' => 'fixed',
            'discount_value' => 50,
            'max_uses' => 10,
            'uses_count' => 0,
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
            'is_active' => true,
        ]);

        Sanctum::actingAs($user);

        CartItem::create([
            'user_id' => $user->id,
            'product_id' => null,
            'name' => 'Custom T-shirt',
            'price' => 299,
            'quantity' => 1,
            'is_custom_design' => true,
            'design_data' => ['layers' => []],
        ]);

        $this->postJson('/api/orders', [
            'first_name' => 'Athar',
            'last_name' => 'User',
            'phone' => '01000000000',
            'city' => 'Cairo',
            'address' => 'Nasr City',
            'promo_code' => 'WELCOME50',
        ])
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.order.subtotal', 299)
            ->assertJsonPath('data.order.discount', 50)
            ->assertJsonPath('data.order.coupon', 'WELCOME50')
            ->assertJsonPath('data.order.total', 299);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'coupon' => 'WELCOME50',
            'discount' => 'LE 50',
            'total' => 299,
        ]);
        $this->assertDatabaseHas('promo_codes', [
            'code' => 'WELCOME50',
            'uses_count' => 1,
        ]);
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

    public function test_flutter_can_create_instapay_order_from_direct_items_and_list_payment_fields(): void
    {
        $user = User::factory()->create([
            'phone' => '01000000000',
        ]);
        $otherUser = User::factory()->create();

        Order::create([
            'code' => 111111,
            'user_id' => $otherUser->id,
            'user_name' => 'Other User',
            'phone' => '01011111111',
            'delivery' => 'Delivery',
            'city' => 'Alexandria',
            'address' => 'Other address',
            'shipping' => 50,
            'total' => 999,
            'payment_method' => 'instapay',
            'payment_provider' => 'instapay',
            'payment_status' => 'awaiting_transfer_proof',
        ]);

        Sanctum::actingAs($user);

        $orderId = $this->postJson('/api/orders', [
            'shipping_info' => [
                'name' => 'Athar Buyer',
                'phone' => '01000000000',
                'city' => 'Cairo',
                'address' => 'Nasr City',
                'note' => 'Call first',
            ],
            'payment_method' => 'instapay',
            'payment_provider' => 'instapay',
            'payment_status' => 'awaiting_transfer_proof',
            'items' => [
                [
                    'name' => 'Custom Hoodie',
                    'price' => 790,
                    'quantity' => 2,
                    'color' => 'Black',
                    'size' => 'L',
                ],
            ],
            'subtotal' => 1580,
            'delivery_fee' => 50,
            'discount' => 0,
            'total' => 1630,
        ])
            ->assertCreated()
            ->assertJsonPath('data.order.total', 1630)
            ->assertJsonPath('data.order.status', 'pending')
            ->assertJsonPath('data.order.payment_method', 'instapay')
            ->assertJsonPath('data.order.payment_provider', 'instapay')
            ->assertJsonPath('data.order.payment_status', 'awaiting_transfer_proof')
            ->json('data.order.id');

        $this->assertDatabaseHas('orders', [
            'id' => $orderId,
            'user_id' => $user->id,
            'user_name' => 'Athar Buyer',
            'payment_method' => 'instapay',
            'payment_provider' => 'instapay',
            'payment_status' => 'awaiting_transfer_proof',
        ]);

        $this->getJson('/api/orders')
            ->assertOk()
            ->assertJsonCount(1, 'data.orders')
            ->assertJsonPath('data.orders.0.id', $orderId)
            ->assertJsonPath('data.orders.0.payment_method', 'instapay')
            ->assertJsonPath('data.orders.0.payment_provider', 'instapay')
            ->assertJsonPath('data.orders.0.payment_status', 'awaiting_transfer_proof')
            ->assertJsonPath('data.orders.0.items_count', 1);
    }

    public function test_user_can_upload_instapay_payment_proof_for_own_order(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $order = Order::create([
            'code' => 222222,
            'user_id' => $user->id,
            'user_name' => 'Athar Buyer',
            'phone' => '01000000000',
            'delivery' => 'Delivery',
            'city' => 'Cairo',
            'address' => 'Nasr City',
            'shipping' => 50,
            'total' => 1630,
            'payment_method' => 'instapay',
            'payment_provider' => 'instapay',
            'payment_status' => 'awaiting_transfer_proof',
        ]);

        Sanctum::actingAs($user);

        $this->postJson("/api/orders/{$order->id}/payment-proof", [
            'proof' => UploadedFile::fake()->image('proof.png')->size(1024),
        ])
            ->assertOk()
            ->assertJsonPath('data.order.payment_status', 'pending_review')
            ->assertJsonPath('data.order.payment_method', 'instapay');

        $order->refresh();

        $this->assertNotNull($order->payment_proof_path);
        $this->assertNotNull($order->payment_proof_url);
        Storage::disk('public')->assertExists($order->payment_proof_path);
    }

    public function test_admin_can_filter_and_review_instapay_payment_proofs(): void
    {
        $admin = User::factory()->create(['is_admin' => 1]);
        $customer = User::factory()->create([
            'first_name' => 'Mona',
            'last_name' => 'Ali',
            'email' => 'mona@example.com',
        ]);

        $order = Order::create([
            'code' => 333333,
            'user_id' => $customer->id,
            'user_name' => 'Mona Ali',
            'phone' => '01000000000',
            'delivery' => 'Delivery',
            'city' => 'Cairo',
            'address' => 'Nasr City',
            'shipping' => 50,
            'total' => 1630,
            'payment_method' => 'instapay',
            'payment_provider' => 'instapay',
            'payment_status' => 'pending_review',
            'payment_proof_url' => 'http://athar_back.test/storage/payment-proofs/proof.png',
        ]);

        Sanctum::actingAs($admin);

        $this->getJson('/api/admin/dashboard')
            ->assertOk()
            ->assertJsonPath('data.counts.total_orders', 1)
            ->assertJsonPath('data.counts.uploaded_instapay_proofs', 1)
            ->assertJsonPath('data.recent_orders.0.customer.email', 'mona@example.com')
            ->assertJsonPath('data.recent_orders.0.payment_proof_url', 'http://athar_back.test/storage/payment-proofs/proof.png');

        $this->getJson('/api/admin/orders?payment_method=instapay&payment_status=pending_review&status=pending')
            ->assertOk()
            ->assertJsonPath('data.orders.0.id', $order->id)
            ->assertJsonPath('data.orders.0.payment_status', 'pending_review');

        $this->patchJson("/api/admin/orders/{$order->id}/payment-proof/status", [
            'payment_status' => 'paid',
            'admin_note' => 'Transfer confirmed',
        ])
            ->assertOk()
            ->assertJsonPath('data.order.payment_status', 'paid')
            ->assertJsonPath('data.order.admin_note', 'Transfer confirmed');

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_status' => 'paid',
            'payment_admin_note' => 'Transfer confirmed',
        ]);
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
