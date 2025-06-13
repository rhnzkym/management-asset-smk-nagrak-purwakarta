<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Room;
use App\Models\Item;
use App\Models\ItemLoan;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Total buildings = jumlah lokasi
        $totalBuildings = Location::count();

        // Total rooms
        $totalRooms = Room::count();

        // Total items (sum qty)
        $totalItems = Item::sum('qty');

        // Good condition items count
        $goodCondition = Item::sum('good_qty');

        // Damaged assets
        $brokenItems = Item::sum('broken_qty');
        $lostItems = Item::sum('lost_qty');
        $damagedAssets = $brokenItems + $lostItems;
        
        // Items condition data for pie chart
        $itemConditionData = [
            'good' => $goodCondition,
            'broken' => $brokenItems,
            'lost' => $lostItems
        ];
        
        // Loan status count for chart
        $pendingLoans = ItemLoan::where('status', 'pending')->count();
        $borrowedLoans = ItemLoan::where('status', 'pinjam')->count();
        $returnedLoans = ItemLoan::where('status', 'kembali')->count();
        
        $loanStatusData = [
            'pending' => $pendingLoans,
            'pinjam' => $borrowedLoans,
            'kembali' => $returnedLoans
        ];
        
        // Get top 5 categories with most items
        $topCategories = Category::select('categories.id', 'categories.cat_name')
            ->selectRaw('SUM(items.qty) as total_items')
            ->join('items', 'categories.id', '=', 'items.cat_id')
            ->where('items.is_active', true)
            ->groupBy('categories.id', 'categories.cat_name')
            ->orderByDesc('total_items')
            ->limit(5)
            ->get();
            
        // Monthly loan trends - last 6 months
        $monthlyLoans = [];
        $labels = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $count = ItemLoan::whereMonth('tanggal_pinjam', $month->month)
                ->whereYear('tanggal_pinjam', $month->year)
                ->count();
                
            $monthlyLoans[] = $count;
            $labels[] = $month->format('M Y');
        }
        
        $monthlyLoanData = [
            'labels' => $labels,
            'data' => $monthlyLoans
        ];
        
        // Recent activities = recent borrow records with relations loaded
        $recentActivities = ItemLoan::with(['user', 'item'])
            ->latest('tanggal_pinjam')
            ->limit(5)
            ->get();

        return view('pages.dashboard', compact(
            'totalBuildings',
            'totalRooms',
            'totalItems',
            'goodCondition',
            'damagedAssets',
            'recentActivities',
            'itemConditionData',
            'loanStatusData',
            'topCategories',
            'monthlyLoanData'
        ));
    }
}
