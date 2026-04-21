<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\Admin\BorrowController as AdminBorrowController;
use App\Http\Controllers\Admin\MemberController as AdminMemberController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Member\DashboardController as MemberDashboardController;
use App\Http\Controllers\Member\BookController as MemberBookController;
use App\Http\Controllers\Member\BorrowController as MemberBorrowController;

// ========== DASHBOARD REDIRECT ROUTE ==========
Route::get('/dashboard', function () {
    if (auth()->check()) {
        if (auth()->user()->role === 'member') {
            return redirect()->route('member.dashboard');
        }
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('login');
})->middleware('auth')->name('dashboard');
// ==============================================

// Home Route
Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->role === 'member') {
            return redirect()->route('member.dashboard');
        }
        return redirect()->route('admin.dashboard');
    }
    return view('welcome');
})->name('home');

// ========== PROFILE ROUTES ==========
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/phone', [ProfileController::class, 'updatePhone'])->name('profile.update-phone');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
// ==============================================

// Member Routes
Route::middleware(['auth', 'role:member'])->prefix('member')->name('member.')->group(function () {
    Route::get('/dashboard', [MemberDashboardController::class, 'index'])->name('dashboard');
    Route::get('/books', [MemberBookController::class, 'index'])->name('books.index');
    Route::get('/books/{book}', [MemberBookController::class, 'show'])->name('books.show');
    Route::post('/books/{book}/borrow', [MemberBorrowController::class, 'borrow'])->name('books.borrow');
    Route::put('/borrows/{borrow}/return', [MemberBorrowController::class, 'return'])->name('borrows.return');
});

// Admin & Librarian Routes
Route::middleware(['auth', 'role:admin,librarian'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('books', AdminBookController::class);
    Route::resource('members', AdminMemberController::class);
    Route::get('/borrows', [AdminBorrowController::class, 'index'])->name('borrows.index');
    Route::get('/borrows/create', [AdminBorrowController::class, 'create'])->name('borrows.create');
    Route::post('/borrows', [AdminBorrowController::class, 'store'])->name('borrows.store');
    Route::put('/borrows/{id}/return', [AdminBorrowController::class, 'return'])->name('borrows.return');
    Route::delete('/borrows/{id}', [AdminBorrowController::class, 'destroy'])->name('borrows.destroy');
});

require __DIR__.'/auth.php';