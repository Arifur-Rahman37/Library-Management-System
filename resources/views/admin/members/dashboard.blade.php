@extends('layouts.app')

@section('title', 'My Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card bg-gradient-primary text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <img src="{{ auth()->user()->avatar_url }}" class="rounded-circle me-3" width="80" height="80">
                    <div>
                        <h2 class="mb-0">Welcome back, {{ auth()->user()->name }}!</h2>
                        <p class="mb-0">Member since {{ auth()->user()->membership_date ? auth()->user()->membership_date->format('F Y') : 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="stat-card text-center">
            <h5 class="text-muted">Total Books Borrowed</h5>
            <h2>{{ $stats['total_borrowed'] }}</h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card text-center">
            <h5 class="text-muted">Currently Reading</h5>
            <h2 class="text-primary">{{ $stats['active_borrows'] }}</h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card text-center">
            <h5 class="text-muted">Overdue Books</h5>
            <h2 class="text-danger">{{ $stats['overdue'] }}</h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card text-center">
            <h5 class="text-muted">Total Fine</h5>
            <h2 class="text-warning">৳{{ number_format($stats['total_fine'], 2) }}</h2>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-book-reader me-2"></i> Currently Borrowed Books</h5>
            </div>
            <div class="card-body">
                @if($activeBorrows->count() > 0)
                    @foreach($activeBorrows as $borrow)
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">{{ $borrow->book->title }}</h6>
                                <small class="text-muted">by {{ $borrow->book->author }}</small>
                                <br>
                                <small>Due Date: <strong class="{{ $borrow->isOverdue() ? 'text-danger' : '' }}">{{ $borrow->due_date->format('d M, Y') }}</strong></small>
                                @if($borrow->isOverdue())
                                    <br><small class="text-danger">{{ $borrow->days_overdue }} days overdue - Fine: ৳{{ $borrow->calculateFine() }}</small>
                                @endif
                            </div>
                            <button class="btn btn-sm btn-success return-book" data-id="{{ $borrow->id }}">
                                <i class="fas fa-undo"></i> Return
                            </button>
                            <form id="return-form-{{ $borrow->id }}" action="{{ route('member.borrows.return', $borrow->id) }}" method="POST" class="d-none">
                                @csrf @method('PUT')
                            </form>
                        </div>
                    </div>
                    @endforeach
                @else
                    <p class="text-muted text-center py-4">No active borrows. <a href="{{ route('member.books.index') }}">Browse books</a></p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-history me-2"></i> Borrow History</h5>
            </div>
            <div class="card-body">
                @if($borrowHistory->count() > 0)
                    @foreach($borrowHistory as $borrow)
                    <div class="border-bottom pb-2 mb-2">
                        <div class="d-flex justify-content-between">
                            <div>
                                <strong>{{ $borrow->book->title }}</strong>
                                <br>
                                <small>Returned: {{ $borrow->returned_at->format('d M, Y') }}</small>
                            </div>
                            @if($borrow->fine_amount > 0)
                                <span class="badge bg-danger">Fine: ৳{{ number_format($borrow->fine_amount, 2) }}</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                    {{ $borrowHistory->links() }}
                @else
                    <p class="text-muted text-center py-4">No borrow history yet</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="text-center mt-3">
    <a href="{{ route('member.books.index') }}" class="btn btn-primary btn-lg">
        <i class="fas fa-search me-2"></i> Browse Available Books
    </a>
</div>

@push('scripts')
<script>
document.querySelectorAll('.return-book').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.dataset.id;
        Swal.fire({
            title: 'Return Book',
            text: "Confirm to return this book?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            confirmButtonText: 'Yes, return it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`return-form-${id}`).submit();
            }
        });
    });
});
</script>
@endpush
@endsection