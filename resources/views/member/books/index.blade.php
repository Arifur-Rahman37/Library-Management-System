@extends('layouts.app')

@section('title', 'Browse Books')

@section('content')
<style>
    /* ========== PREMIUM PEACH CREAM THEME ========== */
    
    /* Animated Background */
    body {
        background: linear-gradient(
            135deg,
            #FEF9E7 0%,
            #FDEBD0 20%,
            #FAD7A0 40%,
            #F8C471 60%,
            #F5B041 80%,
            #F39C12 100%
        ) !important;
        background-size: 300% 300% !important;
        animation: premiumFlow 12s ease infinite !important;
    }
    
    @keyframes premiumFlow {
        0% { background-position: 0% 50%; }
        25% { background-position: 50% 50%; }
        50% { background-position: 100% 50%; }
        75% { background-position: 50% 50%; }
        100% { background-position: 0% 50%; }
    }
    
    /* Premium Floating Particles */
    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: 
            radial-gradient(circle at 20% 40%, rgba(255, 193, 7, 0.2) 3px, transparent 3px),
            radial-gradient(circle at 80% 70%, rgba(255, 215, 0, 0.15) 2px, transparent 2px),
            radial-gradient(circle at 40% 80%, rgba(255, 160, 0, 0.18) 4px, transparent 4px),
            radial-gradient(circle at 90% 20%, rgba(255, 193, 7, 0.12) 2px, transparent 2px),
            radial-gradient(circle at 60% 30%, rgba(255, 215, 0, 0.1) 3px, transparent 3px);
        background-size: 70px 70px, 60px 60px, 90px 90px, 50px 50px, 80px 80px;
        pointer-events: none;
        animation: floatParticles 25s linear infinite;
        z-index: 0;
    }
    
    @keyframes floatParticles {
        0% { transform: translateY(0px) translateX(0px); }
        100% { transform: translateY(-200px) translateX(100px); }
    }
    
    /* Wave Animation */
    body::after {
        content: '';
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 180px;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,215,0,0.12)" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') repeat-x;
        background-size: cover;
        pointer-events: none;
        animation: waveMove 14s linear infinite;
        z-index: 0;
    }
    
    @keyframes waveMove {
        0% { background-position: 0% 0%; }
        100% { background-position: 100% 0%; }
    }
    
    .container {
        position: relative;
        z-index: 1;
    }
    
    main.py-4 {
        padding-top: 0.5rem !important;
        padding-bottom: 0 !important;
    }
    
    /* Header */
    h2 {
        background: linear-gradient(135deg, #D35400 0%, #E67E22 30%, #F39C12 60%, #E67E22 100%);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent !important;
        font-weight: 800 !important;
        font-size: 2rem !important;
        margin-bottom: 0.5rem !important;
    }
    
    /* Search Box */
    .search-box {
        border: 2px solid #F39C12;
        border-radius: 35px;
        padding: 10px 22px;
        font-size: 14px;
        background: rgba(255, 255, 255, 0.95);
        transition: all 0.3s;
    }
    
    .search-box:focus {
        border-color: #E67E22;
        box-shadow: 0 0 0 5px rgba(243, 156, 18, 0.2);
        outline: none;
    }
    
    /* Button */
    .btn-primary {
        background: linear-gradient(135deg, #E67E22 0%, #F39C12 50%, #E67E22 100%) !important;
        border: none !important;
        border-radius: 35px !important;
        padding: 10px 22px !important;
        font-weight: 700 !important;
        color: white !important;
        transition: all 0.3s !important;
    }
    
    .btn-primary:hover {
        transform: translateY(-3px);
        background: linear-gradient(135deg, #F39C12 0%, #E67E22 100%) !important;
    }
    
    /* Cards */
    .card {
        background: rgba(255, 255, 255, 0.98);
        border: none;
        border-radius: 24px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        transition: all 0.4s;
        overflow: hidden;
    }
    
    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 25px 45px rgba(230, 126, 34, 0.25);
    }
    
    /* Book Cover Styling */
    .book-cover-wrapper {
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        height: 220px;
    }
    
    .book-cover {
        height: 220px;
        width: 100%;
        object-fit: cover;
        transition: all 0.5s ease;
    }
    
    /* Book Avatar (যখন কোন ইমেজ নেই) */
    .book-avatar {
        height: 220px;
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        transition: all 0.5s ease;
    }
    
    .book-avatar i {
        font-size: 60px;
        margin-bottom: 15px;
        opacity: 0.9;
    }
    
    .book-avatar h4 {
        font-size: 18px;
        margin: 0;
        padding: 0 15px;
        font-weight: 700;
    }
    
    .book-avatar p {
        font-size: 12px;
        margin: 5px 0 0;
        opacity: 0.8;
    }
    
    .card:hover .book-cover {
        transform: scale(1.08);
    }
    
    .card:hover .book-avatar {
        transform: scale(1.05);
    }
    
    /* Badges */
    .book-badge {
        position: absolute;
        padding: 4px 10px;
        font-size: 11px;
        font-weight: 600;
        border-radius: 20px;
        z-index: 2;
    }
    
    .book-badge.category {
        bottom: 10px;
        left: 10px;
        background: linear-gradient(135deg, #E67E22 0%, #F39C12 100%);
        color: white;
    }
    
    .book-badge.copies {
        top: 10px;
        right: 10px;
        background: rgba(0, 0, 0, 0.7);
        color: #FFD700;
    }
    
    .book-badge.rating {
        top: 10px;
        left: 10px;
        background: rgba(255, 215, 0, 0.9);
        color: #D35400;
    }
    
    /* Book Info */
    .book-info {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 8px;
        padding-top: 8px;
        border-top: 1px dashed rgba(243, 156, 18, 0.2);
    }
    
    .book-info-item {
        font-size: 10px;
        color: #7F8C8D;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    
    .book-info-item i {
        color: #F39C12;
        width: 14px;
    }
    
    .card-body {
        padding: 15px !important;
    }
    
    .card-title {
        font-size: 15px !important;
        font-weight: 800 !important;
        margin-bottom: 5px !important;
        color: #D35400;
    }
    
    .card-text {
        font-size: 12px !important;
        margin-bottom: 8px !important;
        color: #7F8C8D;
    }
    
    /* Card Footer */
    .card-footer {
        background: white !important;
        border-top: 1px solid rgba(243, 156, 18, 0.2);
        padding: 12px !important;
    }
    
    .card-footer .btn-sm {
        background: linear-gradient(135deg, #F39C12 0%, #E67E22 100%) !important;
        border: none !important;
        border-radius: 30px !important;
        padding: 6px 20px !important;
        font-size: 12px !important;
        font-weight: 600 !important;
        color: white !important;
        transition: all 0.3s;
    }
    
    .card-footer .btn-sm:hover {
        transform: scale(1.05);
    }
    
    /* Pagination */
    .custom-pagination {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin: 15px 0 0 0;
        padding: 0;
        list-style: none;
    }
    
    .custom-pagination li {
        list-style: none;
    }
    
    .custom-pagination a,
    .custom-pagination span {
        display: inline-block;
        padding: 8px 14px;
        font-size: 14px;
        font-weight: 700;
        border-radius: 30px;
        border: 2px solid #F39C12;
        background: white;
        color: #E67E22;
        text-decoration: none;
        transition: all 0.3s;
    }
    
    .custom-pagination a:hover {
        background: #F39C12;
        color: white;
    }
    
    .custom-pagination .active span {
        background: linear-gradient(135deg, #E67E22 0%, #F39C12 100%);
        border-color: #F39C12;
        color: white;
    }
    
    .custom-pagination .disabled span {
        background: #f5f5f5;
        color: #ccc;
        border-color: #ddd;
    }
    
    .row {
        margin-bottom: 0 !important;
    }
    
    .row > [class*="col-"] {
        margin-bottom: 25px;
    }
    
    .alert-info {
        background: rgba(243, 156, 18, 0.12) !important;
        border: 2px solid #F39C12 !important;
        border-radius: 25px !important;
        color: #D35400 !important;
    }
    
    footer {
        margin-top: 30px !important;
        padding: 20px 0 !important;
        background: linear-gradient(135deg, #D35400 0%, #E67E22 50%, #F39C12 100%) !important;
        color: white !important;
        border-radius: 30px 30px 0 0;
    }
    
    footer .text-danger {
        color: #FFF9C4 !important;
        animation: heartBeat 1.5s ease infinite;
    }
    
    @keyframes heartBeat {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.2); }
    }
    
    @media (max-width: 768px) {
        h2 { font-size: 1.3rem !important; }
        .book-cover, .book-avatar { height: 160px; }
        .book-avatar i { font-size: 40px; }
        .book-avatar h4 { font-size: 14px; }
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2><i class="fas fa-search me-2"></i> Browse Available Books</h2>
</div>

<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control search-box" 
                       placeholder="🔍 Search by title or author..." 
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="category" class="form-select search-box">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-1"></i> Search
                </button>
            </div>
        </form>
    </div>
</div>

<div class="row">
    @forelse($books as $book)
    <div class="col-md-3 mb-3">
        <div class="card book-card h-100">
            <!-- Book Cover / Avatar with Badges -->
            <div class="book-cover-wrapper">
                @if($book->cover_image)
                    <img src="{{ $book->cover_url }}" class="book-cover" alt="{{ $book->title }}">
                @else
                    <div class="book-avatar" style="background: linear-gradient(135deg, #{{ substr(md5($book->category_id), 0, 6) }} 0%, #{{ substr(md5($book->title), 0, 6) }} 100%);">
                        <i class="fas fa-book-open"></i>
                        <h4>{{ Str::limit($book->title, 20) }}</h4>
                        <p>by {{ Str::limit($book->author, 15) }}</p>
                    </div>
                @endif
                
                <!-- Category Badge -->
                <span class="book-badge category">
                    <i class="fas fa-tag me-1"></i> {{ $book->category->name }}
                </span>
                
                <!-- Available Copies Badge -->
                <span class="book-badge copies">
                    <i class="fas fa-copy me-1"></i> {{ $book->available_copies }} left
                </span>
                
                <!-- Rating Badge -->
                <span class="book-badge rating">
                    <i class="fas fa-star me-1"></i> 
                    @php
                        $ratings = [4.2, 4.5, 4.8, 4.3, 4.7, 4.9, 4.4, 4.6];
                        $rating = $ratings[$book->id % count($ratings)];
                    @endphp
                    {{ number_format($rating, 1) }}
                </span>
            </div>
            
            <div class="card-body">
                <h6 class="card-title mb-1">{{ Str::limit($book->title, 35) }}</h6>
                <p class="card-text text-muted small mb-2">
                    <i class="fas fa-user me-1"></i> by {{ Str::limit($book->author, 18) }}
                </p>
                
                <div class="book-info">
                    @if($book->published_year)
                    <span class="book-info-item">
                        <i class="fas fa-calendar-alt"></i> {{ $book->published_year }}
                    </span>
                    @endif
                    
                    @if($book->language)
                    <span class="book-info-item">
                        <i class="fas fa-language"></i> {{ $book->language }}
                    </span>
                    @endif
                    
                    @if($book->pages)
                    <span class="book-info-item">
                        <i class="fas fa-file-alt"></i> {{ $book->pages }} pgs
                    </span>
                    @endif
                    
                    <span class="book-info-item">
                        <i class="fas fa-barcode"></i> {{ substr($book->isbn, -6) }}
                    </span>
                </div>
            </div>
            
            <div class="card-footer bg-white border-0 text-center">
                <a href="{{ route('member.books.show', $book) }}" class="btn btn-sm">
                    <i class="fas fa-info-circle me-1"></i> View Details
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle me-2"></i> No books found!
        </div>
    </div>
    @endforelse
</div>

<!-- Premium Pagination -->
@if ($books->hasPages())
    <ul class="custom-pagination">
        @if ($books->onFirstPage())
            <li class="disabled"><span>‹</span></li>
        @else
            <li><a href="{{ $books->previousPageUrl() }}" rel="prev">‹</a></li>
        @endif

        @foreach ($books->getUrlRange(1, $books->lastPage()) as $page => $url)
            @if ($page == $books->currentPage())
                <li class="active"><span>{{ $page }}</span></li>
            @else
                <li><a href="{{ $url }}">{{ $page }}</a></li>
            @endif
        @endforeach

        @if ($books->hasMorePages())
            <li><a href="{{ $books->nextPageUrl() }}" rel="next">›</a></li>
        @else
            <li class="disabled"><span>›</span></li>
        @endif
    </ul>
@endif
@endsection