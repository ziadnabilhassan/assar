<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryType;
use App\Models\Color;
use App\Models\Product;
use App\Models\gender;
use App\Models\Variant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductWebController extends Controller
{
    public $paginate = 20;

    protected function getCommonData(): array
    {
        return [
            'categoryTypes' => CategoryType::select('id', 'title')
                ->with([
                    'categories' => function ($query) {
                        $query->select(
                            'id',
                            'category_type_id',
                            'title',
                            'image'
                        )->withCount('products');
                    }
                ])
                ->withCount('products')
                ->latest()
                ->get(),

            'colors' => Color::select(
                'id',
                'name',
                'code'
            )->get()
        ];
    }

    public function categoryType(Request $request, $id): JsonResponse
    {
        $categoryType = CategoryType::with([
            'categories' => function ($query) {
                $query->select(
                    'id',
                    'category_type_id',
                    'title',
                    'image'
                )->withCount('products');
            }
        ])->findOrFail($id);

        $products = $categoryType->products()
            ->with([
                'oneVariant',
                'oneImage',
                'variants' => function ($query) {
                    $query->select(
                        'product_id',
                        'color_id'
                    )->with('color')->distinct('color_id');
                }
            ])
            ->filter($request)
            ->paginate($this->paginate);

        $categories = Category::withCount('products')
            ->where('category_type_id', $id)
            ->latest()
            ->get([
                'id',
                'title',
                'image'
            ]);

        return response()->json([
            'status' => true,
            'message' => 'Category type products fetched successfully',
            'data' => array_merge([
                'categoryType' => $categoryType,
                'categories' => $categories,
                'products' => $products,
            ], $this->getCommonData())
        ]);
    }

    public function category(Request $request, $id): JsonResponse
    {
        $category = Category::with('categoryType')
            ->findOrFail($id);

        $products = $category->products()
            ->with([
                'oneVariant',
                'oneImage',
                'variants' => function ($query) {
                    $query->select(
                        'product_id',
                        'color_id'
                    )->with('color')->distinct('color_id');
                }
            ])
            ->filter($request)
            ->paginate($this->paginate);

        $categories = Category::withCount('products')
            ->latest()
            ->get([
                'id',
                'title',
                'image'
            ]);

        return response()->json([
            'status' => true,
            'message' => 'Category products fetched successfully',
            'data' => array_merge([
                'category' => $category,
                'categories' => $categories,
                'products' => $products,
            ], $this->getCommonData())
        ]);
    }

    // public function gender(Request $request, $id): JsonResponse
    // {


    //     $products = $gender->products()
    //         ->with([
    //             'oneVariant',
    //             'oneImage',
    //             'variants' => function ($query) {
    //                 $query->select(
    //                     'product_id',
    //                     'color_id'
    //                 )->with('color')->distinct('color_id');
    //             }
    //         ])
    //         ->filter($request)
    //         ->paginate($this->paginate);

    //     $categories = Category::withCount('products')
    //         ->latest()
    //         ->get([
    //             'id',
    //             'title',
    //             'image'
    //         ]);


    //         ->latest()
    //         ->get([
    //             'id',
    //             'title',
    //             'image'
    //         ]);

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Gender products fetched successfully',
    //         'data' => array_merge([


    //             'categories' => $categories,
    //             'products' => $products,
    //         ], $this->getCommonData())
    //     ]);
    // }

    public function products(Request $request): JsonResponse
    {
        $products = Product::with([
            'oneVariant',
            'oneImage',
            'variants' => function ($query) {
                $query->select(
                    'product_id',
                    'color_id'
                )->with('color')->distinct('color_id');
            }
        ])
            ->filter($request)
            ->paginate($this->paginate);

        $categories = Category::withCount('products')
            ->latest()
            ->get([
                'id',
                'title',
                'image'
            ]);

        return response()->json([
            'status' => true,
            'message' => 'Products fetched successfully',
            'data' => array_merge([
                'products' => $products,
                'categories' => $categories,
            ], $this->getCommonData())
        ]);
    }

    public function productDetails($id): JsonResponse
    {
        $product = Product::with([
            'category.categoryType',
            'images',
            'variants.color'
        ])->findOrFail($id);

        $features = Product::with([
            'oneVariant',
            'oneImage',
            'variants' => function ($query) {
                $query->select(
                    'product_id',
                    'color_id'
                )->with('color')->distinct('color_id');
            }
        ])
            ->where('is_featured', 1)
            ->latest()
            ->limit(12)
            ->get([
                'id',
                'name',
                'image'
            ]);

        $uniqueSizes = $product->variants
            ->pluck('size')
            ->unique()
            ->values();

        $uniqueColors = $product->variants
            ->pluck('color')
            ->unique('id')
            ->values();

        $categories = Category::withCount('products')
            ->latest()
            ->get([
                'id',
                'title',
                'image'
            ]);

        $relatedProducts = Product::with([
                'oneVariant',
                'oneImage'
            ])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->inRandomOrder()
            ->limit(10)
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Product details fetched successfully',
            'data' => [
                'product' => $product,
                'features' => $features,
                'uniqueSizes' => $uniqueSizes,
                'uniqueColors' => $uniqueColors,
                'relatedProducts' => $relatedProducts,
                'categories' => $categories,
            ]
        ]);
    }

    public function quickView($variantId): JsonResponse
    {
        $product = Product::with('oneVariant.color')
            ->whereRelation('oneVariant', 'id', $variantId)
            ->firstOrFail();

        return response()->json([
            'status' => true,
            'message' => 'Quick view fetched successfully',
            'data' => $product
        ]);
    }

    public function getUniqueColorsByVariantSize($productId, $colorId): JsonResponse
    {
        $variants = Variant::query()
            ->where('product_id', $productId)
            ->where('color_id', $colorId)
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Variants fetched successfully',
            'data' => $variants
        ]);
    }
}
