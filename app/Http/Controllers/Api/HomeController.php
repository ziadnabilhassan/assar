<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Page;
use App\Models\Product;
use App\Models\Review;
use App\Models\Slider;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    public function index(): JsonResponse
    {
        $sliders = Slider::latest()->get([
            'id',
            'url',
            'image',
            'title',
            'text'
        ]);

        $banners = Banner::latest()->get([
            'id',
            'url',
            'image'
        ]);

        $categories = Category::withCount('products')
            ->latest()
            ->get([
                'id',
                'title',
                'image'
            ]);

        $products = Product::with([
                'oneVariant',
                'oneImage',
                'category'
            ])
            ->orderBy('is_featured', 'asc')
            ->orderBy('id', 'desc')
            ->limit(40)
            ->get([
                'id',
                'name',
                'image',
                'is_featured',
                'category_id'
            ]);

        $features = Product::with([
                'oneVariant',
                'oneImage',
                'category'
            ])
            ->orderBy('is_featured', 'desc')
            ->orderBy('id', 'desc')
            ->limit(40)
            ->get([
                'id',
                'name',
                'image',
                'is_featured',
                'category_id'
            ]);

        $reviews = Review::latest()->get();

        return response()->json([
            'status' => true,
            'message' => 'Home data fetched successfully',
            'data' => [
                'sliders' => $sliders,
                'banners' => $banners,
                'categories' => $categories,
                'products' => $products,
                'features' => $features,
                'reviews' => $reviews,
            ]
        ], 200);
    }

    public function page($id = 1): JsonResponse
    {
        $page = Page::findOrFail($id);

        return response()->json([
            'status' => true,
            'message' => 'Page fetched successfully',
            'data' => $page
        ], 200);
    }

    public function contacts(): JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => 'Contact page data',
            'data' => []
        ], 200);
    }
}
