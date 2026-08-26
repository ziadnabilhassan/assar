<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $items = Wishlist::query()
            ->where('user_id', $request->user()->id)
            ->with([
                'product' => fn ($query) => $query->with(['oneVariant', 'variants']),
            ])
            ->latest()
            ->get()
            ->map(fn (Wishlist $wishlist) => $this->wishlistPayload($wishlist))
            ->filter()
            ->values();

        return response()->json([
            'data' => $items,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
        ]);

        Wishlist::firstOrCreate([
            'user_id' => $request->user()->id,
            'product_id' => $data['product_id'],
        ]);

        return response()->json([
            'message' => 'Product added to wishlist',
        ]);
    }

    public function destroy(Request $request, int $productId): JsonResponse
    {
        Wishlist::where('user_id', $request->user()->id)
            ->where('product_id', $productId)
            ->delete();

        return response()->json([
            'message' => 'Product removed from wishlist',
        ]);
    }

    private function wishlistPayload(Wishlist $wishlist): ?array
    {
        $product = $wishlist->product;

        if (! $product instanceof Product) {
            return null;
        }

        return [
            'id' => $wishlist->id,
            'product_id' => $product->id,
            'name' => $this->productName($product),
            'price' => $this->money($product->oneVariant?->price ?? 0),
            'image' => $product->image,
            'in_stock' => $product->variants->contains(fn ($variant) => $variant->quantity > 0),
        ];
    }

    private function productName(Product $product): ?string
    {
        return $product->getTranslation('name', 'ar', false)
            ?: $product->getTranslation('name', 'en', false)
            ?: (is_string($product->name) ? $product->name : null);
    }

    private function money(mixed $value): int|float
    {
        $number = (float) $value;

        return floor($number) == $number ? (int) $number : round($number, 2);
    }
}
