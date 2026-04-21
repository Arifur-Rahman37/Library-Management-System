<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\User;
use App\Models\BorrowRecord;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BorrowController extends Controller
{
    public function index(Request $request)
    {
        $query = BorrowRecord::with(['book', 'user']);
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        $borrows = $query->latest()->paginate(20);
        $stats = [
            'total' => BorrowRecord::count(),
            'active' => BorrowRecord::whereNull('returned_at')->count(),
            'overdue' => BorrowRecord::whereNull('returned_at')->where('due_date', '<', Carbon::now())->count(),
            'returned' => BorrowRecord::whereNotNull('returned_at')->count(),
        ];
        
        return view('admin.borrows.index', compact('borrows', 'stats'));
    }

    public function create()
    {
        $books = Book::where('available_copies', '>', 0)->get();
        $members = User::where('role', 'member')->where('is_active', true)->get();
        return view('admin.borrows.create', compact('books', 'members'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'user_id' => 'required|exists:users,id',
            'due_date' => 'required|date|after:today'
        ]);

        $book = Book::findOrFail($request->book_id);
        
        if ($book->available_copies <= 0) {
            return back()->with('error', 'No copies available!');
        }

        $activeBorrows = BorrowRecord::where('user_id', $request->user_id)
            ->whereNull('returned_at')
            ->count();
            
        if ($activeBorrows >= 5) {
            return back()->with('error', 'Member has reached maximum borrow limit (5 books)!');
        }

        $existingBorrow = BorrowRecord::where('book_id', $request->book_id)
            ->where('user_id', $request->user_id)
            ->whereNull('returned_at')
            ->exists();
            
        if ($existingBorrow) {
            return back()->with('error', 'User already has this book!');
        }

        BorrowRecord::create([
            'book_id' => $request->book_id,
            'user_id' => $request->user_id,
            'borrowed_at' => Carbon::now(),
            'due_date' => $request->due_date,
            'status' => 'borrowed'
        ]);

        $book->available_copies -= 1;
        $book->save();

        return redirect()->route('admin.borrows.index')
            ->with('success', 'Book issued successfully!');
    }

    public function return($id)
    {
        $borrow = BorrowRecord::findOrFail($id);
        
        if ($borrow->returned_at) {
            return back()->with('error', 'Book already returned!');
        }
        
        $borrow->returnBook();

        return redirect()->route('admin.borrows.index')
            ->with('success', 'Book returned successfully! Fine: ৳' . number_format($borrow->fine_amount, 2));
    }

    public function destroy($id)
    {
        $borrow = BorrowRecord::findOrFail($id);
        $borrow->delete();
        
        return redirect()->route('admin.borrows.index')
            ->with('success', 'Borrow record deleted!');
    }
}