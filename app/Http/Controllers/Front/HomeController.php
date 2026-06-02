<?php

namespace App\Http\Controllers\Front;

use App\Models\Page;
use App\Models\Banner;
use App\Models\Review;
use App\Models\Slider;
use App\Models\Category;
use App\Models\Product;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $sliders = Slider::latest()->get(['url', 'image','title','text']);
        $banners = Banner::latest()->get(['url', 'image']);
$categories  = Category::withCount(['products'])->latest()->get(['id', 'title', 'image']);
        $products = Product::with(['oneVariant', 'oneImage', 'category'])
            ->orderBy('is_featured', 'asc')->orderBy('id', 'desc')
            ->limit(40)->get(['id', 'name', 'image', 'is_featured', 'category_id']);

        $features = Product::with(['oneVariant', 'oneImage', 'category'])
            ->orderBy('is_featured', 'desc')->orderBy('id', 'desc')
            ->limit(40)->get(['id', 'name', 'image', 'is_featured', 'category_id']);

        $reviews = Review::latest()->get();

        return view('website.index', compact(
            'sliders',
            'banners',
            'products',
            'features',
            'reviews',
            'categories'
        ));
    }
    public function page($id = 1)
    {
        $page = Page::findOrFail($id);
        return view('website.about', compact('page'));
    }
    public function contacts()
    {
        return view('website.contact');
    }
}
