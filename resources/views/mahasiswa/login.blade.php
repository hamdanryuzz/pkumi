<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PKUMI</title>

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
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #FFFFFF;
            min-height: 100vh;
        }
        
        .login-container {
            display: flex;
            min-height: 100vh;
        }
        
        .login-left {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: #FFFFFF;
        }
        
        .login-right {
            flex: 1;
            background: #FFFFFF; /* Diubah jadi putih */
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            position: relative;
            overflow: hidden;
        }

        /* Animasi dekoratif di sisi kanan agar tidak polos */
        .login-right::before,
        .login-right::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: rgba(10, 37, 64, 0.03); /* Warna sangat transparan dari brand-dark-blue */
            animation: float 8s infinite ease-in-out;
        }

        .login-right::before {
            width: 300px;
            height: 300px;
            top: -100px;
            right: -100px;
            animation-delay: 0s;
        }
        
        .login-right::after {
            width: 200px;
            height: 200px;
            bottom: -80px;
            left: -60px;
            animation-delay: -4s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
            }
            50% {
                transform: translateY(-20px) rotate(10deg);
            }
        }
        
        .background-image {
            position: relative;
            z-index: 10;
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.08));
        }
        
        .card-wrapper {
            width: 100%;
            max-width: 480px; /* Diperbesar dari 420px */
            animation: slideInLeft 0.6s ease-out;
        }
        
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .login-card {
            background: #FFFFFF;
            border-radius: 16px;
            padding: 56px; /* Diperbesar dari 48px */
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .card-header {
            text-align: center;
            margin-bottom: 32px;
        }
        
        .logo {
            width: 100px;
            height: auto;
            margin-bottom: 20px;
        }
        
        .card-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--brand-dark-blue);
            margin-bottom: 8px;
        }
        
        .card-subtitle {
            font-size: 14px;
            color: var(--brand-slate);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: var(--brand-dark-blue);
            margin-bottom: 8px;
        }
        
        .input-wrapper {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            color: #CBD5E1;
            flex-shrink: 0;
        }
        
        .form-input {
            width: 100%;
            height: 48px;
            padding-left: 44px;
            padding-right: 16px;
            font-size: 14px;
            border: 1px solid var(--brand-border);
            border-radius: 10px;
            background: #F8FAFC;
            color: var(--brand-dark-blue);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-family: 'Inter', sans-serif;
        }
        
        .form-input::placeholder {
            color: var(--brand-placeholder);
        }
        
        .form-input:focus {
            outline: none;
            background: #FFFFFF;
            border-color: var(--brand-primary-blue);
            box-shadow: 0 0 0 3px rgba(0, 102, 255, 0.1);
            transform: translateY(-1px);
        }
        
        .form-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            font-size: 14px;
        }
        
        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }
        
        .checkbox-wrapper input {
            cursor: pointer;
            width: 18px;
            height: 18px;
        }
        
        .checkbox-label {
            color: var(--brand-slate);
            cursor: pointer;
        }
        
        .forgot-link {
            color: var(--brand-primary-blue);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }
        
        .forgot-link:hover {
            color: var(--brand-primary-dark);
        }
        
        .submit-btn {
            width: 100%;
            height: 48px;
            background: var(--brand-primary-blue);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-bottom: 24px;
            font-family: 'Inter', sans-serif;
        }
        
        .submit-btn:hover {
            background: var(--brand-primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 102, 255, 0.3);
        }
        
        .submit-btn:active {
            transform: translateY(0);
        }
        
        .divider {
            position: relative;
            text-align: center;
            margin-bottom: 24px;
            font-size: 13px;
            color: var(--brand-slate);
        }
        
        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: var(--brand-border);
        }
        
        .divider span {
            position: relative;
            background: white;
            padding: 0 12px;
            display: inline-block;
        }
        
        .social-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 24px;
        }
        
        .social-btn {
            height: 44px;
            border: 1px solid var(--brand-border);
            background: white;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            font-family: 'Inter', sans-serif;
        }
        
        .social-btn:hover {
            background: #F8FAFC;
            border-color: var(--brand-primary-blue);
            transform: translateY(-1px);
        }
        
        .social-icon {
            width: 18px;
            height: 18px;
        }
        
        .signup-text {
            text-align: center;
            font-size: 14px;
            color: var(--brand-slate);
        }
        
        .signup-link {
            color: var(--brand-primary-blue);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }
        
        .signup-link:hover {
            color: var(--brand-primary-dark);
        }
        
        @media (max-width: 1024px) {
            .login-container {
                flex-direction: column;
            }
            
            .login-right {
                min-height: 250px;
            }
        }
        
        @media (max-width: 640px) {
            .login-left {
                padding: 20px;
            }
            
            .card-wrapper {
                max-width: 100%;
            }
            
            .login-card {
                padding: 36px 24px; /* Lebih responsif */
            }
            
            .card-title {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>

<div class="login-container">
    <!-- Left Side - Login Form -->
    <div class="login-left">
        <div class="card-wrapper">
            <div class="login-card">
                <div class="card-header">
                    <img src="{{ asset('images/logo0pkumi.png') }}" alt="PKUMI Logo" class="logo">
                    <h1 class="card-title">Welcome Back</h1>
                    <p class="card-subtitle">Sign in to continue to your account</p>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                            <input type="email" id="email" name="email" placeholder="you@example.com" required autofocus class="form-input" value="{{ old('email') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <input type="password" id="password" name="password" placeholder="Enter your password" required class="form-input">
                        </div>
                    </div>

                    <div class="form-footer">
                        <label class="checkbox-wrapper">
                            <input type="checkbox" name="remember">
                            <span class="checkbox-label">Remember me</span>
                        </label>
                        <a href="#" class="forgot-link">Forgot Password?</a>
                    </div>

                    <button type="submit" class="submit-btn">Sign In</button>

                    <div class="divider">
                        <span>Or continue with</span>
                    </div>

                    <div class="social-buttons">
                        <button type="button" class="social-btn">
                            <svg class="social-icon" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                            <span>Google</span>
                        </button>
                        <button type="button" class="social-btn">
                            <svg class="social-icon" fill="#1877F2" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                            <span>Facebook</span>
                        </button>
                    </div>

                    <p class="signup-text">
                        Don't have an account? 
                        <a href="#" class="signup-link">Sign up</a>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <!-- Right Side - Now white with subtle animated circles -->
    <div class="login-right">
        <img src="{{ asset('images/pkumibg.png') }}" alt="PKUMI Background" class="background-image">
    </div>
</div>

</body>
</html>