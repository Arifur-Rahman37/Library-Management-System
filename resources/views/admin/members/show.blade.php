@extends('layouts.app')

@section('title', 'Member Details')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <img src="{{ $member->avatar_url }}" class="rounded-circle mb-3" width="150" height="150" style="object-fit: cover;">
                <h4>{{ $member->name }}</h4>
                <p class="text-muted">{{ $member->email }}</p>
                <p>
                    @if($member->is_active)
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-danger">Inactive</span>
                    @endif
                </p>
                <hr>
                <div class="row">
                    <div class="col-6">
                        <h5>{{ $stats['total_borrowed'] }}</h5>
                        <small>Total Borrowed</small>
                    </div>
                    <div class="col-6">
                        <h5>{{ $stats['active_borrows'] }}</h5>
                        <small>Active Borrows</small>
                    </div>
                </div>
                <hr>
                <p><strong>Total Fine:</strong> ৳{{ number_format($stats['total_fine'], 2) }}</p>
                <p><strong>Member Since:</strong> {{ $member->membership_date ? $member->membership_date->format('d M, Y') : 'N/A' }}</p>
                <p><strong>Phone:</strong> {{ $member->phone ?? 'N/A' }}</p>
                <p><strong>Address:</strong> {{ $member->address ?? 'N/A' }}</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5><i class="fas fa-book-reader"></i> Currently Borrowed Books</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Book Title</th>
                                <th>Borrowed Date</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Fine</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activeBorrows as $borrow)
                            <tr>
                                <td>{{ $borrow->book->title }}</td>
                                <td>{{ $borrow->borrowed_at->format('d M, Y') }}</td>
                                <td>
                                    {{ $borrow->due_date->format('d M, Y') }}
                                    @if($borrow->isOverdue())
                                        <span class="badge bg-danger">Overdue</span>
                                    @endif
                                </td>
                                <td>
                                    @if($borrow->isOverdue())
                                        <span class="badge bg-danger">Overdue</span>
                                    @else
                                        <span class="badge bg-success">Active</span>
                                    @endif
                                </td>
                                <td>৳{{ number_format($borrow->calculateFine(), 2) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center">No active borrows</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-history"></i> Borrow History</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Book Title</th>
                                <th>Borrowed Date</th>
                                <th>Returned Date</th>
                                <th>Fine Paid</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($borrowHistory as $borrow)
                            <tr>
                                <td>{{ $borrow->book->title }}</td>
                                <td>{{ $borrow->borrowed_at->format('d M, Y') }}</td>
                                <td>{{ $borrow->returned_at ? $borrow->returned_at->format('d M, Y') : '-' }}</td>
                                <td>৳{{ number_format($borrow->fine_amount, 2) }}</td>
                            </tr>
                            @empty
                            <td><td colspan="4" class="text-center">No borrow history</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('admin.members.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Members
    </a>
    <a href="{{ route('admin.members.edit', $member) }}" class="btn btn-warning">
        <i class="fas fa-edit"></i> Edit Member
    </a>
</div>
<div class="mt-3">
    <a href="{{ route('admin.members.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Members
    </a>
    @can('update', $member)
        <a href="{{ route('admin.members.edit', $member) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Edit Member
        </a>
    @endcan
</div>
@endsection