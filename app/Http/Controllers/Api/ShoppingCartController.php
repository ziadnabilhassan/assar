<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Variant;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShoppingCartController extends Controller
{
    public function index(): JsonResponse
    {
        $cartItems = Cart::getContent();

        foreach ($cartItems as $item) {

            $product = Product::whereHas('variants', function ($query) use ($item) {

                $query->where(
                    'id',
                    $item->attributes->variant_id
                );

            })->with([
                'oneVariant' => function ($query) use ($item) {

                    $query->where(
                        'id',
                        $item->attributes->variant_id
                    )->select(
                        'size',
                        'product_id',
                        'price',
                        'quantity'
                    );
                }
            ])->find($item->attributes->product_id);

            if ($product) {

                if (
                    $item->price !=
                    $product->oneVariant->price
                ) {

                    Cart::update($item->id, [
                        'price' => $product->oneVariant->price,
                    ]);
                }

            } else {

                Cart::remove($item->id);
            }
        }

        $categories = Category::withCount('products')
            ->latest()
            ->get([
                'id',
                'title',
                'image'
            ]);

        return response()->json([
            'status' => true,
            'message' => 'Cart fetched successfully',
            'data' => [
                'cartItems' => Cart::getContent(),
                'cartCount' => Cart::getContent()->count(),
                'cartSubtotal' => Cart::getSubTotal(),
                'categories' => $categories,
            ]
        ]);
    }

    public function addToCart(
        Request $request
    ): JsonResponse {

        $request->validate([
            'id' => 'required|integer|exists:products,id',
            'variant' => 'required|integer|exists:variants,id',
            'qty' => 'nullable|integer|min:1',
        ]);

        $productId = $request->input('id');

        $variantId = $request->input('variant');

        $quantity = $request->input('qty', 1);

        $productVariant = Variant::where(
                'id',
                $variantId
            )
            ->where(
                'product_id',
                $productId
            )
            ->with([
                'product.category',
                'color'
            ])
            ->first();

        if (!$productVariant) {

            return response()->json([
                'status' => false,
                'message' => __('main.no products')
            ], 404);
        }

        $id = $productId . '_' . $variantId;

        $cartItem = Cart::get($id);

        $totalQuantity = $quantity;

        if ($cartItem) {

            $totalQuantity += $cartItem->quantity;
        }

        if (
            $totalQuantity >
            $productVariant->quantity
        ) {

            return response()->json([
                'status' => false,
                'message' => __(
                    'main.Requested quantity exceeds available stock'
                )
            ], 400);
        }

        $name = $productVariant
            ->product
            ->getTranslations('name');

        $category = $productVariant
            ->product
            ?->category
            ?->getTranslations('title');

        $color = $productVariant
            ->color
            ?->getTranslations('name');

        $size = $productVariant->size;

        Cart::add([
            'id' => $id,
            'name' => $name,
            'price' => $productVariant->price,
            'quantity' => $quantity,

            'attributes' => [
                'variant_id' => $productVariant->id,
                'product_id' => $productVariant->product_id,
                'color' => $color,
                'size' => $size,
                'category' => $category,
                'image' => $productVariant
                    ->product
                    ?->image,
            ],
        ]);

        return response()->json([
            'status' => true,
            'message' => __(
                'main.Product added to cart successfully'
            ),
            'data' => [
                'cartCount' => Cart::getContent()->count(),
                'cartSubtotal' => Cart::getSubTotal(),
            ]
        ]);
    }

    public function update(
        Request $request
    ): JsonResponse {

        $request->validate([
            'id' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        $productId = $request->input('id');

        $quantity = $request->input('quantity');

        $cartItem = Cart::get($productId);

        if (!$cartItem) {

            return response()->json([
                'status' => false,
                'message' => __('main.Item not found in cart')
            ], 404);
        }

        $product = Product::whereHas(
            'variants',
            function ($query) use ($cartItem) {

                $query->where(
                    'id',
                    $cartItem->attributes->variant_id
                );
            }
        )->with([
            'oneVariant' => function ($query) use ($cartItem) {

                $query->where(
                    'id',
                    $cartItem->attributes->variant_id
                )->select(
                    'size',
                    'product_id',
                    'price',
                    'quantity'
                );
            }
        ])->find($cartItem->attributes->product_id);

        if (
            $quantity >
            $product->oneVariant->quantity
        ) {

            return response()->json([
                'status' => false,
                'message' => __(
                    'main.Requested quantity exceeds available stock'
                )
            ], 400);
        }

        Cart::update($productId, [
            'quantity' => [
                'relative' => false,
                'value' => $quantity,
            ],
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Quantity updated successfully',
            'data' => [
                'cartCount' => Cart::getContent()->count(),
                'cartSubtotal' => Cart::getSubTotal(),
                'cartItems' => Cart::getContent(),
            ]
        ]);
    }

    public function remove(
        Request $request
    ): JsonResponse {

        $request->validate([
            'id' => 'required|string',
        ]);

        $productId = $request->input('id');

        $cartItem = Cart::get($productId);

        if (!$cartItem) {

            return response()->json([
                'status' => false,
                'message' => 'Item not found in cart'
            ], 404);
        }

        Cart::remove($productId);

        return response()->json([
            'status' => true,
            'message' => 'Item removed successfully',
            'data' => [
                'cartCount' => Cart::getContent()->count(),
                'cartSubtotal' => Cart::getSubTotal(),
                'cartItems' => Cart::getContent(),
            ]
        ]);
    }
}
