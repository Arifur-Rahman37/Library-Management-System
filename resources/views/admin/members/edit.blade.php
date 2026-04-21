@extends('layouts.app')

@section('title', 'Edit Member')

@section('content')
<div class="card">
    <div class="card-header bg-white">
        <h4 class="mb-0"><i class="fas fa-user-edit me-2"></i> Edit Member Information</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.members.update', $member) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name', $member->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email Address *</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                           value="{{ old('email', $member->email) }}" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                           value="{{ old('phone', $member->phone) }}">
                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Member Status</label>
                    <select name="is_active" class="form-select @error('is_active') is-invalid @enderror">
                        <option value="1" {{ $member->is_active ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ !$member->is_active ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('is_active') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="col-md-12 mb-3">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control @error('address') is-invalid @enderror" 
                              rows="3">{{ old('address', $member->address) }}</textarea>
                    @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="col-md-12 mb-3">
                    <hr>
                    <h5><i class="fas fa-key me-2"></i> Change Password (Optional)</h5>
                    <small class="text-muted">Leave empty if you don't want to change password</small>
                    
                    <!-- Password Info Alert -->
                    <div class="alert alert-info mt-2 py-2">
                        <i class="fas fa-info-circle me-1"></i> 
                        <small>Admin can change member's password without entering current password.</small>
                    </div>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">New Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
            </div>
            
            <div class="text-end">
                <a href="{{ route('admin.members.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Member</button>
            </div>
        </form>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i> Member Information</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <strong>Member ID:</strong>
                <p>#{{ $member->id }}</p>
            </div>
            <div class="col-md-4">
                <strong>Member Since:</strong>
                <p>{{ $member->membership_date ? $member->membership_date->format('d M, Y') : 'N/A' }}</p>
            </div>
            <div class="col-md-4">
                <strong>Total Books Borrowed:</strong>
                <p>{{ $member->borrowRecords()->count() }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <strong>Currently Borrowed:</strong>
                <p>{{ $member->activeBorrows()->count() }}</p>
            </div>
            <div class="col-md-4">
                <strong>Total Fine:</strong>
                <p>৳{{ number_format($member->borrowRecords()->sum('fine_amount'), 2) }}</p>
            </div>
            <div class="col-md-4">
                <strong>Role:</strong>
                <p><span class="badge bg-info">{{ ucfirst($member->role) }}</span></p>
            </div>
        </div>
    </div>
</div>
@endsection