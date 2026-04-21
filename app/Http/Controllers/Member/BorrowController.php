<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BorrowRecord;
use Carbon\Carbon;

class BorrowController extends Controller
{
    public function borrow(Book $book)
    {
        // Check if book is available
        if ($book->available_copies <= 0) {
            return back()->with('error', 'This book is not available right now!');
        }
        
        $user = auth()->user();
        
        // Check maximum borrow limit (max 5 books)
        $activeBorrows = BorrowRecord::where('user_id', $user->id)
            ->whereNull('returned_at')
            ->count();
            
        if ($activeBorrows >= 5) {
            return back()->with('error', 'You have reached the maximum borrow limit (5 books)! Please return some books first.');
        }
        
        // Check if user already has this book
        $existing = BorrowRecord::where('book_id', $book->id)
            ->where('user_id', $user->id)
            ->whereNull('returned_at')
            ->exists();
            
        if ($existing) {
            return back()->with('error', 'You already have this book!');
        }
        
        // Create borrow record
        BorrowRecord::create([
            'book_id' => $book->id,
            'user_id' => $user->id,
            'borrowed_at' => Carbon::now(),
            'due_date' => Carbon::now()->addDays(14), // 14 days to return
            'status' => 'borrowed'
        ]);
        
        // Decrease available copies
        $book->available_copies -= 1;
        $book->save();
        
        return redirect()->route('member.dashboard')
            ->with('success', 'Book borrowed successfully! Due date: ' . Carbon::now()->addDays(14)->format('d M, Y'));
    }
    
    public function return($id)
    {
        $borrow = BorrowRecord::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();
            
        if ($borrow->returned_at) {
            return back()->with('error', 'Book already returned!');
        }
        
        // Calculate fine if overdue
        $fine = 0;
        if (Carbon::now()->gt($borrow->due_date)) {
            $daysOverdue = Carbon::now()->diffInDays($borrow->due_date);
            $fine = $daysOverdue * 5; // 5 Taka per day
        }
        
        // Update borrow record
        $borrow->returned_at = Carbon::now();
        $borrow->fine_amount = $fine;
        $borrow->status = $fine > 0 ? 'overdue' : 'returned';
        $borrow->save();
        
        // Increase available copies
        $book = $borrow->book;
        $book->available_copies += 1;
        $book->save();
        
        $message = 'Book returned successfully!';
        if ($fine > 0) {
            $message .= ' Fine: ৳' . number_format($fine, 2);
        }
        
        return redirect()->route('member.dashboard')->with('success', $message);
    }
}