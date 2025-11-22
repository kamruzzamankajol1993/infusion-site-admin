<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use Carbon\Carbon;

// --- Import ALL necessary models ---
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Models\ProductReview;
use App\Models\ContactUsMessage; // Messages
use App\Models\User;      // Admins/Staff
use App\Models\StoreMainBanner;
// Use specific module models to count data
use App\Models\DigitalMarketingSolution;
use App\Models\GraphicDesignSolution;
use App\Models\FacebookAdsPricingPackage;
use App\Models\UkPricingPackage;
use App\Models\VpsPackage;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        try {
            // --- 1. Ecommerce Metrics (Top Row) ---
            $totalOrders = Order::count();
            $totalRevenue = Order::where('payment_status', 'paid')->sum('grand_total');
            $totalProducts = Product::count();
            $totalCustomers = User::where('user_type',1)->count();

            // --- 2. Pending Actions (Second Row) ---
            $pendingOrders = Order::where('order_status', 'pending')->count();
            $pendingReviews = ProductReview::where('status', 0)->count();
            $unreadMessages = ContactUsMessage::count(); // Assuming you might add 'is_read' later, for now count all
            $lowStockProducts = Product::where('stock_quantity', '<', 5)->count();

            // --- 3. Content Summary (Third Row) ---
            $activeBanners = StoreMainBanner::where('status', 1)->count();
            
            // Count total service packages across all modules
            $totalPackages = DigitalMarketingSolution::count() + 
                             GraphicDesignSolution::count() + 
                             FacebookAdsPricingPackage::count() + 
                             UkPricingPackage::count() + 
                             VpsPackage::count();

            // --- 4. Chart Data: Monthly Revenue (Last 6 Months) ---
            $revenueData = Order::select(
                DB::raw('sum(grand_total) as sum'), 
                DB::raw("DATE_FORMAT(created_at,'%M') as month")
            )
            ->where('payment_status', 'paid')
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('created_at', 'ASC') // Order by date to show sequence correctly (might need adjustment for strict date sorting)
            ->pluck('sum', 'month');

            // Fill in missing months if needed, for now simple pluck
            $chartLabels = $revenueData->keys();
            $chartValues = $revenueData->values();

            // --- 5. Recent Orders Table ---
            $recentOrders = Order::with('items')
                ->latest()
                ->limit(5)
                ->get();

            return view('admin.dashboard.index', [
                // Metrics
                'totalOrders' => $totalOrders,
                'totalRevenue' => $totalRevenue,
                'totalProducts' => $totalProducts,
                'totalCustomers' => $totalCustomers,
                
                // Pending
                'pendingOrders' => $pendingOrders,
                'pendingReviews' => $pendingReviews,
                'unreadMessages' => $unreadMessages,
                'lowStockProducts' => $lowStockProducts,
                
                // Content
                'activeBanners' => $activeBanners,
                'totalPackages' => $totalPackages,

                // Data
                'recentOrders' => $recentOrders,
                'chartLabels' => $chartLabels,
                'chartValues' => $chartValues,
            ]);

        } catch (Exception $e) {
            Log::error('Failed to load dashboard data: ' . $e->getMessage());
            // Return view with zeroed data to prevent crash
            return view('admin.dashboard.index', [
                'totalOrders' => 0, 'totalRevenue' => 0, 'totalProducts' => 0, 'totalCustomers' => 0,
                'pendingOrders' => 0, 'pendingReviews' => 0, 'unreadMessages' => 0, 'lowStockProducts' => 0,
                'activeBanners' => 0, 'totalPackages' => 0,
                'recentOrders' => collect(), 'chartLabels' => [], 'chartValues' => [],
            ])->with('error', 'Could not load dashboard statistics.');
        }
    }
}