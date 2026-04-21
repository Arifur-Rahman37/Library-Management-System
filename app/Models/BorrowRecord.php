<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BorrowRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id', 'user_id', 'borrowed_at', 'due_date', 
        'returned_at', 'fine_amount', 'fine_paid', 'status', 'remarks'
    ];

    protected $casts = [
        'borrowed_at' => 'date',
        'due_date' => 'date',
        'returned_at' => 'date',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isOverdue()
    {
        if ($this->returned_at) {
            return false;
        }
        return Carbon::now()->gt($this->due_date);
    }

    public function getDaysOverdueAttribute()
    {
        if (!$this->isOverdue()) {
            return 0;
        }
        return Carbon::now()->diffInDays($this->due_date);
    }

    public function calculateFine()
    {
        if (!$this->isOverdue() || $this->returned_at) {
            return 0;
        }

        $finePerDay = 5; // 5 Taka per day
        return $this->days_overdue * $finePerDay;
    }

    public function returnBook()
    {
        $this->returned_at = Carbon::now();
        $this->fine_amount = $this->calculateFine();
        $this->status = $this->fine_amount > 0 ? 'overdue' : 'returned';
        $this->save();
        
        if ($this->book) {
            $this->book->updateAvailableCopies();
        }
    }
}