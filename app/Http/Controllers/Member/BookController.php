<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with('category')->where('available_copies', '>', 0);
        
        // Search by title, author, or ISBN
        if ($request->filled('search')) {  // filled() ব্যবহার করা ভালো
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('author', 'LIKE', "%{$search}%")
                  ->orWhere('isbn', 'LIKE', "%{$search}%");
            });
        }
        
        // Filter by category
        if ($request->filled('category')) {  // filled() ব্যবহার করা ভালো
            $query->where('category_id', $request->get('category'));
        }
        
        $books = $query->latest()->paginate(12);
        $categories = Category::all();
        
        // Retain search parameters in pagination
        $books->appends($request->all());
        
        return view('member.books.index', compact('books', 'categories'));
    }
    
    public function show(Book $book)
    {
        // Increment view count (optional)
        // $book->increment('views');
        
        return view('member.books.show', compact('book'));
    }
}