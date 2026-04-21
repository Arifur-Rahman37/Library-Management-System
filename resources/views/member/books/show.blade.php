@extends('layouts.app')

@section('title', $book->title)

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <img src="{{ $book->cover_url }}" class="img-fluid rounded shadow" alt="{{ $book->title }}">
            </div>
            <div class="col-md-8">
                <h2>{{ $book->title }}</h2>
                <p class="text-muted">by <strong>{{ $book->author }}</strong></p>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <small class="text-muted">ISBN:</small>
                        <p><strong>{{ $book->isbn }}</strong></p>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">Category:</small>
                        <p><span class="badge bg-info">{{ $book->category->name }}</span></p>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">Publisher:</small>
                        <p>{{ $book->publisher ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">Published Year:</small>
                        <p>{{ $book->published_year ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">Language:</small>
                        <p>{{ $book->language }}</p>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">Pages:</small>
                        <p>{{ $book->pages ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">Rack Number:</small>
                        <p>{{ $book->rack_number ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">Available Copies:</small>
                        <p>
                            @if($book->available_copies > 0)
                                <span class="badge bg-success">{{ $book->available_copies }} / {{ $book->total_copies }}</span>
                            @else
                                <span class="badge bg-danger">Out of Stock</span>
                            @endif
                        </p>
                    </div>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted">Description:</small>
                    <p>{{ $book->description ?: 'No description available.' }}</p>
                </div>
                
                @auth
                    @if(auth()->user()->role === 'member')
                        @if($book->available_copies > 0)
                            <form action="{{ route('member.books.borrow', $book) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-lg" onclick="return confirm('Are you sure you want to borrow this book?')">
                                    <i class="fas fa-hand-holding-heart me-2"></i> Borrow This Book
                                </button>
                            </form>
                        @else
                            <button class="btn btn-secondary btn-lg" disabled>
                                <i class="fas fa-times-circle me-2"></i> Not Available
                            </button>
                        @endif
                    @endif
                @endauth
                
                <a href="{{ route('member.books.index') }}" class="btn btn-outline-secondary mt-3">
                    <i class="fas fa-arrow-left me-2"></i> Back to Books
                </a>
            </div>
        </div>
    </div>
</div>
@endsection