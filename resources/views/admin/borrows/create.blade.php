@extends('layouts.app')

@section('title', 'Issue New Book')

@section('content')
<div class="card">
    <div class="card-header bg-white">
        <h4 class="mb-0"><i class="fas fa-hand-holding-heart me-2"></i> Issue Book to Member</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.borrows.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Select Book *</label>
                    <select name="book_id" class="form-select @error('book_id') is-invalid @enderror" required>
                        <option value="">Choose a book...</option>
                        @foreach($books as $book)
                        <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                            {{ $book->title }} by {{ $book->author }} ({{ $book->available_copies }} available)
                        </option>
                        @endforeach
                    </select>
                    @error('book_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Select Member *</label>
                    <select name="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                        <option value="">Choose a member...</option>
                        @foreach($members as $member)
                        <option value="{{ $member->id }}" {{ old('user_id') == $member->id ? 'selected' : '' }}>
                            {{ $member->name }} ({{ $member->email }})
                        </option>
                        @endforeach
                    </select>
                    @error('user_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Due Date *</label>
                    <input type="date" name="due_date" class="form-control @error('due_date') is-invalid @enderror" value="{{ old('due_date', date('Y-m-d', strtotime('+14 days'))) }}" required>
                    @error('due_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <small class="text-muted">Default: 14 days from today</small>
                </div>
            </div>
            
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Rules:</strong>
                <ul class="mb-0 mt-2">
                    <li>Maximum 5 books can be borrowed at a time</li>
                    <li>Late return fine: 5 Taka per day</li>
                    <li>Books must be returned by due date</li>
                </ul>
            </div>
            
            <div class="text-end">
                <a href="{{ route('admin.borrows.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Issue Book</button>
            </div>
        </form>
    </div>
</div>
@endsection