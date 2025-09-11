<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk - IwakMart</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            height: 100vh;
            background: linear-gradient(135deg, #0D47A1, #1565C0, #1976D2, #2196F3);
            overflow-x: hidden;
        }

        .container {
            height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        /* Wave Animation */
        .wave-container {
            position: relative;
            height: 35vh;
            overflow: hidden;
        }

        .wave {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            clip-path: polygon(0 20px, 100% 0, 100% 100%, 0 100%);
            animation: wave 3s ease-in-out infinite;
        }

        .wave:nth-child(2) {
            background: rgba(255, 255, 255, 0.05);
            animation-delay: 1.5s;
            clip-path: polygon(0 40px, 100% 10px, 100% 100%, 0 100%);
        }

        @keyframes wave {
            0%, 100% {
                clip-path: polygon(0 20px, 100% 0, 100% 100%, 0 100%);
            }
            50% {
                clip-path: polygon(0 0, 100% 20px, 100% 100%, 0 100%);
            }
        }

        /* Header Section */
        .header-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: white;
            z-index: 10;
        }

        .logo-container {
            background: rgba(255, 255, 255, 0.15);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            padding: 16px;
            display: inline-block;
            margin-bottom: 16px;
            backdrop-filter: blur(10px);
        }

        .logo {
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: white;
        }

        .app-title {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 8px;
            animation: fadeInUp 1.5s ease-out 0.3s both;
        }

        .app-subtitle {
            font-size: 16px;
            opacity: 0.9;
            animation: fadeInUp 1.5s ease-out 0.6s both;
        }

        /* Form Section */
        .form-section {
            flex: 1;
            padding: 20px;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            overflow-y: auto;
        }

        .form-container {
            background: white;
            border-radius: 30px;
            padding: 30px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            animation: slideInUp 1.5s ease-out 0.9s both;
            margin-top: 20px;
        }

        .form-title {
            font-size: 24px;
            font-weight: bold;
            color: #0D47A1;
            text-align: center;
            margin-bottom: 8px;
        }

        .form-subtitle {
            font-size: 14px;
            color: #666;
            text-align: center;
            margin-bottom: 32px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #0D47A1;
            margin-bottom: 8px;
        }

        .input-container {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(25, 118, 210, 0.1);
            padding: 8px;
            border-radius: 8px;
            color: #1976D2;
            font-size: 20px;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-input {
            width: 100%;
            padding: 16px 16px 16px 60px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            font-size: 16px;
            background: #F8FBFF;
            transition: all 0.3s ease;
            font-family: 'Inter', sans-serif;
        }

        .form-input:focus {
            outline: none;
            border: 2px solid #1976D2;
            background: white;
            box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.1);
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #1976D2;
            cursor: pointer;
            font-size: 20px;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: #0D47A1;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 16px 0 24px 0;
            flex-wrap: wrap;
            gap: 8px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #666;
        }

        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #1976D2;
            border-radius: 4px;
        }

        .forgot-password {
            color: #1976D2;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .forgot-password:hover {
            color: #0D47A1;
            text-decoration: underline;
        }

        .login-btn {
            width: 100%;
            padding: 16px;
            background: #1976D2;
            border: none;
            border-radius: 16px;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 8px 16px rgba(25, 118, 210, 0.4);
            font-family: 'Inter', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .login-btn:hover {
            background: #0D47A1;
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(25, 118, 210, 0.4);
        }

        .login-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .spinner {
            width: 20px;
            height: 20px;
            border: 2px solid transparent;
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }

        .register-link a {
            color: #1976D2;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .register-link a:hover {
            color: #0D47A1;
            text-decoration: underline;
        }

        /* Alert Messages */
        .alert {
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            animation: slideIn 0.5s ease-out;
        }

        .alert-error {
            background: #FFEBEE;
            color: #C62828;
            border-left: 4px solid #F44336;
        }

        .alert-success {
            background: #E8F5E8;
            color: #2E7D32;
            border-left: 4px solid #4CAF50;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .wave-container {
                height: 25vh;
            }
            
            .logo {
                width: 80px;
                height: 80px;
                font-size: 36px;
            }
            
            .app-title {
                font-size: 24px;
            }
            
            .app-subtitle {
                font-size: 12px;
            }
            
            .form-container {
                margin: 16px;
                padding: 20px;
            }
            
            .form-title {
                font-size: 20px;
            }
            
            .form-input {
                padding: 12px 12px 12px 52px;
                font-size: 14px;
            }
            
            .input-icon {
                font-size: 18px;
                width: 32px;
                height: 32px;
            }
            
            .login-btn {
                padding: 12px;
                font-size: 14px;
            }
            
            .form-options {
                font-size: 12px;
            }
        }

        @media (max-width: 480px) {
            .form-options {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
        }

        /* Small screen handling */
        @media (max-height: 600px) {
            .wave-container {
                height: 20vh;
            }
            
            .form-container {
                margin-top: 10px;
                padding: 20px;
            }
        }

        /* Alert Styling */
        .alert {
            padding: 12px 16px;
            margin-bottom: 16px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            font-weight: 500;
            animation: slideIn 0.3s ease-out;
        }

        .alert-success {
            background-color: #e8f5e8;
            color: #2e7d32;
            border-left: 4px solid #4caf50;
        }

        .alert-error {
            background-color: #ffeaea;
            color: #c62828;
            border-left: 4px solid #f44336;
        }

        .alert-warning {
            background-color: #fff8e1;
            color: #f57c00;
            border-left: 4px solid #ff9800;
        }

        .alert-info {
            background-color: #e3f2fd;
            color: #1565c0;
            border-left: 4px solid #2196f3;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Section with Wave Animation -->
        <div class="wave-container">
            <div class="wave"></div>
            <div class="wave"></div>
            
            <div class="header-content">
                <div class="logo-container">
                    <div class="logo">
                        <i class="fas fa-fish"></i>
                    </div>
                </div>
                <h1 class="app-title">IwakMart</h1>
                <p class="app-subtitle">Market Ikan Terpercaya</p>
            </div>
        </div>

        <!-- Form Section -->
        <div class="form-section">
            <div class="form-container">
                <h2 class="form-title">Masuk ke Akun Anda</h2>
                <p class="form-subtitle">Silakan masuk untuk melanjutkan</p>

                @if ($errors->any())
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.submit') }}" id="loginForm">
                    @csrf
                    
                    <!-- Email Field -->
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <div class="input-container">
                            <div class="input-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                class="form-input" 
                                value="{{ old('email') }}" 
                                required 
                                autocomplete="off"
                                placeholder="Masukkan email Anda"
                            >
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-container">
                            <div class="input-icon">
                                <i class="fas fa-lock"></i>
                            </div>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="form-input" 
                                required 
                                autocomplete="off"
                                placeholder="Masukkan password Anda"
                            >
                            <i class="fas fa-eye-slash password-toggle" id="togglePassword"></i>
                        </div>
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="form-options">
                        <label class="remember-me">
                            <input type="checkbox" name="remember_me" {{ old('remember_me') ? 'checked' : '' }}>
                            <span>Ingat saya</span>
                        </label>
                        <a href="#" class="forgot-password" onclick="showComingSoon(event)">Lupa Password?</a>
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="login-btn" id="loginButton">
                        <span id="buttonText">MASUK</span>
                        <span id="buttonSpinner" style="display: none;">
                            <div class="spinner"></div>
                        </span>
                    </button>
                </form>

                <!-- Register Link -->
                <div class="register-link">
                    Belum punya akun? <a href="{{ route('register') }}">Daftar Sekarang</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Password toggle functionality
        document.getElementById('togglePassword').addEventListener('click', function() {
            const password = document.getElementById('password');
            const isPassword = password.getAttribute('type') === 'password';
            
            password.setAttribute('type', isPassword ? 'text' : 'password');
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });

        // Form submission with loading state and Bearer token handling
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault(); // Prevent default form submission
            
            const button = document.getElementById('loginButton');
            const buttonText = document.getElementById('buttonText');
            const buttonSpinner = document.getElementById('buttonSpinner');
            
            button.disabled = true;
            buttonText.style.display = 'none';
            buttonSpinner.style.display = 'inline-flex';
            
            try {
                const formData = new FormData(this);
                
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({
                        email: formData.get('email'),
                        password: formData.get('password')
                    })
                });

                const data = await response.json();
                console.log('Login response:', data); // Debug log

                if (response.ok && data.success) {
                    // Simpan token berdasarkan remember_me
                    const rememberMe = formData.get('remember_me');
                    const token = data.data.access_token;
                    
                    console.log('Saving token:', token);
                    
                    // Clear any existing tokens first
                    clearAuthToken();
                    
                    if (rememberMe) {
                        setAuthToken(token, true);
                        console.log('Token saved to localStorage and cookie');
                    } else {
                        setAuthToken(token, false);
                        console.log('Token saved to sessionStorage and cookie');
                    }
                    
                    // Simpan data user juga
                    if (data.data.user) {
                        setUserData(data.data.user, rememberMe);
                    }
                    
                    // Show success message
                    localShowAlert('Login berhasil! Mengalihkan ke fishmarket...', 'success');
                    
                    console.log('Login successful, token saved. Redirecting to fishmarket...');
                    console.log('Token in storage:', getAuthToken() ? 'Found' : 'Not found');
                    
                    // Wait for cookie to be set and verify before redirect
                    let retries = 0;
                    const checkToken = () => {
                        const savedToken = getAuthToken();
                        const cookieToken = getCookie('auth_token');
                        
                        console.log('Token check attempt', retries + 1);
                        console.log('Storage token:', savedToken ? 'Found' : 'Not found');
                        console.log('Cookie token:', cookieToken ? 'Found' : 'Not found');
                        
                        if (savedToken && cookieToken) {
                            console.log('Tokens verified, redirecting to fishmarket');
                            window.location.href = '/fishmarket';
                        } else if (retries < 5) { // Reduce retries
                            retries++;
                            setTimeout(checkToken, 100);
                        } else {
                            console.log('Token save verification failed, redirecting anyway');
                            // Force redirect even if verification fails
                            window.location.href = '/fishmarket';
                        }
                    };
                    
                    // Start checking after a brief delay
                    setTimeout(checkToken, 50);
                    
                } else {
                    localShowAlert(data.message || 'Login gagal. Periksa email dan password Anda.', 'error');
                }
            } catch (error) {
                console.error('Login error:', error);
                localShowAlert('Terjadi kesalahan saat login. Silakan coba lagi.', 'error');
            } finally {
                // Reset button state
                button.disabled = false;
                buttonText.style.display = 'inline';
                buttonSpinner.style.display = 'none';
            }
        });

        // Show coming soon message for forgot password
        function showComingSoon(event) {
            event.preventDefault();
            
            // Create and show custom alert
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-success';
            alertDiv.innerHTML = `
                <i class="fas fa-info-circle"></i>
                <span>Fitur lupa password segera hadir</span>
            `;
            
            const form = document.getElementById('loginForm');
            form.insertBefore(alertDiv, form.firstChild);
            
            // Remove alert after 3 seconds
            setTimeout(function() {
                alertDiv.remove();
            }, 3000);
        }

        // Universal alert function
        function localShowAlert(message, type = 'info') {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type}`;
            
            const iconMap = {
                'success': 'fas fa-check-circle',
                'error': 'fas fa-exclamation-circle',
                'warning': 'fas fa-exclamation-triangle',
                'info': 'fas fa-info-circle'
            };
            
            alertDiv.innerHTML = `
                <i class="${iconMap[type] || iconMap['info']}"></i>
                <span>${message}</span>
            `;
            
            const form = document.getElementById('loginForm');
            form.insertBefore(alertDiv, form.firstChild);
            
            // Remove alert after 5 seconds
            setTimeout(function() {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
        }

        // Email validation
        document.getElementById('email').addEventListener('blur', function() {
            const email = this.value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (email && !emailRegex.test(email)) {
                this.style.borderColor = '#F44336';
                this.style.borderWidth = '2px';
            } else {
                this.style.borderColor = '';
                this.style.borderWidth = '';
            }
        });

        // Password validation
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            
            if (password.length > 0 && password.length < 6) {
                this.style.borderColor = '#F44336';
                this.style.borderWidth = '2px';
            } else {
                this.style.borderColor = '';
                this.style.borderWidth = '';
            }
        });

        // Keyboard accessibility
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                const activeElement = document.activeElement;
                if (activeElement.tagName === 'INPUT' && !document.getElementById('loginButton').disabled) {
                    e.preventDefault();
                    document.getElementById('loginForm').submit();
                }
            }
        });

        // Auto-focus on email field when page loads
        window.addEventListener('load', function() {
            setTimeout(function() {
                document.getElementById('email').focus();
            }, 1000); // Wait for animations
        });

        // Handle responsive keyboard on mobile
        window.addEventListener('resize', function() {
            if (window.innerHeight < 500) {
                document.querySelector('.wave-container').style.height = '15vh';
            }
        });

        // Token utility functions for use across the app
        function getAuthToken() {
            return localStorage.getItem('auth_token') || sessionStorage.getItem('auth_token');
        }

        function setAuthToken(token, remember = false) {
            if (remember) {
                localStorage.setItem('auth_token', token);
                sessionStorage.removeItem('auth_token');
            } else {
                sessionStorage.setItem('auth_token', token);
                localStorage.removeItem('auth_token');
            }
        }

        function clearAuthToken() {
            localStorage.removeItem('auth_token');
            sessionStorage.removeItem('auth_token');
            localStorage.removeItem('user_data');
            sessionStorage.removeItem('user_data');
        }

        function isAuthenticated() {
            return !!getAuthToken();
        }

        function getUserData() {
            const userData = localStorage.getItem('user_data') || sessionStorage.getItem('user_data');
            return userData ? JSON.parse(userData) : null;
        }

        // Make token functions globally available
        window.getAuthToken = getAuthToken;
        window.setAuthToken = setAuthToken;
        window.clearAuthToken = clearAuthToken;
        window.isAuthenticated = isAuthenticated;
        window.getUserData = getUserData;
        window.localShowAlert = localShowAlert;
    </script>
    
    <!-- Bearer Token Authentication (load after local functions) -->
    <script src="{{ asset('js/auth.js') }}"></script>
</body>
</html>
