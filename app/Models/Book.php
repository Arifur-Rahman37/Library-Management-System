<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'author', 'isbn', 'category_id', 'description',
        'cover_image', 'total_copies', 'available_copies', 'publisher',
        'published_year', 'language', 'pages', 'rack_number'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($book) {
            $book->slug = Str::slug($book->title . '-' . $book->isbn);
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function borrowRecords()
    {
        return $this->hasMany(BorrowRecord::class);
    }

    public function activeBorrows()
    {
        return $this->borrowRecords()->whereNull('returned_at');
    }

    public function updateAvailableCopies()
    {
        $borrowedCount = $this->activeBorrows()->count();
        $this->available_copies = $this->total_copies - $borrowedCount;
        $this->save();
    }

    public function isAvailable()
    {
        return $this->available_copies > 0;
    }

    // ========== আপডেটেড মেথড - আকর্ষণীয় আভতার ==========
    public function getCoverUrlAttribute()
    {
        // যদি কভার ইমেজ আপলোড করা থাকে
        if ($this->cover_image) {
            return asset('storage/' . $this->cover_image);
        }
        
        // ক্যাটাগরি ভিত্তিক কালার সেট
        $categoryColors = [
            'Fiction' => 'FF6B6B',
            'Non-Fiction' => '4ECDC4',
            'Science' => '45B7D1',
            'Technology' => '96CEB4',
            'History' => 'FFEAA7',
            'Biography' => 'DDA0DD',
            'Children' => '98D8C8',
            'Poetry' => 'F7DC6F',
            'Drama' => 'BB8FCE',
            'Philosophy' => '85C1E9',
            'Business' => 'F8C471',
            'Self Help' => 'A9DFBF',
        ];
        
        $categoryName = $this->category ? $this->category->name : 'Book';
        $color = $categoryColors[$categoryName] ?? '667eea';
        
        // বইয়ের নাম থেকে 2 অক্ষর নেওয়া
        $initials = strtoupper(substr($this->title, 0, 2));
        
        // UI Avatars ব্যবহার করে আভতার তৈরি (বইয়ের আইকন সহ)
        return "https://ui-avatars.com/api/?background={$color}&color=fff&rounded=true&size=300&bold=true&font-size=0.33&length=2&name={$initials}&format=svg";
    }
    // ========== আপডেট শেষ ==========
}