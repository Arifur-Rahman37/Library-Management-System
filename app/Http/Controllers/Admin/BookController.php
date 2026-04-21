<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with('category');
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('author', 'LIKE', "%{$search}%")
                  ->orWhere('isbn', 'LIKE', "%{$search}%");
            });
        }
        
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }
        
        $books = $query->latest()->paginate(15);
        $categories = Category::all();
        
        return view('admin.books.index', compact('books', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books',
            'category_id' => 'required|exists:categories,id',
            'total_copies' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'publisher' => 'nullable|string',
            'published_year' => 'nullable|integer|min:1800|max:' . date('Y'),
            'language' => 'nullable|string',
            'pages' => 'nullable|integer|min:1',
            'rack_number' => 'nullable|string'
        ]);

        if ($request->hasFile('cover_image')) {
            $image = $request->file('cover_image');
            $filename = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('books', $filename, 'public');
            $validated['cover_image'] = $path;
        }

        $validated['available_copies'] = $validated['total_copies'];
        $validated['slug'] = Str::slug($validated['title'] . '-' . $validated['isbn']);
        
        Book::create($validated);

        return redirect()->route('admin.books.index')
            ->with('success', 'Book added successfully!');
    }

    public function edit(Book $book)
    {
        $categories = Category::all();
        return view('admin.books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => ['required', 'string', Rule::unique('books')->ignore($book->id)],
            'category_id' => 'required|exists:categories,id',
            'total_copies' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'publisher' => 'nullable|string',
            'published_year' => 'nullable|integer|min:1800|max:' . date('Y'),
            'language' => 'nullable|string',
            'pages' => 'nullable|integer|min:1',
            'rack_number' => 'nullable|string'
        ]);

        if ($request->hasFile('cover_image')) {
            $image = $request->file('cover_image');
            $filename = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('books', $filename, 'public');
            $validated['cover_image'] = $path;
        }

        $oldTotal = $book->total_copies;
        $newTotal = $validated['total_copies'];
        $difference = $newTotal - $oldTotal;
        
        $book->update($validated);
        $book->available_copies += $difference;
        $book->save();

        return redirect()->route('admin.books.index')
            ->with('success', 'Book updated successfully!');
    }

    public function destroy(Book $book)
    {
        if ($book->activeBorrows()->count() > 0) {
            return back()->with('error', 'Cannot delete book with active borrows!');
        }
        
        $book->delete();
        return redirect()->route('admin.books.index')
            ->with('success', 'Book deleted successfully!');
    }
}