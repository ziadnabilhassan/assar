<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Delivery;
use App\Models\PromoCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PromoCodeController extends Controller
{
    private const DEFAULT_DELIVERY_FEE = 50;

    public function validateCode(Request $request): JsonResponse
    {
        $data = $request->validate([
            'code' => 'required|string',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $promoCode = $this->findPromoCode($data['code']);

        if (!$promoCode || !$promoCode->isValid()) {
            return response()->json([
                'success' => false,
                'status' => false,
                'valid' => false,
                'message' => 'Invalid promo code',
            ], 422);
        }

        $subtotal = (float) $data['subtotal'];
        $discount = $promoCode->calculateDiscount($subtotal);

        return response()->json([
            'success' => true,
            'status' => true,
            'valid' => true,
            'message' => 'Promo code is valid',
            'data' => [
                'promo_code' => $this->promoCodePayload($promoCode),
                'subtotal' => $this->money($subtotal),
                'discount' => $this->money($discount),
                'discount_amount' => $this->money($discount),
                'total' => $this->money(max(0, $subtotal - $discount)),
            ],
        ]);
    }

    public function applyToCart(Request $request): JsonResponse
    {
        $data = $request->validate([
            'code' => 'required|string',
            'delivery_id' => 'nullable|integer|exists:deliveries,id',
            'delivery' => 'nullable|integer|exists:deliveries,id',
        ]);

        $cartItems = CartItem::where('user_id', $request->user()->id)->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'status' => false,
                'message' => 'Cart is empty',
            ], 400);
        }

        $promoCode = $this->findPromoCode($data['code']);

        if (!$promoCode || !$promoCode->isValid()) {
            return response()->json([
                'success' => false,
                'status' => false,
                'message' => 'Invalid promo code',
            ], 422);
        }

        $subtotal = $cartItems->sum(fn (CartItem $item) => (float) $item->price * $item->quantity);
        $discount = $promoCode->calculateDiscount($subtotal);
        $delivery = $this->delivery($data);
        $deliveryFee = $delivery ? (float) $delivery->cost : self::DEFAULT_DELIVERY_FEE;

        return response()->json([
            'success' => true,
            'status' => true,
            'message' => 'Promo code applied successfully',
            'data' => [
                'promo_code' => $this->promoCodePayload($promoCode),
                'cart' => [
                    'subtotal' => $this->money($subtotal),
                    'discount' => $this->money($discount),
                    'delivery_fee' => $this->money($deliveryFee),
                    'total' => $this->money(max(0, $subtotal - $discount) + $deliveryFee),
                ],
            ],
        ]);
    }

    private function findPromoCode(string $code): ?PromoCode
    {
        return PromoCode::where('code', trim($code))->first();
    }

    private function delivery(array $data): ?Delivery
    {
        $deliveryId = $data['delivery_id'] ?? $data['delivery'] ?? null;

        return $deliveryId ? Delivery::find($deliveryId) : null;
    }

    private function promoCodePayload(PromoCode $promoCode): array
    {
        return [
            'code' => $promoCode->code,
            'discount_type' => $promoCode->discount_type,
            'discount_value' => $this->money($promoCode->discount_value),
            'is_active' => (bool) $promoCode->is_active,
            'max_uses' => $promoCode->max_uses,
            'uses_count' => $promoCode->uses_count,
            'start_date' => optional($promoCode->start_date)->toDateString(),
            'end_date' => optional($promoCode->end_date)->toDateString(),
        ];
    }

    private function money(mixed $value): int|float
    {
        $number = (float) $value;

        return floor($number) == $number ? (int) $number : round($number, 2);
    }
}
