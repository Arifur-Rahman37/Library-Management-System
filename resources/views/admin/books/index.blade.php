@extends('layouts.app')

@section('title', 'Manage Books')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-book me-2"></i> Books Management</h2>
    <a href="{{ route('admin.books.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i> Add New Book
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control search-box" 
                       placeholder="Search by title, author, ISBN..." 
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100 search-btn">
                    <i class="fas fa-search me-2"></i> Search
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Cover</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th>ISBN</th>
                        <th>Copies</th>
                        <th>Available</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($books as $book)
                    <tr>
                        <td>
                            <img src="{{ $book->cover_url }}" width="50" height="60" style="object-fit: cover; border-radius: 5px;">
                        </td>
                        <td>
                            <strong>{{ $book->title }}</strong>
                            <br>
                            <small class="text-muted">{{ $book->publisher ?? 'N/A' }}</small>
                        </td>
                        <td>{{ $book->author }}</td>
                        <td><span class="badge bg-info">{{ $book->category->name }}</span></td>
                        <td><code>{{ $book->isbn }}</code></td>
                        <td>{{ $book->total_copies }}</td>
                        <td>
                            @if($book->available_copies > 0)
                                <span class="badge bg-success">{{ $book->available_copies }}</span>
                            @else
                                <span class="badge bg-danger">Out of Stock</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn btn-sm btn-danger delete-book" data-id="{{ $book->id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                            <form id="delete-form-{{ $book->id }}" action="{{ route('admin.books.destroy', $book) }}" method="POST" class="d-none">
                                @csrf @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-5">No books found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $books->links() }}
    </div>
</div>

@push('scripts')
<script>
document.querySelectorAll('.delete-book').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.dataset.id;
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${id}`).submit();
            }
        });
    });
});
</script>
@endpush
@endsection