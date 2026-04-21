@extends('layouts.app')

@section('title', 'Add New Member')

@section('content')
<style>
    .password-requirements {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 10px;
        margin-top: 5px;
    }
    .password-requirements small {
        display: block;
        margin-bottom: 3px;
    }
    .password-requirements .valid {
        color: #28a745;
    }
    .password-requirements .invalid {
        color: #dc3545;
    }
    .requirement-met {
        text-decoration: line-through;
        opacity: 0.7;
    }
</style>

<div class="card">
    <div class="card-header bg-white">
        <h4 class="mb-0"><i class="fas fa-user-plus me-2"></i> Add New Member</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.members.store') }}" method="POST" id="memberForm">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name') }}" required>
                    @error('name') 
                        <div class="invalid-feedback">{{ $message }}</div>
                    @else
                        <small class="text-muted">Enter member's full name</small>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email Address <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                           value="{{ old('email') }}" required id="emailInput">
                    @error('email') 
                        <div class="invalid-feedback">{{ $message }}</div>
                    @else
                        <small class="text-muted">Enter a valid email address (e.g., user@example.com)</small>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                           required id="passwordInput">
                    @error('password') 
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    
                    <!-- Password Requirements -->
                    <div class="password-requirements" id="passwordRequirements">
                        <small class="text-muted mb-2 d-block">Password must contain:</small>
                        <small id="lengthReq" class="invalid">❌ At least 8 characters</small>
                        <small id="upperReq" class="invalid">❌ At least 1 uppercase letter (A-Z)</small>
                        <small id="lowerReq" class="invalid">❌ At least 1 lowercase letter (a-z)</small>
                        <small id="numberReq" class="invalid">❌ At least 1 number (0-9)</small>
                    </div>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control" required id="confirmPasswordInput">
                    <small class="text-muted" id="confirmMessage"></small>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                           value="{{ old('phone') }}" placeholder="+8801XXXXXXXXX">
                    @error('phone') 
                        <div class="invalid-feedback">{{ $message }}</div>
                    @else
                        <small class="text-muted">Optional - Enter contact number</small>
                    @enderror
                </div>
                
                <div class="col-md-12 mb-3">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control @error('address') is-invalid @enderror" 
                              rows="3" placeholder="Enter member's address">{{ old('address') }}</textarea>
                    @error('address') 
                        <div class="invalid-feedback">{{ $message }}</div>
                    @else
                        <small class="text-muted">Optional - Enter residential address</small>
                    @enderror
                </div>
            </div>
            
            <!-- Validation Rules Alert -->
            <div class="alert alert-info mt-3">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Validation Rules:</strong>
                <ul class="mb-0 mt-2">
                    <li>Email must be valid format (e.g., name@domain.com)</li>
                    <li>Email must be unique (not used by another member)</li>
                    <li>Password must be at least 8 characters</li>
                    <li>Password must contain uppercase, lowercase, and numbers</li>
                    <li>Password and confirmation must match</li>
                </ul>
            </div>
            
            <div class="text-end">
                <a href="{{ route('admin.members.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary" id="submitBtn">Add Member</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Real-time email validation
    const emailInput = document.getElementById('emailInput');
    if (emailInput) {
        emailInput.addEventListener('input', function() {
            const email = this.value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (email && !emailRegex.test(email)) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    }
    
    // Password validation with requirements
    const passwordInput = document.getElementById('passwordInput');
    const confirmInput = document.getElementById('confirmPasswordInput');
    const lengthReq = document.getElementById('lengthReq');
    const upperReq = document.getElementById('upperReq');
    const lowerReq = document.getElementById('lowerReq');
    const numberReq = document.getElementById('numberReq');
    const confirmMessage = document.getElementById('confirmMessage');
    const submitBtn = document.getElementById('submitBtn');
    
    function validatePassword() {
        const password = passwordInput.value;
        
        // Check length
        if (password.length >= 8) {
            lengthReq.classList.add('valid');
            lengthReq.classList.remove('invalid');
            lengthReq.innerHTML = '✅ At least 8 characters';
        } else {
            lengthReq.classList.add('invalid');
            lengthReq.classList.remove('valid');
            lengthReq.innerHTML = '❌ At least 8 characters';
        }
        
        // Check uppercase
        if (/[A-Z]/.test(password)) {
            upperReq.classList.add('valid');
            upperReq.classList.remove('invalid');
            upperReq.innerHTML = '✅ At least 1 uppercase letter (A-Z)';
        } else {
            upperReq.classList.add('invalid');
            upperReq.classList.remove('valid');
            upperReq.innerHTML = '❌ At least 1 uppercase letter (A-Z)';
        }
        
        // Check lowercase
        if (/[a-z]/.test(password)) {
            lowerReq.classList.add('valid');
            lowerReq.classList.remove('invalid');
            lowerReq.innerHTML = '✅ At least 1 lowercase letter (a-z)';
        } else {
            lowerReq.classList.add('invalid');
            lowerReq.classList.remove('valid');
            lowerReq.innerHTML = '❌ At least 1 lowercase letter (a-z)';
        }
        
        // Check number
        if (/[0-9]/.test(password)) {
            numberReq.classList.add('valid');
            numberReq.classList.remove('invalid');
            numberReq.innerHTML = '✅ At least 1 number (0-9)';
        } else {
            numberReq.classList.add('invalid');
            numberReq.classList.remove('valid');
            numberReq.innerHTML = '❌ At least 1 number (0-9)';
        }
        
        // Check confirm password
        if (confirmInput.value) {
            if (password === confirmInput.value) {
                confirmMessage.innerHTML = '✓ Passwords match';
                confirmMessage.style.color = '#28a745';
                confirmInput.classList.remove('is-invalid');
            } else {
                confirmMessage.innerHTML = '✗ Passwords do not match';
                confirmMessage.style.color = '#dc3545';
                confirmInput.classList.add('is-invalid');
            }
        } else {
            confirmMessage.innerHTML = '';
            confirmInput.classList.remove('is-invalid');
        }
        
        // Enable/disable submit button
        const isValid = password.length >= 8 && /[A-Z]/.test(password) && /[a-z]/.test(password) && /[0-9]/.test(password);
        if (password && !isValid) {
            passwordInput.classList.add('is-invalid');
        } else {
            passwordInput.classList.remove('is-invalid');
        }
    }
    
    if (passwordInput) {
        passwordInput.addEventListener('input', validatePassword);
    }
    
    if (confirmInput) {
        confirmInput.addEventListener('input', validatePassword);
    }
</script>
@endpush
@endsection