<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\User;
use App\Models\BorrowRecord;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBooks = Book::count();
        $totalMembers = User::where('role', 'member')->count();
        $activeBorrows = BorrowRecord::whereNull('returned_at')->count();
        $overdueBorrows = BorrowRecord::whereNull('returned_at')
            ->where('due_date', '<', Carbon::now())
            ->count();
        
        $recentBorrows = BorrowRecord::with(['book', 'user'])
            ->latest()
            ->take(10)
            ->get();
        
        $popularBooks = Book::withCount('borrowRecords')
            ->orderBy('borrow_records_count', 'desc')
            ->take(5)
            ->get();
        
        $lowStockBooks = Book::where('available_copies', '<=', 2)
            ->where('available_copies', '>', 0)
            ->get();
        
        $monthlyStats = [
            'borrowed' => BorrowRecord::whereMonth('borrowed_at', Carbon::now()->month)->count(),
            'returned' => BorrowRecord::whereMonth('returned_at', Carbon::now()->month)->count(),
        ];
        
        return view('admin.dashboard', compact(
            'totalBooks', 'totalMembers', 'activeBorrows', 'overdueBorrows',
            'recentBorrows', 'popularBooks', 'lowStockBooks', 'monthlyStats'
        ));
    }
}