<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IDAP System - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #fff7ed;
        }
        .login-card {
            background-color: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .login-button {
            background-color: #ff6b35;
            border-radius: 8px;
        }
        .login-button:hover {
            background-color: #e55a2b;
        }
        .input-field {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
        }
        .input-field:focus {
            border-color: #ff6b35;
            outline: none;
            box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md mx-auto p-6">
        <!-- Title -->
        <h1 class="text-center text-2xl font-bold text-orange-500 mb-8 uppercase tracking-wide">
            Individual Development Action Plan
        </h1>
        
        <!-- Login Card -->
        <div class="login-card p-8">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <!-- Email Address -->
                <div class="mb-6">
                    <label for="email" class="block text-gray-700 text-sm font-medium mb-2">
                        Email Address
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="input-field w-full px-4 py-3 text-gray-700 placeholder-gray-400"
                        placeholder="Enter your email address"
                        required
                        autocomplete="email"
                        autofocus
                    >
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-gray-700 text-sm font-medium mb-2">
                        Password
                    </label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="input-field w-full px-4 py-3 text-gray-700 placeholder-gray-400"
                        placeholder="Enter your password"
                        required
                        autocomplete="current-password"
                    >
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Remember Me -->
                <div class="mb-6">
                    <label class="flex items-center">
                        <input 
                            type="checkbox" 
                            name="remember" 
                            class="rounded border-gray-300 text-orange-500 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50"
                        >
                        <span class="ml-2 text-sm text-gray-600">Remember me</span>
                    </label>
                </div>
                
                <!-- Login Button -->
                <button type="submit" class="login-button w-full py-3 text-white font-semibold text-center transition duration-200">
                    Login
                </button>
                
                <!-- Forgot Password Link -->
                <div class="mt-4 text-center">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-orange-500 hover:text-orange-600">
                            Forgot your password?
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</body>
</html>
