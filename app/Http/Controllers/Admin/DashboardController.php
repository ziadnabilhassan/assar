<?php

// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\Order;
use App\Models\Slider;
use App\Models\Variant;
use App\Models\CartItem;
use App\Models\DesignSticker;
use App\Models\SavedDesign;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {

        // =========================
        // COUNTS
        // =========================

        $totalProducts = Product::count();

        $featuredProducts = Product::where('is_featured', 1)->count();

        $normalProducts = Product::where('is_featured', 0)->count();

        $totalCategories = Category::count();

        $totalReviews = Review::count();

        $totalSliders = Slider::count();

        $totalDesignStickers = DesignSticker::count();

        $totalSavedDesigns = SavedDesign::count();

        $totalCartItems = CartItem::count();

        $totalOrders = Order::count();

        $pendingInstapayProofs = Order::where('payment_provider', 'instapay')
            ->where('payment_status', 'awaiting_transfer_proof')
            ->count();

        $uploadedInstapayProofs = Order::where('payment_provider', 'instapay')
            ->whereIn('payment_status', ['proof_uploaded', 'pending_review'])
            ->count();

        $recentInstapayOrders = Order::with('user')
            ->where('payment_provider', 'instapay')
            ->latest()
            ->take(8)
            ->get();


        // =========================
        // AVG RATING
        // =========================

        $avgRating = round(5, 1);


        // =========================
        // TOP CATEGORIES
        // =========================

        $topCategories = Category::withCount('products')
            ->orderByDesc('products_count')
            ->take(5)
            ->get();


        // =========================
        // LATEST PRODUCTS
        // =========================

        $latestProducts = Product::latest()
            ->take(10)
            ->get();


        // =========================
        // TOP COLORS
        // =========================

        $topColors = Variant::select('color_id')
            ->with('color')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('color_id')
            ->orderByDesc('total')
            ->take(10)
            ->get();


        // =========================
        // MONTHLY PRODUCTS
        // =========================

          $monthlyProducts = Order::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as total')
            ->where('created_at', '>=', Carbon::now()->subMonths(6)->startOfMonth())
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $months = [];
        $monthsCount = [];

        foreach ($monthlyProducts as $month) {

            $months[] = Carbon::create()
                ->month($month->month)
                ->format('F');

            $monthsCount[] = $month->total;
        }


        return view('dashboard', compact(
            'totalProducts',
            'featuredProducts',
            'normalProducts',
            'totalCategories',
            'totalReviews',
            'totalSliders',
            'totalDesignStickers',
            'totalSavedDesigns',
            'totalCartItems',
            'totalOrders',
            'pendingInstapayProofs',
            'uploadedInstapayProofs',
            'recentInstapayOrders',
            'avgRating',
            'topCategories',
            'latestProducts',
            'topColors',
            'months',
            'monthsCount'
        ));
    }
}
