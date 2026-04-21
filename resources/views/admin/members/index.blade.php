@extends('layouts.app')

@section('title', 'Manage Members')

@section('content')
<style>
    /* Custom Pagination - No Terminal Logo */
    .custom-pagination {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin: 20px 0 0 0;
        padding: 0;
        list-style: none;
    }
    
    .custom-pagination li {
        list-style: none;
        margin: 0;
        padding: 0;
    }
    
    .custom-pagination a,
    .custom-pagination span {
        display: inline-block;
        padding: 6px 12px;
        font-size: 13px;
        font-weight: 500;
        border-radius: 6px;
        border: 1px solid #ddd;
        background: white;
        color: #00BFFF;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .custom-pagination a:hover {
        background: #00BFFF;
        border-color: #00BFFF;
        color: white;
    }
    
    .custom-pagination .active span {
        background: linear-gradient(135deg, #00BFFF 0%, #1E90FF 100%);
        border-color: #00BFFF;
        color: white;
    }
    
    .custom-pagination .disabled span {
        background: #f5f5f5;
        color: #ccc;
        border-color: #ddd;
        cursor: not-allowed;
    }
    
    /* Remove any icons or pseudo-elements */
    .custom-pagination a::before,
    .custom-pagination a::after,
    .custom-pagination span::before,
    .custom-pagination span::after {
        content: none !important;
        display: none !important;
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-users me-2"></i> Member Management</h2>
    <a href="{{ route('admin.members.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i> Add New Member
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-10">
                <input type="text" name="search" class="form-control search-box" 
                       placeholder="Search by name, email, phone..." 
                       value="{{ request('search') }}">
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
                        <th>Avatar</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Member Since</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $member)
                    <tr>
                        <td>
                            <img src="{{ $member->avatar_url }}" width="40" height="40" class="rounded-circle">
                        </td>
                        <td>{{ $member->name }}</td>
                        <td>{{ $member->email }}</td>
                        <td>{{ $member->phone ?? 'N/A' }}</td>
                        <td>{{ $member->membership_date ? $member->membership_date->format('d M, Y') : 'N/A' }}</td>
                        <td>
                            @if($member->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.members.edit', $member) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn btn-sm btn-danger delete-member" data-id="{{ $member->id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                            <form id="delete-form-{{ $member->id }}" action="{{ route('admin.members.destroy', $member) }}" method="POST" class="d-none">
                                @csrf @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-5">No members found</td>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Custom Pagination - No Terminal Logo -->
        @if ($members->hasPages())
            <ul class="custom-pagination">
                {{-- Previous Page Link --}}
                @if ($members->onFirstPage())
                    <li class="disabled"><span>‹</span></li>
                @else
                    <li><a href="{{ $members->previousPageUrl() }}" rel="prev">‹</a></li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($members->getUrlRange(1, $members->lastPage()) as $page => $url)
                    @if ($page == $members->currentPage())
                        <li class="active"><span>{{ $page }}</span></li>
                    @else
                        <li><a href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($members->hasMorePages())
                    <li><a href="{{ $members->nextPageUrl() }}" rel="next">›</a></li>
                @else
                    <li class="disabled"><span>›</span></li>
                @endif
            </ul>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.querySelectorAll('.delete-member').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.dataset.id;
        Swal.fire({
            title: 'Are you sure?',
            text: "This member will be permanently deleted!",
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