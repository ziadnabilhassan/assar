<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::orderBy('id', 'desc')->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::get();
        $colors = Color::get();
        $sizes = Size::get();
        return view('admin.products.create', compact('categories', 'colors', 'sizes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'image' => 'required|image',
            'name.*' => 'required|string',
            'description.*' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'is_featured' => 'nullable|in:1',
        ]);
        $variants = $request->validate([
            'color_id' => 'required|array',
            'color_id.*' => 'required|exists:colors,id',
            'size_id' => 'required|array',
            'size_id.*' => 'required|exists:sizes,id',
            'quantity.*' => 'required|integer',
            'price.*' => 'required|numeric',
            'old_price.*' => 'nullable|numeric',
            'variant_images' => 'nullable|array',
            'variant_images.*' => 'nullable|array',
            'variant_images.*.*' => 'nullable|image',
        ]);

        $variantCombinations = [];
        foreach ($request->size_id as $key => $sizeId) {
            $colorId = $request->color_id[$key];
            $combination = $sizeId . '-' . $colorId;
            if (in_array($combination, $variantCombinations)) {
                return back()->withErrors(['variant_error' => 'Each variant must have a unique size and color.']);
            }
            $variantCombinations[] = $combination;
        }

        unset($data['imgs']);
        $fileName = Helper::storeImage($request->file('image'), 'products');
        $data['image'] = $fileName;

        try {
            DB::beginTransaction();

            $product = Product::create($data);

            // store variants
            foreach ($variants['quantity'] as $key => $quantity) {
                $product->variants()->create([
                    'size_id' => $variants['size_id'][$key],
                    'color_id' => $variants['color_id'][$key],
                    'quantity' => $quantity,
                    'price' => $variants['price'][$key],
                    'old_price' => $variants['old_price'][$key],
                ]);

                // Store variant images
                if (isset($variants['variant_images'][$key]) && is_array($variants['variant_images'][$key])) {
                    foreach ($variants['variant_images'][$key] as $image) {
                        $imagePath = Helper::storeImage($image, 'product_images');
                        $product->images()->create([
                            'image' => $imagePath,
                            'color_id' => $variants['color_id'][$key],
                        ]);
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
        return redirect()->route('products.index');
    }

    public function show(string $id)
    {
        //
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::get();
        $colors = Color::get();
        $sizes = Size::get();
        return view('admin.products.edit', compact('product', 'categories', 'colors', 'sizes'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'image' => 'nullable|image',
            'name.*' => 'required|string',
            'description.*' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'is_featured' => 'nullable|in:1',
        ]);

        $variants = $request->validate([
            'color_id' => 'required|array',
            'color_id.*' => 'required|exists:colors,id',
            'size_id' => 'required|array',
            'size_id.*' => 'required|exists:sizes,id',
            'quantity.*' => 'required|integer|min:0',
            'price.*' => 'required|numeric|min:0',
            'old_price.*' => 'nullable|numeric|min:0',
        ]);
        $data['is_featured'] = $data['is_featured'] ?? 0;

        $variantCombinations = [];
        foreach ($request->size_id as $key => $sizeId) {
            $colorId = $request->color_id[$key];
            $combination = $sizeId . '-' . $colorId;
            if (in_array($combination, $variantCombinations)) {
                return back()->withErrors(['variant_error' => 'Each variant must have a unique size and color.']);
            }
            $variantCombinations[] = $combination;
        }

        try {
            DB::beginTransaction();

            if ($request->hasFile('image')) {
                $fileName = Helper::updateImage($request->file('image'), $product->image, 'products');
                $data['image'] = $fileName;
            }

            // Update product details
            $product->update($data);


            // Sync Variants
            $existingVariantIds = $product->variants->pluck('id')->toArray();
            $requestVariantIds = $request->input('variant_id', []);

            // Remove variants that are not in the request
            $variantsToDelete = array_diff($existingVariantIds, $requestVariantIds);
            $product->variants()->whereIn('id', $variantsToDelete)->delete();

            foreach ($variants['quantity'] as $key => $quantity) {
                $product->variants()->updateOrCreate(
                    ['id' => $requestVariantIds[$key]], // Find by ID if exists
                    [
                        'size_id' => $variants['size_id'][$key],
                        'color_id' => $variants['color_id'][$key],
                        'quantity' => $quantity,
                        'price' => $variants['price'][$key],
                        'old_price' => $variants['old_price'][$key],
                    ]
                );
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }


    public function destroy(Request $request, $id)
    {
        $product = Product::with('images')->findOrFail($request->id);
        $oldImage = $product->image;
        Helper::deleteImage($oldImage);

        // delete all image for this product
        foreach ($product->images as $image) {
            Helper::deleteImage($image->image);
        }
        $product->delete();
        return back();
    }
}
