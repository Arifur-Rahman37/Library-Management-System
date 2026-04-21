@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Total Books</h6>
                    <h2 class="mb-0">{{ $totalBooks }}</h2>
                </div>
                <div class="stat-icon bg-primary text-white">
                    <i class="fas fa-book"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Total Members</h6>
                    <h2 class="mb-0">{{ $totalMembers }}</h2>
                </div>
                <div class="stat-icon bg-success text-white">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Active Borrows</h6>
                    <h2 class="mb-0">{{ $activeBorrows }}</h2>
                </div>
                <div class="stat-icon bg-warning text-white">
                    <i class="fas fa-book-reader"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Overdue Books</h6>
                    <h2 class="mb-0">{{ $overdueBorrows }}</h2>
                </div>
                <div class="stat-icon bg-danger text-white">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-history me-2"></i> Recent Borrow Records</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Book</th>
                                <th>Member</th>
                                <th>Borrowed Date</th>
                                <th>Due Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentBorrows as $borrow)
                            <tr>
                                <td>{{ $borrow->book->title }}</td>
                                <td>{{ $borrow->user->name }}</td>
                                <td>{{ $borrow->borrowed_at->format('d M, Y') }}</td>
                                <td class="{{ $borrow->isOverdue() ? 'text-danger fw-bold' : '' }}">
                                    {{ $borrow->due_date->format('d M, Y') }}
                                </td>
                                <td>
                                    @if($borrow->returned_at)
                                        <span class="badge bg-success">Returned</span>
                                    @elseif($borrow->isOverdue())
                                        <span class="badge bg-danger">Overdue</span>
                                    @else
                                        <span class="badge bg-info">Borrowed</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center">No records found</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-fire me-2"></i> Popular Books</h5>
            </div>
            <div class="card-body">
                @foreach($popularBooks as $book)
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <strong>{{ $book->title }}</strong>
                        <small class="text-muted d-block">by {{ $book->author }}</small>
                    </div>
                    <span class="badge bg-primary">{{ $book->borrow_records_count }} borrows</span>
                </div>
                @endforeach
            </div>
        </div>
        
        @if($lowStockBooks->count() > 0)
        <div class="card border-warning">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i> Low Stock Alert</h5>
            </div>
            <div class="card-body">
                @foreach($lowStockBooks as $book)
                <div class="d-flex justify-content-between mb-2">
                    <span>{{ $book->title }}</span>
                    <span class="badge bg-warning">{{ $book->available_copies }} left</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection