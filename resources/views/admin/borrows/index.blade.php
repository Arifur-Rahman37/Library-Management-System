@extends('layouts.app')

@section('title', 'Borrow Records')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-exchange-alt me-2"></i> Borrow Records</h2>
    <a href="{{ route('admin.borrows.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i> Issue New Book
    </a>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card text-center">
            <h5 class="text-muted">Total</h5>
            <h3>{{ $stats['total'] }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card text-center">
            <h5 class="text-muted">Active</h5>
            <h3 class="text-warning">{{ $stats['active'] }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card text-center">
            <h5 class="text-muted">Overdue</h5>
            <h3 class="text-danger">{{ $stats['overdue'] }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card text-center">
            <h5 class="text-muted">Returned</h5>
            <h3 class="text-success">{{ $stats['returned'] }}</h3>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Book</th>
                        <th>Member</th>
                        <th>Borrowed Date</th>
                        <th>Due Date</th>
                        <th>Fine</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($borrows as $borrow)
                    <tr class="{{ $borrow->isOverdue() ? 'table-danger' : '' }}">
                        <td>{{ $borrow->id }}</td>
                        <td>
                            <strong>{{ $borrow->book->title }}</strong>
                            <br><small class="text-muted">ISBN: {{ $borrow->book->isbn }}</small>
                        </td>
                        <td>{{ $borrow->user->name }}</td>
                        <td>{{ $borrow->borrowed_at->format('d M, Y') }}</td>
                        <td class="{{ $borrow->isOverdue() ? 'fw-bold text-danger' : '' }}">
                            {{ $borrow->due_date->format('d M, Y') }}
                            @if($borrow->isOverdue())
                                <br><small class="text-danger">{{ $borrow->days_overdue }} days overdue</small>
                            @endif
                        </td>
                        <td>
                            @if($borrow->fine_amount > 0)
                                <span class="badge bg-danger">৳{{ number_format($borrow->fine_amount, 2) }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
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
                        <td>
                            @if(!$borrow->returned_at)
                                <button class="btn btn-sm btn-success return-book" data-id="{{ $borrow->id }}">
                                    <i class="fas fa-undo"></i> Return
                                </button>
                                <form id="return-form-{{ $borrow->id }}" action="{{ route('admin.borrows.return', $borrow->id) }}" method="POST" class="d-none">
                                    @csrf @method('PUT')
                                </form>
                            @else
                                <span class="text-muted">Completed</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-5">No borrow records found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $borrows->links() }}
    </div>
</div>

@push('scripts')
<script>
document.querySelectorAll('.return-book').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.dataset.id;
        Swal.fire({
            title: 'Return Book',
            text: "Confirm book return?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
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