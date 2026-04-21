<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\BorrowRecord;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Currently borrowed books
        $activeBorrows = BorrowRecord::with('book')
            ->where('user_id', $user->id)
            ->whereNull('returned_at')
            ->get();
            
        // Borrow history
        $borrowHistory = BorrowRecord::with('book')
            ->where('user_id', $user->id)
            ->whereNotNull('returned_at')
            ->latest()
            ->paginate(10);
        
        // Count overdue books
        $overdueCount = $activeBorrows->filter(function($borrow) {
            return Carbon::now()->gt($borrow->due_date);
        })->count();
        
        // Calculate total fine
        $totalFine = $activeBorrows->sum(function($borrow) {
            return $borrow->calculateFine();
        });
        
        // Statistics
        $stats = [
            'total_borrowed' => BorrowRecord::where('user_id', $user->id)->count(),
            'active_borrows' => $activeBorrows->count(),
            'overdue' => $overdueCount,
            'total_fine' => $totalFine,
        ];
        
        return view('member.dashboard', compact('activeBorrows', 'borrowHistory', 'stats'));
    }
}