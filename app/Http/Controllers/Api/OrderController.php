<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\PromoCode;
use App\Models\Variant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    private const DEFAULT_DELIVERY_FEE = 50;
    private const PAYMENT_METHOD_COD = 'cashOnDelivery';
    private const PAYMENT_METHOD_INSTAPAY = 'instapay';

    public function index(Request $request): JsonResponse
    {
        $orders = Order::withCount('products')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get()
            ->map(fn (Order $order) => [
                'id' => $order->id,
                'status' => $order->status,
                'total' => $this->money($order->total),
                'created_at' => $order->created_at?->toJSON(),
                'payment_method' => $order->payment_method,
                'payment_provider' => $order->payment_provider,
                'payment_status' => $order->payment_status,
                'payment_proof_url' => $order->payment_proof_url,
                'items_count' => $order->products_count,
            ])
            ->values();

        return response()->json([
            'success' => true,
            'status' => true,
            'message' => 'Orders fetched successfully',
            'data' => [
                'orders' => $orders,
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'delivery' => 'nullable|integer|exists:deliveries,id',
            'delivery_id' => 'nullable|integer|exists:deliveries,id',
            'city' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'note' => 'nullable|string|max:500',
            'pay' => 'nullable|string|max:255',
            'payment_method' => ['nullable', Rule::in([self::PAYMENT_METHOD_COD, self::PAYMENT_METHOD_INSTAPAY])],
            'payment_provider' => 'nullable|string|max:255',
            'payment_status' => 'nullable|string|max:255',
            'promo_code' => 'nullable|string|max:255',
            'coupon_code' => 'nullable|string|max:255',
            'code' => 'nullable|string|max:255',
            'shipping_info' => 'nullable|array',
            'shipping_info.first_name' => 'nullable|string|max:255',
            'shipping_info.last_name' => 'nullable|string|max:255',
            'shipping_info.name' => 'nullable|string|max:255',
            'shipping_info.phone' => 'nullable|string|max:20',
            'shipping_info.delivery' => 'nullable|string|max:255',
            'shipping_info.city' => 'nullable|string|max:255',
            'shipping_info.address' => 'nullable|string|max:500',
            'shipping_info.note' => 'nullable|string|max:500',
            'items' => 'nullable|array',
            'items.*.product_id' => 'nullable|integer|exists:products,id',
            'items.*.design_id' => 'nullable|integer|exists:saved_designs,id',
            'items.*.name' => 'required_with:items|string|max:255',
            'items.*.quantity' => 'required_with:items|integer|min:1',
            'items.*.price' => 'required_with:items|numeric|min:0',
            'items.*.color' => 'nullable|string|max:255',
            'items.*.size' => 'nullable|string|max:255',
            'items.*.image_url' => 'nullable|string|max:1000',
            'items.*.preview_image_url' => 'nullable|string|max:1000',
            'items.*.is_custom_design' => 'nullable|boolean',
            'items.*.design_data' => 'nullable|array',
            'subtotal' => 'nullable|numeric|min:0',
            'delivery_fee' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'total' => 'nullable|numeric|min:0',
        ]);

        $user = $request->user();
        $cartItems = CartItem::where('user_id', $user->id)->get();
        $requestItems = collect($data['items'] ?? []);

        if ($cartItems->isEmpty() && $requestItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'status' => false,
                'message' => 'Cart is empty',
            ], 400);
        }

        $order = DB::transaction(function () use ($cartItems, $data, $user, $requestItems) {
            foreach ($cartItems as $cartItem) {
                if (! $cartItem->is_custom_design && $cartItem->variant_id) {
                    $variant = Variant::where('id', $cartItem->variant_id)
                        ->lockForUpdate()
                        ->first();

                    if (! $variant || $variant->quantity < $cartItem->quantity) {
                        abort(400, __('main.Requested quantity exceeds available stock'));
                    }
                }
            }

            $usesRequestItems = $cartItems->isEmpty() && $requestItems->isNotEmpty();
            $subtotal = $usesRequestItems
                ? (float) ($data['subtotal'] ?? $requestItems->sum(fn (array $item) => (float) $item['price'] * (int) $item['quantity']))
                : $cartItems->sum(fn (CartItem $item) => (float) $item->price * $item->quantity);
            $promoCode = $this->promoCode($data);
            $discountAmount = (float) ($data['discount'] ?? 0);
            $discountLabel = null;

            if ($promoCode) {
                $discountAmount = $promoCode->calculateDiscount($subtotal);
                $discountLabel = $this->discountLabel($promoCode);
            }

            $delivery = $this->delivery($data);
            $shippingInfo = $data['shipping_info'] ?? [];
            $shipping = $delivery ? (float) $delivery->cost : (float) ($data['delivery_fee'] ?? self::DEFAULT_DELIVERY_FEE);
            $payment = $this->paymentFields($data);

            $order = Order::create([
                'code' => rand(100000, 999999),
                'user_id' => $user->id,
                'user_name' => $this->shippingName($data, $shippingInfo, $user),
                'phone' => $data['phone'] ?? $shippingInfo['phone'] ?? $user->phone ?? '',
                'delivery' => $delivery ? $delivery->getTranslation('name', 'ar') : ($shippingInfo['delivery'] ?? 'Delivery'),
                'city' => $data['city'] ?? $shippingInfo['city'] ?? '',
                'address' => $data['address'] ?? $shippingInfo['address'] ?? '',
                'shipping' => $shipping,
                'total' => (float) ($data['total'] ?? (max(0, $subtotal - $discountAmount) + $shipping)),
                'status' => 'pending',
                'coupon' => $promoCode?->code,
                'discount' => $discountLabel,
                'note' => $data['note'] ?? $shippingInfo['note'] ?? null,
                'payment_method' => $payment['payment_method'],
                'payment_provider' => $payment['payment_provider'],
                'payment_status' => $payment['payment_status'],
            ]);

            foreach ($cartItems as $cartItem) {
                $order->products()->create([
                    'product_id' => $cartItem->product_id,
                    'design_id' => $cartItem->design_id,
                    'name' => $cartItem->name,
                    'quantity' => $cartItem->quantity,
                    'color' => $cartItem->color ?? '',
                    'size' => $cartItem->size ?? '',
                    'price' => $cartItem->price,
                    'image_url' => $cartItem->image_url,
                    'preview_image_url' => $cartItem->preview_image_url,
                    'is_custom_design' => $cartItem->is_custom_design,
                    'design_data' => $cartItem->design_data,
                    'total_price' => (float) $cartItem->price * $cartItem->quantity,
                ]);

                if (! $cartItem->is_custom_design && $cartItem->variant_id) {
                    Variant::where('id', $cartItem->variant_id)->decrement('quantity', $cartItem->quantity);
                }
            }

            foreach ($usesRequestItems ? $requestItems : [] as $item) {
                $order->products()->create([
                    'product_id' => $item['product_id'] ?? null,
                    'design_id' => $item['design_id'] ?? null,
                    'name' => $item['name'],
                    'quantity' => (int) $item['quantity'],
                    'color' => $item['color'] ?? '',
                    'size' => $item['size'] ?? '',
                    'price' => $item['price'],
                    'image_url' => $item['image_url'] ?? null,
                    'preview_image_url' => $item['preview_image_url'] ?? null,
                    'is_custom_design' => (bool) ($item['is_custom_design'] ?? false),
                    'design_data' => $item['design_data'] ?? null,
                    'total_price' => (float) $item['price'] * (int) $item['quantity'],
                ]);
            }

            if ($promoCode) {
                $promoCode->increment('uses_count');
            }

            CartItem::where('user_id', $user->id)->delete();

            return $order->load('products');
        });

        return response()->json([
            'success' => true,
            'status' => true,
            'message' => 'Order created successfully',
            'data' => [
                'order' => $this->orderPayload($order),
                'cart' => [
                    'items' => [],
                    'subtotal' => 0,
                    'delivery_fee' => 0,
                    'total' => 0,
                ],
            ],
        ], 201);
    }

    public function show(Request $request, Order $order): JsonResponse
    {
        abort_unless($order->user_id === $request->user()->id, 404);

        return response()->json([
            'success' => true,
            'status' => true,
            'message' => 'Order fetched successfully',
            'data' => $this->orderDetailPayload($order->load('products')),
        ]);
    }

    public function uploadPaymentProof(Request $request, Order $order): JsonResponse
    {
        abort_unless($order->user_id === $request->user()->id, 404);
        abort_unless($this->isInstapayOrder($order), 422, 'Payment proof is only allowed for InstaPay orders.');

        $data = $request->validate([
            'proof' => 'required|file|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $path = $data['proof']->store('payment-proofs', 'public');

        $order->forceFill([
            'payment_proof_path' => $path,
            'payment_proof_url' => Storage::disk('public')->url($path),
            'payment_status' => 'pending_review',
        ])->save();

        return response()->json([
            'success' => true,
            'status' => true,
            'message' => 'Payment proof uploaded successfully',
            'data' => [
                'order' => $this->orderPayload($order->fresh('products')),
            ],
        ]);
    }

    private function delivery(array $data): ?Delivery
    {
        $deliveryId = $data['delivery_id'] ?? $data['delivery'] ?? null;

        return $deliveryId ? Delivery::find($deliveryId) : null;
    }

    private function promoCode(array $data): ?PromoCode
    {
        $code = $data['promo_code'] ?? $data['coupon_code'] ?? $data['code'] ?? null;

        if (! $code) {
            return null;
        }

        $promoCode = PromoCode::where('code', trim($code))
            ->lockForUpdate()
            ->first();

        if (! $promoCode || ! $promoCode->isValid()) {
            abort(422, 'Invalid promo code');
        }

        return $promoCode;
    }

    private function discountLabel(PromoCode $promoCode): string
    {
        $prefix = $promoCode->discount_type === 'percentage' ? '% ' : 'LE ';

        return $prefix . $this->money($promoCode->discount_value);
    }

    private function discountAmount(Order $order, float $subtotal): float
    {
        if (! $order->coupon || ! $order->discount) {
            return 0;
        }

        if (str_starts_with($order->discount, '% ')) {
            return $subtotal * ((float) substr($order->discount, 2)) / 100;
        }

        if (str_starts_with($order->discount, 'LE ')) {
            return min($subtotal, (float) substr($order->discount, 3));
        }

        return 0;
    }

    private function orderPayload(Order $order): array
    {
        $subtotal = $order->products->sum(fn ($item) => (float) $item->price * $item->quantity);

        return [
            'id' => $order->id,
            'code' => $order->code,
            'user_id' => $order->user_id,
            'user_name' => $order->user_name,
            'phone' => $order->phone,
            'delivery' => $order->delivery,
            'city' => $order->city,
            'address' => $order->address,
            'subtotal' => $this->money($subtotal),
            'delivery_fee' => $this->money($order->shipping),
            'discount' => $this->money($this->discountAmount($order, $subtotal)),
            'coupon' => $order->coupon,
            'discount_label' => $order->discount,
            'shipping' => $this->money($order->shipping),
            'total' => $this->money($order->total),
            'status' => $order->status,
            'note' => $order->note,
            'payment_method' => $order->payment_method,
            'payment_provider' => $order->payment_provider,
            'payment_status' => $order->payment_status,
            'payment_proof_url' => $order->payment_proof_url,
            'items' => $order->products->map(fn ($item) => [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'name' => $item->name,
                'price' => $this->money($item->price),
                'quantity' => $item->quantity,
                'total_price' => $this->money($item->total_price),
                'image_url' => $item->image_url,
                'preview_image_url' => $item->preview_image_url,
                'color' => $item->color,
                'size' => $item->size,
                'is_custom_design' => (bool) $item->is_custom_design,
                'design_id' => $item->design_id,
                'design_data' => $item->design_data,
            ])->values(),
        ];
    }

    private function orderDetailPayload(Order $order): array
    {
        $payload = $this->orderPayload($order);

        return [
            'id' => $payload['id'],
            'status' => $payload['status'],
            'subtotal' => $payload['subtotal'],
            'delivery_fee' => $payload['delivery_fee'],
            'discount' => $payload['discount'],
            'coupon' => $payload['coupon'],
            'discount_label' => $payload['discount_label'],
            'total' => $payload['total'],
            'shipping_info' => [
                'name' => $payload['user_name'],
                'phone' => $payload['phone'],
                'delivery' => $payload['delivery'],
                'city' => $payload['city'],
                'address' => $payload['address'],
                'note' => $payload['note'],
            ],
            'payment_method' => $payload['payment_method'] ?? 'cashOnDelivery',
            'payment_provider' => $payload['payment_provider'],
            'payment_status' => $payload['payment_status'],
            'payment_proof_url' => $payload['payment_proof_url'],
            'items' => $payload['items'],
            'created_at' => $order->created_at?->toJSON(),
        ];
    }

    private function shippingName(array $data, array $shippingInfo, $user): string
    {
        if (! empty($shippingInfo['name'])) {
            return $shippingInfo['name'];
        }

        $firstName = $data['first_name'] ?? $shippingInfo['first_name'] ?? $user->first_name ?? '';
        $lastName = $data['last_name'] ?? $shippingInfo['last_name'] ?? $user->last_name ?? '';

        return trim($firstName . ' ' . $lastName) ?: $user->email;
    }

    private function paymentFields(array $data): array
    {
        $method = $data['payment_method'] ?? $data['pay'] ?? self::PAYMENT_METHOD_COD;

        if ($method === self::PAYMENT_METHOD_INSTAPAY) {
            return [
                'payment_method' => self::PAYMENT_METHOD_INSTAPAY,
                'payment_provider' => 'instapay',
                'payment_status' => 'awaiting_transfer_proof',
            ];
        }

        return [
            'payment_method' => self::PAYMENT_METHOD_COD,
            'payment_provider' => null,
            'payment_status' => 'cash_on_delivery',
        ];
    }

    private function isInstapayOrder(Order $order): bool
    {
        return $order->payment_method === self::PAYMENT_METHOD_INSTAPAY
            || $order->payment_provider === 'instapay';
    }

    private function money(mixed $value): int|float
    {
        $number = (float) $value;

        return floor($number) == $number ? (int) $number : round($number, 2);
    }
}
