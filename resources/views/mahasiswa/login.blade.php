<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PKUMI</title>

    @vite('resources/css/app.css')

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        :root {
            --brand-dark-blue: #0A2540;
            --brand-slate: #64748b;
            --brand-light-bg: #F8FAFC;
            --brand-border: #E2E8F0;
            --brand-placeholder: #94A3B8;
            --brand-primary-blue: #0066FF;
            --brand-primary-dark: #0052CC;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .bg-brand-dark-blue { background-color: var(--brand-dark-blue); }
        .text-brand-dark-blue { color: var(--brand-dark-blue); }
        .text-brand-slate { color: var(--brand-slate); }
        .bg-brand-light-bg { background-color: var(--brand-light-bg); }
        .border-brand-border { border-color: var(--brand-border); }
        .placeholder-brand-placeholder::placeholder { color: var(--brand-placeholder); }
        .bg-brand-primary-blue { background-color: var(--brand-primary-blue); }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        .input-field {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .input-field:focus {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.15);
            border-color: var(--brand-primary-blue);
        }
        
        .btn-primary {
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 102, 255, 0.3);
            background-color: var(--brand-primary-dark);
        }
        
        .btn-primary:active {
            transform: translateY(0);
        }
        
        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .divider-text {
            position: relative;
            text-align: center;
            margin: 1.5rem 0;
        }
        
        .divider-text::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: var(--brand-border);
        }
        
        .divider-text span {
            position: relative;
            background: white;
            padding: 0 1rem;
            color: var(--brand-slate);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .social-btn {
            transition: all 0.2s ease;
        }
        
        .social-btn:hover {
            background-color: #F8FAFC;
            border-color: var(--brand-primary-blue);
            transform: translateY(-1px);
        }
    </style>
</head>
<body>

<section id="section-login" class="min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-md">
        <div class="glass-card rounded-2xl p-8 sm:p-10 fade-in">
            
            <div class="text-center mb-8">
                <img src="{{ asset('images/logo0pkumi.png') }}" alt="PKUMI Logo" class="w-32 mx-auto mb-6">
                <h1 class="text-2xl font-semibold text-brand-dark-blue mb-2">Welcome Back</h1>
                <p class="text-sm text-brand-slate">Sign in to continue to your account</p>
            </div>

            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf

                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-sm flex items-start gap-3" role="alert">
                        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <div>
                    <label for="email" class="block text-sm font-medium text-brand-dark-blue mb-2">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                        </div>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" 
                            placeholder="you@example.com" required autofocus 
                            class="input-field w-full h-12 pl-11 pr-4 text-sm bg-brand-light-bg border border-brand-border rounded-lg placeholder-brand-placeholder focus:outline-none focus:ring-2 focus:ring-brand-primary-blue/20">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-brand-dark-blue mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input type="password" id="password" name="password" 
                            placeholder="Enter your password" required 
                            class="input-field w-full h-12 pl-11 pr-4 text-sm bg-brand-light-bg border border-brand-border rounded-lg placeholder-brand-placeholder focus:outline-none focus:ring-2 focus:ring-brand-primary-blue/20">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="w-4 h-4 text-brand-primary-blue border-brand-border rounded focus:ring-2 focus:ring-brand-primary-blue/20">
                        <span class="text-sm text-brand-slate">Remember me</span>
                    </label>
                    <a href="#" class="text-sm text-brand-primary-blue hover:underline font-medium">
                        Forgot Password?
                    </a>
                </div>

                <button type="submit" class="btn-primary w-full h-12 bg-brand-primary-blue text-white text-sm font-medium rounded-lg">
                    Sign In
                </button>

                <div class="divider-text">
                    <span>Or continue with</span>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <button type="button" class="social-btn h-11 border border-brand-border rounded-lg flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        <span class="text-sm font-medium text-gray-700">Google</span>
                    </button>
                    <button type="button" class="social-btn h-11 border border-brand-border rounded-lg flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="#1877F2" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                        <span class="text-sm font-medium text-gray-700">Facebook</span>
                    </button>
                </div>

                <p class="text-center text-sm text-brand-slate pt-2">
                    Don't have an account? 
                    <a href="#" class="text-brand-primary-blue hover:underline font-medium">Sign up</a>
                </p>
            </form>
        </div>

        <p class="text-center text-white text-xs mt-6 opacity-80">
            &copy; 2025 PKUMI. All rights reserved.
        </p>
    </div>
</section>

</body>
</html>