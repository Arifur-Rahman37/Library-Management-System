@extends('layouts.app')

@section('title', 'Edit Book')

@section('content')
<div class="card">
    <div class="card-header bg-white">
        <h4 class="mb-0"><i class="fas fa-edit me-2"></i> Edit Book</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.books.update', $book) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Book Title *</label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $book->title) }}" required>
                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Author *</label>
                    <input type="text" name="author" class="form-control @error('author') is-invalid @enderror" value="{{ old('author', $book->author) }}" required>
                    @error('author') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">ISBN *</label>
                    <input type="text" name="isbn" class="form-control @error('isbn') is-invalid @enderror" value="{{ old('isbn', $book->isbn) }}" required>
                    @error('isbn') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Category *</label>
                    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $book->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="col-md-4 mb-3">
                    <label class="form-label">Total Copies *</label>
                    <input type="number" name="total_copies" class="form-control @error('total_copies') is-invalid @enderror" value="{{ old('total_copies', $book->total_copies) }}" required>
                    @error('total_copies') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="col-md-4 mb-3">
                    <label class="form-label">Publisher</label>
                    <input type="text" name="publisher" class="form-control" value="{{ old('publisher', $book->publisher) }}">
                </div>
                
                <div class="col-md-4 mb-3">
                    <label class="form-label">Published Year</label>
                    <input type="number" name="published_year" class="form-control" value="{{ old('published_year', $book->published_year) }}">
                </div>
                
                <div class="col-md-4 mb-3">
                    <label class="form-label">Language</label>
                    <input type="text" name="language" class="form-control" value="{{ old('language', $book->language) }}">
                </div>
                
                <div class="col-md-4 mb-3">
                    <label class="form-label">Pages</label>
                    <input type="number" name="pages" class="form-control" value="{{ old('pages', $book->pages) }}">
                </div>
                
                <div class="col-md-4 mb-3">
                    <label class="form-label">Rack Number</label>
                    <input type="text" name="rack_number" class="form-control" value="{{ old('rack_number', $book->rack_number) }}">
                </div>
                
                <div class="col-md-12 mb-3">
                    <label class="form-label">Current Cover</label>
                    @if($book->cover_image)
                        <div class="mb-2">
                            <img src="{{ $book->cover_url }}" width="100" height="120" style="object-fit: cover; border-radius: 5px;">
                        </div>
                    @endif
                    <input type="file" name="cover_image" class="form-control @error('cover_image') is-invalid @enderror" accept="image/*">
                    @error('cover_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <small class="text-muted">Leave empty to keep current cover</small>
                </div>
                
                <div class="col-md-12 mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description', $book->description) }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            
            <div class="text-end">
                <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Book</button>
            </div>
        </form>
    </div>
</div>
@endsection