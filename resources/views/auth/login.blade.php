<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IDAP System - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body class="font-sans antialiased bg-gray-100 flex items-center justify-center min-h-screen relative overflow-hidden">

    <!-- Background Shapes -->
    <div class="absolute top-0 left-0 w-96 h-96 bg-orange-500 transform rotate-45 -translate-x-1/2 -translate-y-1/2 opacity-20"></div>
    <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-black transform -rotate-45 translate-x-1/2 translate-y-1/2 opacity-10"></div>
    <div class="absolute top-1/4 right-0 w-64 h-64 bg-orange-500 transform rotate-12 translate-x-1/4 -translate-y-1/4 opacity-15"></div>
    <div class="absolute bottom-1/4 left-0 w-48 h-48 bg-gray-800 transform -rotate-12 -translate-x-1/4 translate-y-1/4 opacity-10"></div>

    <!-- Main Card -->
    <div class="relative z-10 bg-white rounded-3xl shadow-2xl flex max-w-6xl w-full overflow-hidden login-card">

        <!-- Left Section - University Info -->
        <div class="w-1/2 p-12 flex flex-col bg-white">
            
            <!-- University Logo -->
            <div class="flex flex-col items-center mb-8">
                <div class="university-logo-circle">
                    <img src="{{ asset('images/cvsu-logo.png') }}" alt="CvSU Logo" class="w-20 h-20 object-contain">
                </div>
                <div class="mt-4 text-center">
                    <div class="text-lg font-bold text-gray-800">CAVITE STATE UNIVERSITY</div>
                    <div class="text-sm text-gray-500">Since 1906</div>
                </div>
            </div>

            <!-- Vision Section -->
            <div class="mb-6">
                <div class="flex items-start gap-3">
                    <div class="section-icon-circle vision-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </div>
                    <div>
                        <h3 class="section-title">VISION</h3>
                        <p class="section-text">The premier university in historic Cavite globally recognized for excellence in character development, academics, research, innovation and sustainable community engagement.</p>
                    </div>
                </div>
            </div>

            <!-- Mission Section -->
            <div class="mb-6">
                <div class="flex items-start gap-3">
                    <div class="section-icon-circle mission-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <circle cx="12" cy="12" r="3"></circle>
                            <path d="M12 2v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="section-title">MISSION</h3>
                        <p class="section-text">Cavite State University shall provide excellent, equitable and relevant educational opportunities in the arts, sciences and technology through quality instruction and responsive research and development activities.</p>
                    </div>
                </div>
            </div>

            <!-- Core Values Section -->
            <div>
                <div class="flex items-start gap-3">
                    <div class="section-icon-circle values-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>
                    </div>
                    <div>
                        <h3 class="section-title">CORE VALUES</h3>
                        <ul class="section-list">
                            <li>• Truth</li>
                            <li>• Integrity</li>
                            <li>• Excellence</li>
                            <li>• Service</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Section - Login Form -->
        <div class="w-1/2 p-12 flex flex-col justify-center bg-gray-50">
            
            <!-- University Title -->
            <div class="mb-8">
                <h1 class="university-main-title">CAVITE STATE UNIVERSITY</h1>
                <div class="title-underline"></div>
            </div>

            <form id="login-form" method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Student/Faculty ID -->
                <div class="mb-5">
                    <label class="form-label">Student/Faculty ID</label>
                    <div class="form-input-wrapper">
                        <svg class="form-input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <input
                            type="text"
                            id="email"
                            name="email"
                            class="form-input"
                            placeholder="Enter your Student/Faculty ID"
                            required
                            autocomplete="email"
                            autofocus
                            value="{{ old('email') }}"
                        >
                    </div>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label class="form-label">Password</label>
                    <div class="form-input-wrapper">
                        <svg class="form-input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-input"
                            placeholder="Enter your password"
                            required
                            autocomplete="current-password"
                        >
                        <button type="button" class="password-toggle-btn" id="passwordToggleBtn" title="Show/hide password">
                            <svg id="eyeIcon" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Login Button -->
                <button type="submit" form="login-form" class="login-btn w-full py-4 text-white font-bold rounded-xl text-lg">
                    LOGIN
                </button>

                <!-- CvSU Email Login Button -->
                <a href="{{ route('auth.google') }}" class="cvsu-email-btn w-full py-3 text-center font-semibold rounded-xl text-sm mt-4 flex items-center justify-center gap-2">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                    Login using CvSU Email
                </a>

            </form>

            <!-- Need Help Section -->
            <div class="mt-8 pt-6 border-t border-gray-300">
                <p class="text-center text-sm text-gray-600 mb-3">Need help?</p>
                <div class="flex justify-center gap-3 text-sm">
                    <a href="#" class="help-link">Forgot Password?</a>
                    <span class="text-gray-400">|</span>
                    <a href="#" class="help-link">Help Center</a>
                    <span class="text-gray-400">|</span>
                    <a href="#" class="help-link">Contact Us</a>
                </div>
            </div>

        </div>

    </div>

    <script>
        // Password visibility toggle
        const passwordToggleBtn = document.getElementById('passwordToggleBtn');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        passwordToggleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>';
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
            }
        });
    </script>
</body>
</html>

