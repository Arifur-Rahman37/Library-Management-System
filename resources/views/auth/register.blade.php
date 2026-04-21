<x-guest-layout>
    <style>
        .password-requirements {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 10px;
            margin-top: 8px;
            font-size: 12px;
        }
        .requirement {
            margin-bottom: 3px;
            font-size: 11px;
        }
        .requirement.valid {
            color: #28a745;
        }
        .requirement.invalid {
            color: #dc3545;
        }
        .requirement i {
            width: 16px;
            margin-right: 5px;
        }
        .error-feedback {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .text-muted {
            color: #6c757d !important;
            font-size: 12px;
        }
        .valid-feedback {
            color: #28a745;
            font-size: 12px;
            margin-top: 5px;
        }
    </style>

    <form method="POST" action="{{ route('register') }}" id="registerForm">
        @csrf

        <!-- Name -->
        <div class="form-group">
            <x-input-label for="name" :value="__('Full Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
            <small class="text-muted">Enter your full name</small>
        </div>

        <!-- Email Address (Only Gmail) -->
        <div class="form-group mt-4">
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
            <div id="emailMessage" class="error-feedback"></div>
            <small class="text-muted">Only <strong>@gmail.com</strong> email addresses are allowed</small>
        </div>

        <!-- Phone Number (Optional) -->
        <div class="form-group mt-4">
            <x-input-label for="phone" :value="__('Phone Number (Optional)')" />
            <x-text-input id="phone" class="block mt-1 w-full" type="tel" name="phone" :value="old('phone')" placeholder="+8801XXXXXXXXX" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            <small class="text-muted">Enter your contact number (e.g., +8801712345678)</small>
        </div>

        <!-- Address (Optional) -->
        <div class="form-group mt-4">
            <x-input-label for="address" :value="__('Address (Optional)')" />
            <textarea id="address" name="address" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" rows="2" placeholder="Enter your address">{{ old('address') }}</textarea>
            <x-input-error :messages="$errors->get('address')" class="mt-2" />
            <small class="text-muted">Your residential address</small>
        </div>

        <!-- Password -->
        <div class="form-group mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
            
            <!-- Password Requirements -->
            <div class="password-requirements" id="passwordRequirements">
                <small class="text-muted d-block mb-2">Password must contain:</small>
                <div class="requirement invalid" id="lengthReq">
                    <i class="fas fa-times-circle"></i> At least 8 characters
                </div>
                <div class="requirement invalid" id="upperReq">
                    <i class="fas fa-times-circle"></i> At least 1 uppercase letter (A-Z)
                </div>
                <div class="requirement invalid" id="lowerReq">
                    <i class="fas fa-times-circle"></i> At least 1 lowercase letter (a-z)
                </div>
                <div class="requirement invalid" id="numberReq">
                    <i class="fas fa-times-circle"></i> At least 1 number (0-9)
                </div>
                <div class="requirement invalid" id="specialReq">
                    <i class="fas fa-times-circle"></i> At least 1 special character (@$!%*?&)
                </div>
            </div>
        </div>

        <!-- Confirm Password -->
        <div class="form-group mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <div id="confirmMessage" class="error-feedback"></div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4" id="submitBtn">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    @push('scripts')
    <script>
        // Real-time Gmail validation
        const emailInput = document.getElementById('email');
        const emailMessage = document.getElementById('emailMessage');
        
        if (emailInput) {
            emailInput.addEventListener('input', function() {
                const email = this.value;
                const gmailRegex = /^[a-zA-Z0-9._%+-]+@gmail\.com$/;
                
                if (email && !gmailRegex.test(email)) {
                    this.classList.add('is-invalid');
                    if (emailMessage) {
                        emailMessage.innerHTML = '❌ Only @gmail.com email addresses are allowed';
                        emailMessage.style.color = '#dc3545';
                    }
                } else if (email && gmailRegex.test(email)) {
                    this.classList.remove('is-invalid');
                    if (emailMessage) {
                        emailMessage.innerHTML = '✓ Valid Gmail address';
                        emailMessage.style.color = '#28a745';
                    }
                } else {
                    this.classList.remove('is-invalid');
                    if (emailMessage) {
                        emailMessage.innerHTML = '';
                    }
                }
            });
        }
        
        // Phone validation
        const phoneInput = document.getElementById('phone');
        if (phoneInput) {
            phoneInput.addEventListener('input', function() {
                const phone = this.value;
                const phoneRegex = /^[\+\d\s\-\(\)]{8,20}$/;
                if (phone && !phoneRegex.test(phone)) {
                    this.classList.add('is-invalid');
                } else {
                    this.classList.remove('is-invalid');
                }
            });
        }
        
        // Password validation
        const passwordInput = document.getElementById('password');
        const lengthReq = document.getElementById('lengthReq');
        const upperReq = document.getElementById('upperReq');
        const lowerReq = document.getElementById('lowerReq');
        const numberReq = document.getElementById('numberReq');
        const specialReq = document.getElementById('specialReq');
        const confirmInput = document.getElementById('password_confirmation');
        const confirmMessage = document.getElementById('confirmMessage');
        const submitBtn = document.getElementById('submitBtn');
        
        function validatePassword() {
            const password = passwordInput.value;
            let isValid = true;
            
            // Check length
            if (password.length >= 8) {
                lengthReq.classList.remove('invalid');
                lengthReq.classList.add('valid');
                lengthReq.innerHTML = '<i class="fas fa-check-circle"></i> At least 8 characters';
            } else {
                lengthReq.classList.remove('valid');
                lengthReq.classList.add('invalid');
                lengthReq.innerHTML = '<i class="fas fa-times-circle"></i> At least 8 characters';
                isValid = false;
            }
            
            // Check uppercase
            if (/[A-Z]/.test(password)) {
                upperReq.classList.remove('invalid');
                upperReq.classList.add('valid');
                upperReq.innerHTML = '<i class="fas fa-check-circle"></i> At least 1 uppercase letter (A-Z)';
            } else {
                upperReq.classList.remove('valid');
                upperReq.classList.add('invalid');
                upperReq.innerHTML = '<i class="fas fa-times-circle"></i> At least 1 uppercase letter (A-Z)';
                isValid = false;
            }
            
            // Check lowercase
            if (/[a-z]/.test(password)) {
                lowerReq.classList.remove('invalid');
                lowerReq.classList.add('valid');
                lowerReq.innerHTML = '<i class="fas fa-check-circle"></i> At least 1 lowercase letter (a-z)';
            } else {
                lowerReq.classList.remove('valid');
                lowerReq.classList.add('invalid');
                lowerReq.innerHTML = '<i class="fas fa-times-circle"></i> At least 1 lowercase letter (a-z)';
                isValid = false;
            }
            
            // Check number
            if (/[0-9]/.test(password)) {
                numberReq.classList.remove('invalid');
                numberReq.classList.add('valid');
                numberReq.innerHTML = '<i class="fas fa-check-circle"></i> At least 1 number (0-9)';
            } else {
                numberReq.classList.remove('valid');
                numberReq.classList.add('invalid');
                numberReq.innerHTML = '<i class="fas fa-times-circle"></i> At least 1 number (0-9)';
                isValid = false;
            }
            
            // Check special character
            if (/[@$!%*?&]/.test(password)) {
                specialReq.classList.remove('invalid');
                specialReq.classList.add('valid');
                specialReq.innerHTML = '<i class="fas fa-check-circle"></i> At least 1 special character (@$!%*?&)';
            } else {
                specialReq.classList.remove('valid');
                specialReq.classList.add('invalid');
                specialReq.innerHTML = '<i class="fas fa-times-circle"></i> At least 1 special character (@$!%*?&)';
                isValid = false;
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
                    isValid = false;
                }
            } else {
                confirmMessage.innerHTML = '';
            }
            
            // Enable/disable submit button
            if (password && isValid && password === confirmInput.value) {
                submitBtn.disabled = false;
            } else if (password && !isValid) {
                submitBtn.disabled = true;
            }
        }
        
        if (passwordInput) {
            passwordInput.addEventListener('input', validatePassword);
        }
        
        if (confirmInput) {
            confirmInput.addEventListener('input', validatePassword);
        }
        
        // Form submit validation
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const email = emailInput.value;
            const gmailRegex = /^[a-zA-Z0-9._%+-]+@gmail\.com$/;
            const password = passwordInput.value;
            const isPasswordValid = password.length >= 8 && /[A-Z]/.test(password) && /[a-z]/.test(password) && /[0-9]/.test(password) && /[@$!%*?&]/.test(password);
            
            if (!gmailRegex.test(email)) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Email',
                    text: 'Only @gmail.com email addresses are allowed!',
                    confirmButtonColor: '#dc3545'
                });
            } else if (!isPasswordValid) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Password',
                    text: 'Please meet all password requirements before registering.',
                    confirmButtonColor: '#dc3545'
                });
            }
        });
    </script>
    @endpush
</x-guest-layout>