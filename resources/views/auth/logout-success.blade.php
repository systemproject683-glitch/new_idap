<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logged Out - IDAP System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #fff7ed;
        }
        .success-card {
            background-color: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            padding: 2rem;
        }
        .success-icon {
            width: 64px;
            height: 64px;
            background-color: #10b981;
            border-radius: 50%;
            margin: 0 auto 1rem;
        }
        .success-message {
            color: #059669;
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
        }
        .login-link {
            color: #ff6b35;
            text-decoration: underline;
            margin-top: 1rem;
        }
        .login-link:hover {
            color: #e55a2b;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md mx-auto p-6">
        <!-- Success Message -->
        <div class="success-card">
            <div class="success-icon">
                <svg class="w-full h-full text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 018 0l-8-8-4-4-4m0 0l-8 8-4 4-4zm-1 0h-1a1 1 0 00-2 0v-2a1 1 0 002 0v-3a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 001 1m-6 0h6z"/>
                </svg>
            </div>
            <h2 class="success-message">You have been successfully logged out</h2>
            <p class="text-gray-600 mb-4">Thank you for using the IDAP system.</p>
            <a href="{{ route('login') }}" class="login-link">
                Click here to login again
            </a>
        </div>
    </div>
    
    <script>
        // Prevent back button navigation
        history.pushState(null, null, location.href);
        window.onpopstate = function () {
            history.go(1);
        };
        
        // Clear any remaining storage
        localStorage.clear();
        sessionStorage.clear();
    </script>
</body>
</html>
