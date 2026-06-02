<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Color;
use App\Models\Size;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Http\Request;

class ProductWebController extends Controller
{
    public function products(Request $request)
    {
        $products = Product::with(['oneVariant', 'category'])
            ->filter($request)
            ->latest()
            ->paginate(20);

        $features = Product::with(['oneVariant', 'oneImage'])
            ->orderBy('is_featured', 'desc')->orderBy('id', 'desc')
            ->limit(3)->get(['id', 'name', 'image']);

        return view('website.products', compact('products', 'features'));
    }
    public function productDetails($id)
    {
        $product = Product::with([
            'category',
            'images',
            'oneVariant.color'
        ])->findOrFail($id);

        $uniqueColors = $product->variants->pluck('color')->unique();

        $relatedProducts = Product::with(['oneVariant'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->inRandomOrder()
            ->limit(10)
            ->get();

        return view('website.product-details', compact('product', 'uniqueColors', 'relatedProducts'));
    }
    public function quickView($variantId)
    {
        $product = Product::with('oneVariant.color')
            ->whereRelation('oneVariant', 'id', $variantId)
            ->firstOrFail();
        return view('website.layouts.quick-view', compact('product'));
    }

    public function getUniqueSizesByColor($productId, $color_id)
    {
        $variants = Variant::with('size')
            ->where('product_id', $productId)
            ->where('color_id', $color_id)
            ->get();
        return response()->json($variants);
    }
}
