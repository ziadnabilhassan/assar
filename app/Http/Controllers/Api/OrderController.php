<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\Variant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    private const DEFAULT_DELIVERY_FEE = 50;

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
            'payment_method' => 'nullable|string|max:255',
        ]);

        $user = $request->user();
        $cartItems = CartItem::where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'status' => false,
                'message' => 'Cart is empty',
            ], 400);
        }

        $order = DB::transaction(function () use ($cartItems, $data, $user) {
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

            $subtotal = $cartItems->sum(fn (CartItem $item) => (float) $item->price * $item->quantity);
            $delivery = $this->delivery($data);
            $shipping = $delivery ? (float) $delivery->cost : self::DEFAULT_DELIVERY_FEE;

            $order = Order::create([
                'code' => rand(100000, 999999),
                'user_id' => $user->id,
                'user_name' => trim(($data['first_name'] ?? $user->first_name ?? '') . ' ' . ($data['last_name'] ?? $user->last_name ?? '')) ?: $user->email,
                'phone' => $data['phone'] ?? $user->phone ?? '',
                'delivery' => $delivery ? $delivery->getTranslation('name', 'ar') : 'Delivery',
                'city' => $data['city'] ?? '',
                'address' => $data['address'] ?? '',
                'shipping' => $shipping,
                'total' => $subtotal + $shipping,
                'note' => $data['note'] ?? null,
                'payment_method' => $data['payment_method'] ?? $data['pay'] ?? null,
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

    private function delivery(array $data): ?Delivery
    {
        $deliveryId = $data['delivery_id'] ?? $data['delivery'] ?? null;

        return $deliveryId ? Delivery::find($deliveryId) : null;
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
            'discount' => 0,
            'shipping' => $this->money($order->shipping),
            'total' => $this->money($order->total),
            'status' => $order->status,
            'note' => $order->note,
            'payment_method' => $order->payment_method,
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
            'items' => $payload['items'],
            'created_at' => $order->created_at?->toJSON(),
        ];
    }

    private function money(mixed $value): int|float
    {
        $number = (float) $value;

        return floor($number) == $number ? (int) $number : round($number, 2);
    }
}
