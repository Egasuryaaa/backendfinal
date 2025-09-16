<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar - IwakMart</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="/js/auth.js"></script>
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
            max-width: 500px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            animation: slideInUp 1.5s ease-out 0.9s both;
            margin-top: 20px;
            margin-bottom: 20px;
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
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
            color: #333;
        }

        .input-container {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 12px;
            color: #0D47A1;
            opacity: 0.6;
        }

        .form-input {
            width: 100%;
            padding: 14px 14px 14px 40px;
            border: 1px solid #E0E0E0;
            border-radius: 12px;
            background: #F9FAFC;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .form-input:focus {
            outline: none;
            border-color: #2196F3;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(33, 150, 243, 0.15);
        }

        /* Select dropdown styling */
        select.form-input {
            appearance: none;
            background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 4 5"><path fill="%23666" d="M2 0L0 2h4zm0 5L0 3h4z"/></svg>');
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 12px;
            cursor: pointer;
        }

        select.form-input option {
            padding: 10px;
        }

        /* Location fields styling */
        .location-container {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .location-btn {
            padding: 10px 16px;
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .location-btn:hover {
            background: linear-gradient(135deg, #45a049 0%, #3d8b40 100%);
            transform: translateY(-1px);
        }

        .location-btn:active {
            transform: translateY(0);
        }

        /* Role-based field styling */
        #locationFields.show {
            display: block;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                max-height: 0;
            }
            to {
                opacity: 1;
                max-height: 300px;
            }
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            color: #666;
            cursor: pointer;
        }

        /* Checkbox and Button */
        .checkbox-container {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .checkbox-label {
            font-size: 14px;
            color: #666;
            margin-left: 8px;
        }

        .forgot-password {
            font-size: 14px;
            color: #2196F3;
            text-decoration: none;
            margin-left: auto;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        .submit-btn {
            width: 100%;
            padding: 14px;
            background: #0D47A1;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .submit-btn:hover {
            background: #1565C0;
        }

        .register-link {
            text-align: center;
            font-size: 14px;
            color: #666;
            margin-top: 20px;
        }

        .register-link a {
            color: #2196F3;
            text-decoration: none;
            font-weight: 600;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        /* Alert styles */
        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideInDown 0.5s ease-out;
        }

        .alert-success {
            background-color: #e8f5e9;
            color: #2e7d32;
            border-left: 4px solid #4caf50;
        }

        .alert-error {
            background-color: #ffebee;
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

        /* Responsive styles */
        @media (max-width: 768px) {
            .wave-container {
                height: 25vh;
            }
            
            .header-content {
                top: 12vh;
            }
            
            .logo {
                width: 70px;
                height: 70px;
                font-size: 36px;
            }
            
            .app-title {
                font-size: 28px;
            }
            
            .form-container {
                padding: 25px 20px;
                margin-top: 0;
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
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
        <!-- Wave Animation -->
        <div class="wave-container">
            <div class="wave"></div>
            <div class="wave"></div>
            
            <!-- Header Content -->
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
                <h2 class="form-title">Daftar ke Akun Anda</h2>
                <p class="form-subtitle">Silakan isi data untuk mendaftar</p>
                
                <!-- Alert Container (Hidden by default) -->
                <div id="alert-container"></div>
                
                <!-- Registration Form -->
                <form id="registerForm" novalidate>
                    <!-- Full Name Field -->
                    <div class="form-group">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <div class="input-container">
                            <div class="input-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                class="form-input" 
                                required 
                                autocomplete="off"
                                placeholder="Masukkan nama lengkap Anda"
                            >
                        </div>
                    </div>

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
                                required 
                                autocomplete="off"
                                placeholder="Masukkan email Anda"
                            >
                        </div>
                    </div>
                    
                    <!-- Phone Number Field -->
                    <div class="form-group">
                        <label for="phone" class="form-label">Nomor Telepon</label>
                        <div class="input-container">
                            <div class="input-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <input 
                                type="tel" 
                                id="phone" 
                                name="phone" 
                                class="form-input" 
                                required 
                                autocomplete="off"
                                placeholder="Masukkan nomor telepon Anda"
                            >
                        </div>
                    </div>

                    <!-- Role Selection Field -->
                    <div class="form-group">
                        <label for="role" class="form-label">Peran/Role</label>
                        <div class="input-container">
                            <div class="input-icon">
                                <i class="fas fa-user-tag"></i>
                            </div>
                            <select 
                                id="role" 
                                name="role" 
                                class="form-input" 
                                required
                            >
                                <option value="">Pilih peran Anda</option>
                                <option value="pembeli">Pembeli - Beli ikan untuk konsumsi</option>
                                <option value="penjual_biasa">Penjual Biasa - Jual ikan hasil tangkapan</option>
                                <option value="pengepul">Pengepul - Kumpulkan dan distribusikan ikan</option>
                                <option value="pemilik_tambak">Pemilik Tambak - Budidaya ikan</option>
                            </select>
                        </div>
                    </div>

                    <!-- Location Fields (Optional, will be shown based on role) -->
                    <div id="locationFields" class="form-group" style="display: none;">
                        <label class="form-label">Lokasi (Opsional)</label>
                        <div class="location-container">
                            <div class="form-group">
                                <div class="input-container">
                                    <div class="input-icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <input 
                                        type="number" 
                                        id="latitude" 
                                        name="latitude" 
                                        class="form-input" 
                                        step="any"
                                        placeholder="Latitude (contoh: -6.2088)"
                                    >
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-container">
                                    <div class="input-icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <input 
                                        type="number" 
                                        id="longitude" 
                                        name="longitude" 
                                        class="form-input" 
                                        step="any"
                                        placeholder="Longitude (contoh: 106.8456)"
                                    >
                                </div>
                            </div>
                            <button type="button" class="location-btn" onclick="getCurrentLocation()">
                                <i class="fas fa-location-arrow"></i> Gunakan Lokasi Saat Ini
                            </button>
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
                            <div class="password-toggle" onclick="togglePassword('password')">
                                <i class="far fa-eye"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Password Confirmation Field -->
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <div class="input-container">
                            <div class="input-icon">
                                <i class="fas fa-lock"></i>
                            </div>
                            <input 
                                type="password" 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                class="form-input" 
                                required 
                                autocomplete="off"
                                placeholder="Masukkan kembali password Anda"
                            >
                            <div class="password-toggle" onclick="togglePassword('password_confirmation')">
                                <i class="far fa-eye"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Terms and Conditions Checkbox -->
                    <div class="checkbox-container">
                        <input type="checkbox" id="terms" name="terms" required>
                        <label for="terms" class="checkbox-label">
                            Saya menyetujui <a href="#" class="forgot-password">syarat dan ketentuan</a>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="submit-btn">DAFTAR</button>
                    
                    <!-- Login Link -->
                    <div class="register-link">
                        Sudah punya akun? <a href="{{ route('login') }}">Masuk sekarang</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="/js/auth.js"></script>
    <script>
        // Password toggle functionality
        function togglePassword(fieldId) {
            const passwordField = document.getElementById(fieldId);
            const toggleIcon = event.currentTarget.querySelector('i');
            
            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleIcon.className = "far fa-eye-slash";
            } else {
                passwordField.type = "password";
                toggleIcon.className = "far fa-eye";
            }
        }

        // Show Alert Function
        window.localShowAlert = function(message, type = 'info') {
            const alertContainer = document.getElementById('alert-container');
            
            // Clear previous alerts
            alertContainer.innerHTML = '';
            
            // Create alert element
            const alert = document.createElement('div');
            alert.className = `alert alert-${type}`;
            
            // Icon based on alert type
            const iconClass = {
                'success': 'fas fa-check-circle',
                'error': 'fas fa-exclamation-circle',
                'warning': 'fas fa-exclamation-triangle',
                'info': 'fas fa-info-circle'
            }[type] || 'fas fa-info-circle';
            
            alert.innerHTML = `
                <i class="${iconClass}"></i>
                <span>${message}</span>
            `;
            
            alertContainer.appendChild(alert);
            
            // Auto remove after 5 seconds for success messages
            if (type === 'success') {
                setTimeout(() => {
                    alert.remove();
                }, 5000);
            }
        };

        // Form validation and submission
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Basic form validation
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const phone = document.getElementById('phone').value;
            const password = document.getElementById('password').value;
            const passwordConfirmation = document.getElementById('password_confirmation').value;
            const terms = document.getElementById('terms').checked;
            
            // Reset all input borders
            document.querySelectorAll('.form-input').forEach(input => {
                input.style.borderColor = '';
                input.style.borderWidth = '';
            });
            
            // Validate name
            if (!name) {
                document.getElementById('name').style.borderColor = '#F44336';
                document.getElementById('name').style.borderWidth = '2px';
                localShowAlert('Nama tidak boleh kosong', 'error');
                return;
            }
            
            // Validate email format
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email || !emailRegex.test(email)) {
                document.getElementById('email').style.borderColor = '#F44336';
                document.getElementById('email').style.borderWidth = '2px';
                localShowAlert('Email tidak valid', 'error');
                return;
            }
            
            // Validate phone
            if (!phone) {
                document.getElementById('phone').style.borderColor = '#F44336';
                document.getElementById('phone').style.borderWidth = '2px';
                localShowAlert('Nomor telepon tidak boleh kosong', 'error');
                return;
            }
            
            // Validate password length
            if (password.length < 8) {
                document.getElementById('password').style.borderColor = '#F44336';
                document.getElementById('password').style.borderWidth = '2px';
                localShowAlert('Password minimal 8 karakter', 'error');
                return;
            }
            
            // Validate password match
            if (password !== passwordConfirmation) {
                document.getElementById('password_confirmation').style.borderColor = '#F44336';
                document.getElementById('password_confirmation').style.borderWidth = '2px';
                localShowAlert('Konfirmasi password tidak sesuai', 'error');
                return;
            }
            
            // Validate terms
            if (!terms) {
                localShowAlert('Anda harus menyetujui syarat dan ketentuan', 'error');
                return;
            }
            
            // Validate role
            const role = document.getElementById('role').value;
            if (!role) {
                document.getElementById('role').style.borderColor = '#F44336';
                document.getElementById('role').style.borderWidth = '2px';
                localShowAlert('Silakan pilih peran Anda', 'error');
                return;
            }
            
            // Get location data if provided
            const latitude = document.getElementById('latitude').value;
            const longitude = document.getElementById('longitude').value;
            
            try {
                // Show loading state
                const submitBtn = document.querySelector('.submit-btn');
                const originalText = submitBtn.innerText;
                submitBtn.innerText = 'MENDAFTAR...';
                submitBtn.disabled = true;
                
                // Prepare registration data
                const registrationData = {
                    name,
                    email,
                    phone,
                    password,
                    password_confirmation: passwordConfirmation,
                    role
                };

                // Add coordinates if provided
                if (latitude && longitude) {
                    registrationData.latitude = parseFloat(latitude);
                    registrationData.longitude = parseFloat(longitude);
                }
                
                // Send registration request
                const response = await fetch('/api/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(registrationData)
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    // Registration successful
                    localShowAlert('Pendaftaran berhasil! Silakan login dengan akun Anda.', 'success');
                    
                    // Reset form
                    document.getElementById('registerForm').reset();
                    
                    // Redirect to login page after a delay
                    setTimeout(() => {
                        window.location.href = '/login';
                    }, 2000);
                } else {
                    // Registration failed
                    const errorMessage = data.message || 'Pendaftaran gagal. Silakan coba lagi.';
                    
                    // Check for validation errors
                    if (data.errors) {
                        const firstError = Object.values(data.errors)[0];
                        localShowAlert(firstError[0] || errorMessage, 'error');
                        
                        // Highlight error fields
                        for (const field in data.errors) {
                            const element = document.getElementById(field);
                            if (element) {
                                element.style.borderColor = '#F44336';
                                element.style.borderWidth = '2px';
                            }
                        }
                    } else {
                        localShowAlert(errorMessage, 'error');
                    }
                }
                
                // Reset button state
                submitBtn.innerText = originalText;
                submitBtn.disabled = false;
                
            } catch (error) {
                console.error('Registration error:', error);
                localShowAlert('Terjadi kesalahan saat mendaftar. Silakan coba lagi.', 'error');
                
                // Reset button state
                document.querySelector('.submit-btn').innerText = 'DAFTAR';
                document.querySelector('.submit-btn').disabled = false;
            }
        });
        
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
        document.getElementById('password').addEventListener('blur', function() {
            const password = this.value;
            
            if (password && password.length < 8) {
                this.style.borderColor = '#F44336';
                this.style.borderWidth = '2px';
            } else {
                this.style.borderColor = '';
                this.style.borderWidth = '';
            }
        });
        
        // Password confirmation validation
        document.getElementById('password_confirmation').addEventListener('blur', function() {
            const password = document.getElementById('password').value;
            const passwordConfirmation = this.value;
            
            if (passwordConfirmation && password !== passwordConfirmation) {
                this.style.borderColor = '#F44336';
                this.style.borderWidth = '2px';
            } else {
                this.style.borderColor = '';
                this.style.borderWidth = '';
            }
        });
        
        // Role selection handler
        document.getElementById('role').addEventListener('change', function() {
            const role = this.value;
            const locationFields = document.getElementById('locationFields');
            
            // Show location fields for roles that benefit from location data
            if (role === 'pengepul' || role === 'pemilik_tambak') {
                locationFields.style.display = 'block';
                locationFields.classList.add('show');
            } else {
                locationFields.style.display = 'none';
                locationFields.classList.remove('show');
                // Clear location fields when hiding
                document.getElementById('latitude').value = '';
                document.getElementById('longitude').value = '';
            }
        });

        // Location functionality
        function getCurrentLocation() {
            if (!navigator.geolocation) {
                localShowAlert('Geolocation tidak didukung oleh browser Anda', 'error');
                return;
            }

            const options = {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 300000 // 5 minutes
            };

            // Show loading state
            const locationBtn = document.querySelector('.location-btn');
            const originalText = locationBtn.innerHTML;
            locationBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mendapatkan Lokasi...';
            locationBtn.disabled = true;

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const { latitude, longitude } = position.coords;
                    document.getElementById('latitude').value = latitude.toFixed(6);
                    document.getElementById('longitude').value = longitude.toFixed(6);
                    
                    localShowAlert('Lokasi berhasil didapatkan!', 'success');
                    
                    // Reset button state
                    locationBtn.innerHTML = originalText;
                    locationBtn.disabled = false;
                },
                (error) => {
                    let errorMessage = 'Gagal mendapatkan lokasi: ';
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            errorMessage += 'Akses lokasi ditolak. Silakan izinkan akses lokasi di browser Anda.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMessage += 'Informasi lokasi tidak tersedia.';
                            break;
                        case error.TIMEOUT:
                            errorMessage += 'Waktu permintaan lokasi habis.';
                            break;
                        default:
                            errorMessage += 'Terjadi kesalahan yang tidak diketahui.';
                            break;
                    }
                    localShowAlert(errorMessage, 'error');
                    
                    // Reset button state
                    locationBtn.innerHTML = originalText;
                    locationBtn.disabled = false;
                },
                options
            );
        }
        
        // Focus first input on page load
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                document.getElementById('name').focus();
            }, 500);
        });
    </script>
</body>
</html>
