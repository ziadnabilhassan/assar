<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\SavedDesign;
use App\Models\Variant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CartController extends Controller
{
    private const DEFAULT_DELIVERY_FEE = 50;

    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'status' => true,
            'message' => 'Cart fetched successfully',
            'data' => $this->cartPayload($request->user()->id),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validateCartItem($request);
        $isCustomDesign = (bool) ($data['is_custom_design'] ?? false);

        if (! $isCustomDesign && empty($data['product_id'])) {
            return response()->json([
                'success' => false,
                'status' => false,
                'message' => 'product_id is required for normal products',
            ], 422);
        }

        if ($isCustomDesign && empty($data['design_id']) && empty($data['design_data'])) {
            return response()->json([
                'success' => false,
                'status' => false,
                'message' => 'Custom design cart items require design_id or design_data',
            ], 422);
        }

        if (! empty($data['design_id'])) {
            $ownsDesign = SavedDesign::where('user_id', $request->user()->id)
                ->where('id', $data['design_id'])
                ->exists();

            if (! $ownsDesign) {
                return response()->json([
                    'success' => false,
                    'status' => false,
                    'message' => 'Design not found',
                ], 404);
            }
        }

        $prepared = $this->prepareCartItemData($data, $isCustomDesign);

        $cartItem = CartItem::create(array_merge($prepared, [
            'user_id' => $request->user()->id,
        ]));

        return response()->json([
            'success' => true,
            'status' => true,
            'message' => 'Cart item added successfully',
            'data' => [
                'item' => $this->cartItemPayload($cartItem),
                'cart' => $this->cartPayload($request->user()->id),
            ],
        ], 201);
    }

    public function update(Request $request, CartItem $cartItem): JsonResponse
    {
        if ($cartItem->user_id !== $request->user()->id) {
            abort(404);
        }

        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
            'color' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:255',
        ]);

        if ($cartItem->variant_id) {
            $variant = Variant::find($cartItem->variant_id);

            if ($variant && $data['quantity'] > $variant->quantity) {
                return response()->json([
                    'success' => false,
                    'status' => false,
                    'message' => __('main.Requested quantity exceeds available stock'),
                ], 400);
            }
        }

        $cartItem->update($data);

        return response()->json([
            'success' => true,
            'status' => true,
            'message' => 'Cart item updated successfully',
            'data' => [
                'item' => $this->cartItemPayload($cartItem->refresh()),
                'cart' => $this->cartPayload($request->user()->id),
            ],
        ]);
    }

    public function destroy(Request $request, CartItem $cartItem): JsonResponse
    {
        if ($cartItem->user_id !== $request->user()->id) {
            abort(404);
        }

        $cartItem->delete();

        return response()->json([
            'success' => true,
            'status' => true,
            'message' => 'Cart item removed successfully',
            'data' => $this->cartPayload($request->user()->id),
        ]);
    }

    public function clear(Request $request): JsonResponse
    {
        CartItem::where('user_id', $request->user()->id)->delete();

        return response()->json([
            'success' => true,
            'status' => true,
            'message' => 'Cart cleared successfully',
            'data' => $this->cartPayload($request->user()->id),
        ]);
    }

    private function validateCartItem(Request $request): array
    {
        return $request->validate([
            'product_id' => 'nullable|integer|exists:products,id',
            'variant_id' => 'nullable|integer|exists:variants,id',
            'name' => 'nullable|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'color' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:255',
            'image_url' => 'nullable|string|max:2048',
            'preview_image_url' => 'nullable|string|max:2048',
            'is_custom_design' => 'required|boolean',
            'design_id' => [
                'nullable',
                'integer',
                Rule::exists('saved_designs', 'id'),
            ],
            'design_data' => 'nullable|array',
        ]);
    }

    private function prepareCartItemData(array $data, bool $isCustomDesign): array
    {
        $product = null;
        $variant = null;
        $savedDesign = null;

        if ($isCustomDesign && ! empty($data['design_id'])) {
            $savedDesign = SavedDesign::find($data['design_id']);
        }

        if (! empty($data['variant_id'])) {
            $variant = Variant::with(['product', 'color', 'size'])->find($data['variant_id']);
            $product = $variant?->product;
        }

        if (
            $variant
            && ! empty($data['product_id'])
            && (int) $variant->product_id !== (int) $data['product_id']
        ) {
            abort(422, 'variant_id does not belong to product_id');
        }

        if (! $product && ! empty($data['product_id'])) {
            $product = Product::with(['oneVariant.color', 'oneVariant.size'])->find($data['product_id']);
            $variant = $product?->oneVariant;
        }

        if (! $isCustomDesign && $variant && $data['quantity'] > $variant->quantity) {
            abort(400, __('main.Requested quantity exceeds available stock'));
        }

        return [
            'product_id' => $data['product_id'] ?? $product?->id,
            'variant_id' => $data['variant_id'] ?? $variant?->id,
            'design_id' => $data['design_id'] ?? null,
            'name' => $data['name'] ?? $this->productName($product) ?? 'Custom product',
            'price' => $data['price'] ?? $variant?->price ?? 0,
            'quantity' => $data['quantity'],
            'color' => $data['color'] ?? $this->variantColor($variant),
            'size' => $data['size'] ?? $this->variantSize($variant),
            'image_url' => $data['image_url'] ?? $product?->image,
            'preview_image_url' => $data['preview_image_url'] ?? $savedDesign?->preview_image_url ?? $savedDesign?->preview_image,
            'is_custom_design' => $isCustomDesign,
            'design_data' => $isCustomDesign ? ($data['design_data'] ?? $savedDesign?->design_data) : null,
        ];
    }

    private function cartPayload(int $userId): array
    {
        $items = CartItem::where('user_id', $userId)
            ->latest()
            ->get()
            ->map(fn (CartItem $item) => $this->cartItemPayload($item))
            ->values();

        $subtotal = $items->sum(fn (array $item) => $item['price'] * $item['quantity']);
        $deliveryFee = $items->isEmpty() ? 0 : self::DEFAULT_DELIVERY_FEE;

        return [
            'items' => $items,
            'subtotal' => $this->money($subtotal),
            'delivery_fee' => $this->money($deliveryFee),
            'total' => $this->money($subtotal + $deliveryFee),
        ];
    }

    private function cartItemPayload(CartItem $item): array
    {
        return [
            'id' => $item->id,
            'product_id' => $item->product_id,
            'variant_id' => $item->variant_id,
            'name' => $item->name,
            'price' => $this->money($item->price),
            'quantity' => $item->quantity,
            'image_url' => $item->image_url,
            'preview_image_url' => $item->preview_image_url,
            'color' => $item->color,
            'size' => $item->size,
            'is_custom_design' => (bool) $item->is_custom_design,
            'design_id' => $item->design_id,
            'design_data' => $item->design_data,
        ];
    }

    private function productName(?Product $product): ?string
    {
        if (! $product) {
            return null;
        }

        return $product->getTranslation('name', 'ar', false)
            ?: $product->getTranslation('name', 'en', false)
            ?: (is_string($product->name) ? $product->name : null);
    }

    private function variantColor(?Variant $variant): ?string
    {
        return $variant?->color?->getTranslation('name', 'ar', false)
            ?: $variant?->color?->getTranslation('name', 'en', false);
    }

    private function variantSize(?Variant $variant): ?string
    {
        return $variant?->size?->name ?? null;
    }

    private function money(mixed $value): int|float
    {
        $number = (float) $value;

        return floor($number) == $number ? (int) $number : round($number, 2);
    }
}
