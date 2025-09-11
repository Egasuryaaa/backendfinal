<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Profil Saya - IwakMart</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #F0F8FF;
            min-height: 100vh;
            line-height: 1.6;
        }

        .container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* App Bar */
        .custom-app-bar {
            background: linear-gradient(135deg, #1565C0, #0D47A1, #002171);
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            position: relative;
            overflow: visible;
            z-index: 1000;
        }

        .app-bar-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 2;
            gap: 20px;
        }

        .app-bar-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }

        .app-title {
            color: white;
            font-size: 24px;
            font-weight: 800;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .back-button {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.2);
            border: none;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .back-button:hover {
            background: rgba(255,255,255,0.3);
            transform: scale(1.1);
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 0;
        }

        /* Profile Header */
        .profile-header {
            margin: 20px;
            padding: 24px;
            background: linear-gradient(135deg, #2196F3, #1976D2);
            border-radius: 25px;
            box-shadow: 0 10px 30px rgba(25, 118, 210, 0.3);
            color: white;
            text-align: center;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }

        .profile-avatar i {
            font-size: 50px;
            color: #1976D2;
        }

        .profile-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .profile-email {
            font-size: 16px;
            color: rgba(255,255,255,0.8);
            margin-bottom: 8px;
        }

        .profile-phone {
            font-size: 14px;
            color: rgba(255,255,255,0.7);
            margin-bottom: 20px;
        }

        .edit-profile-btn {
            background: white;
            color: #1976D2;
            border: none;
            padding: 12px 24px;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .edit-profile-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
        }

        /* Stats Section */
        .stats-section {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            padding: 0 20px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            text-align: center;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            font-size: 24px;
            color: white;
        }

        .stat-value {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 12px;
            color: #666;
        }

        .stat-success { background: #4CAF50; }
        .stat-warning { background: #FF9800; }
        .stat-danger { background: #D32F2F; }

        /* Menu Section */
        .menu-section {
            padding: 0 20px;
        }

        .menu-item {
            background: white;
            border-radius: 16px;
            margin-bottom: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .menu-link {
            display: flex;
            align-items: center;
            padding: 20px;
            text-decoration: none;
            color: inherit;
            transition: all 0.3s ease;
        }

        .menu-link:hover {
            background: #f8fbff;
        }

        .menu-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 16px;
            font-size: 24px;
            color: white;
        }

        .menu-content {
            flex: 1;
        }

        .menu-title {
            font-size: 16px;
            font-weight: bold;
            color: #0D47A1;
            margin-bottom: 4px;
        }

        .menu-subtitle {
            font-size: 14px;
            color: #666;
        }

        .menu-arrow {
            width: 30px;
            height: 30px;
            background: rgba(25, 118, 210, 0.1);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1976D2;
        }

        /* Logout Button */
        .logout-section {
            padding: 20px;
            margin-top: 24px;
        }

        .logout-btn {
            width: 100%;
            background: linear-gradient(135deg, #D32F2F, #B71C1C);
            border: none;
            padding: 16px;
            border-radius: 16px;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(211, 47, 47, 0.3);
        }

        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(211, 47, 47, 0.4);
        }

        /* Loading State */
        .loading {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 200px;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #1976D2;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .stats-section {
                grid-template-columns: 1fr;
                gap: 8px;
            }
            
            .app-bar-content {
                flex-direction: row;
                gap: 16px;
            }
            
            .profile-header {
                margin: 16px;
                padding: 20px;
            }
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

        .fade-in {
            animation: fadeInUp 0.6s ease-out both;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- App Bar -->
        <div class="custom-app-bar">
            <div class="app-bar-content">
                <div class="app-bar-left">
                    <a href="/fishmarket" class="back-button">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div class="logo-section">
                        <div class="logo-icon">
                            <i class="fas fa-fish"></i>
                        </div>
                        <h1 class="app-title">Profil Saya</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Loading State -->
            <div id="loadingState" class="loading">
                <div class="spinner"></div>
            </div>

            <!-- Profile Content -->
            <div id="profileContent" style="display: none;">
                <!-- Profile Header -->
                <div class="profile-header fade-in">
                    <div class="profile-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="profile-name" id="profileName">-</div>
                    <div class="profile-email" id="profileEmail">-</div>
                    <div class="profile-phone" id="profilePhone" style="display: none;">-</div>
                    <button class="edit-profile-btn" onclick="showEditDialog()">
                        <i class="fas fa-edit"></i> Edit Profil
                    </button>
                </div>

                <!-- Statistics -->
                <div class="stats-section fade-in">
                    <div class="stat-card">
                        <div class="stat-icon stat-success">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                        <div class="stat-value" id="totalOrders">0</div>
                        <div class="stat-label">Total Pesanan</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon stat-warning">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-value" id="pendingOrders">0</div>
                        <div class="stat-label">Menunggu</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon stat-danger">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="stat-value" id="cancelledOrders">0</div>
                        <div class="stat-label">Dibatalkan</div>
                    </div>
                </div>

                <!-- Menu Items -->
                <div class="menu-section fade-in">
                    <div class="menu-item">
                        <a href="#" class="menu-link" onclick="showOrderHistory()">
                            <div class="menu-icon" style="background: #1976D2;">
                                <i class="fas fa-receipt"></i>
                            </div>
                            <div class="menu-content">
                                <div class="menu-title">Riwayat Pesanan</div>
                                <div class="menu-subtitle">Lihat semua pesanan Anda</div>
                            </div>
                            <div class="menu-arrow">
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </a>
                    </div>

                    <div class="menu-item">
                        <a href="/addresses" class="menu-link">
                            <div class="menu-icon" style="background: #4CAF50;">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="menu-content">
                                <div class="menu-title">Kelola Alamat</div>
                                <div class="menu-subtitle">Atur alamat pengiriman</div>
                            </div>
                            <div class="menu-arrow">
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </a>
                    </div>

                    <div class="menu-item">
                        <a href="#" class="menu-link" onclick="showNotificationSettings()">
                            <div class="menu-icon" style="background: #FF5722;">
                                <i class="fas fa-bell"></i>
                            </div>
                            <div class="menu-content">
                                <div class="menu-title">Pengaturan Notifikasi</div>
                                <div class="menu-subtitle">Atur notifikasi aplikasi</div>
                            </div>
                            <div class="menu-arrow">
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </a>
                    </div>

                    <div class="menu-item">
                        <a href="#" class="menu-link" onclick="showSecuritySettings()">
                            <div class="menu-icon" style="background: #795548;">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div class="menu-content">
                                <div class="menu-title">Keamanan</div>
                                <div class="menu-subtitle">Ubah password dan keamanan</div>
                            </div>
                            <div class="menu-arrow">
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </a>
                    </div>

                    <div class="menu-item">
                        <a href="#" class="menu-link" onclick="showHelpCenter()">
                            <div class="menu-icon" style="background: #FF9800;">
                                <i class="fas fa-question-circle"></i>
                            </div>
                            <div class="menu-content">
                                <div class="menu-title">Pusat Bantuan</div>
                                <div class="menu-subtitle">FAQ dan panduan aplikasi</div>
                            </div>
                            <div class="menu-arrow">
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </a>
                    </div>

                    <div class="menu-item">
                        <a href="#" class="menu-link" onclick="showAboutApp()">
                            <div class="menu-icon" style="background: #9C27B0;">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div class="menu-content">
                                <div class="menu-title">Tentang Aplikasi</div>
                                <div class="menu-subtitle">Informasi aplikasi IwakMart</div>
                            </div>
                            <div class="menu-arrow">
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Logout Button -->
                <div class="logout-section">
                    <button class="logout-btn" onclick="confirmLogout()">
                        <i class="fas fa-sign-out-alt"></i> Keluar dari Akun
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let user = null;
        let orderStats = { total: 0, pending: 0, cancelled: 0 };

        // Load profile data on page load
        document.addEventListener('DOMContentLoaded', function() {
            fetchProfile();
            fetchOrderStats();
        });

        // Fetch user profile
        async function fetchProfile() {
            try {
                // First check auth status
                const authResponse = await fetch('/auth-test');
                const authData = await authResponse.json();
                
                if (!authData.authenticated) {
                    window.location.href = '/login';
                    return;
                }

                user = authData.user;
                displayProfile();
                
            } catch (error) {
                console.error('Error fetching profile:', error);
                showError('Gagal memuat profil');
            }
        }

        // Display profile data
        function displayProfile() {
            if (!user) return;

            document.getElementById('profileName').textContent = user.name || '-';
            document.getElementById('profileEmail').textContent = user.email || '-';
            
            if (user.phone) {
                document.getElementById('profilePhone').textContent = user.phone;
                document.getElementById('profilePhone').style.display = 'block';
            }

            // Hide loading and show content
            document.getElementById('loadingState').style.display = 'none';
            document.getElementById('profileContent').style.display = 'block';
        }

        // Fetch order statistics (mock data for now)
        async function fetchOrderStats() {
            try {
                // Mock stats - in real app, fetch from API
                orderStats = {
                    total: Math.floor(Math.random() * 20),
                    pending: Math.floor(Math.random() * 5),
                    cancelled: Math.floor(Math.random() * 3)
                };

                document.getElementById('totalOrders').textContent = orderStats.total;
                document.getElementById('pendingOrders').textContent = orderStats.pending;
                document.getElementById('cancelledOrders').textContent = orderStats.cancelled;
            } catch (error) {
                console.error('Error fetching order stats:', error);
            }
        }

        // Show edit profile dialog
        function showEditDialog() {
            const modal = document.createElement('div');
            modal.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.5);
                backdrop-filter: blur(8px);
                z-index: 10000;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
            `;

            modal.innerHTML = `
                <div style="
                    background: white;
                    border-radius: 20px;
                    padding: 30px;
                    max-width: 400px;
                    width: 100%;
                    box-shadow: 0 20px 40px rgba(0,0,0,0.3);
                ">
                    <h3 style="
                        color: #0D47A1;
                        margin-bottom: 20px;
                        display: flex;
                        align-items: center;
                        gap: 8px;
                    ">
                        <i class="fas fa-edit" style="color: #1976D2;"></i>
                        Edit Profil
                    </h3>
                    <div style="margin-bottom: 16px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 500;">Nama</label>
                        <input type="text" id="editName" value="${user.name || ''}" style="
                            width: 100%;
                            padding: 12px;
                            border: 2px solid #E3F2FD;
                            border-radius: 12px;
                            font-size: 14px;
                        ">
                    </div>
                    <div style="margin-bottom: 16px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 500;">Email</label>
                        <input type="email" id="editEmail" value="${user.email || ''}" disabled style="
                            width: 100%;
                            padding: 12px;
                            border: 2px solid #E3F2FD;
                            border-radius: 12px;
                            font-size: 14px;
                            background: #f5f5f5;
                            color: #666;
                        ">
                    </div>
                    <div style="margin-bottom: 24px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 500;">No. Telepon</label>
                        <input type="text" id="editPhone" value="${user.phone || ''}" style="
                            width: 100%;
                            padding: 12px;
                            border: 2px solid #E3F2FD;
                            border-radius: 12px;
                            font-size: 14px;
                        ">
                    </div>
                    <div style="display: flex; gap: 12px;">
                        <button onclick="this.parentElement.parentElement.parentElement.remove()" style="
                            flex: 1;
                            padding: 12px;
                            border: 2px solid #E0E0E0;
                            background: white;
                            color: #666;
                            border-radius: 12px;
                            cursor: pointer;
                            font-weight: 600;
                        ">Batal</button>
                        <button onclick="updateProfile()" style="
                            flex: 1;
                            padding: 12px;
                            border: none;
                            background: linear-gradient(135deg, #1976D2, #0D47A1);
                            color: white;
                            border-radius: 12px;
                            cursor: pointer;
                            font-weight: 600;
                        ">Simpan</button>
                    </div>
                </div>
            `;

            document.body.appendChild(modal);
        }

        // Update profile
        async function updateProfile() {
            const name = document.getElementById('editName').value;
            const phone = document.getElementById('editPhone').value;

            try {
                // Mock update - in real app, send to API
                user.name = name;
                user.phone = phone;
                
                displayProfile();
                document.querySelector('[style*="position: fixed"]').remove();
                showSuccess('Profil berhasil diperbarui');
            } catch (error) {
                console.error('Error updating profile:', error);
                showError('Gagal memperbarui profil');
            }
        }

        // Menu functions
        function showOrderHistory() {
            alert('Fitur riwayat pesanan akan segera hadir');
        }

        function showNotificationSettings() {
            alert('Fitur pengaturan notifikasi akan segera hadir');
        }

        function showSecuritySettings() {
            alert('Fitur pengaturan keamanan akan segera hadir');
        }

        function showHelpCenter() {
            alert('Fitur pusat bantuan akan segera hadir');
        }

        function showAboutApp() {
            const modal = document.createElement('div');
            modal.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.5);
                backdrop-filter: blur(8px);
                z-index: 10000;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
            `;

            modal.innerHTML = `
                <div style="
                    background: white;
                    border-radius: 20px;
                    padding: 30px;
                    max-width: 500px;
                    width: 100%;
                    box-shadow: 0 20px 40px rgba(0,0,0,0.3);
                    max-height: 80vh;
                    overflow-y: auto;
                ">
                    <h3 style="
                        color: #0D47A1;
                        margin-bottom: 20px;
                        display: flex;
                        align-items: center;
                        gap: 8px;
                    ">
                        <i class="fas fa-info-circle" style="color: #1976D2;"></i>
                        Tentang IwakMart
                    </h3>
                    <div style="text-align: center; margin-bottom: 20px;">
                        <div style="
                            width: 80px;
                            height: 80px;
                            background: linear-gradient(135deg, #1976D2, #0D47A1);
                            border-radius: 20px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            margin: 0 auto 16px;
                        ">
                            <i class="fas fa-fish" style="font-size: 40px; color: white;"></i>
                        </div>
                        <h4 style="color: #0D47A1; margin-bottom: 8px;">IwakMart v1.0.0</h4>
                        <p style="color: #666; font-size: 14px;">Marketplace Ikan Segar Terpercaya</p>
                    </div>
                    <div style="margin-bottom: 20px; line-height: 1.6; color: #333;">
                        <p>IwakMart adalah aplikasi marketplace yang menghubungkan Anda dengan penjual ikan segar terpercaya. Nikmati kemudahan berbelanja ikan segar berkualitas tinggi langsung dari nelayan lokal.</p>
                    </div>
                    <h5 style="color: #1976D2; margin-bottom: 12px;">Fitur Unggulan:</h5>
                    <ul style="color: #666; font-size: 14px; line-height: 1.8; margin-bottom: 20px;">
                        <li>🐟 Ikan segar berkualitas tinggi</li>
                        <li>🚚 Pengiriman cepat dan aman</li>
                        <li>💬 Chat langsung dengan penjual</li>
                        <li>📍 Janji temu dengan petani/nelayan</li>
                        <li>📱 Interface yang user-friendly</li>
                        <li>🔒 Transaksi yang aman</li>
                    </ul>
                    <hr style="margin: 20px 0; border: none; border-top: 1px solid #E3F2FD;">
                    <div style="font-size: 12px; color: #666;">
                        <p><strong>Developer:</strong> Tim IwakMart</p>
                        <p><strong>Rilis:</strong> Desember 2024</p>
                        <p><strong>Build:</strong> 1.0.0+1</p>
                    </div>
                    <div style="margin-top: 20px; text-align: center;">
                        <button onclick="this.parentElement.parentElement.parentElement.remove()" style="
                            padding: 12px 24px;
                            border: none;
                            background: linear-gradient(135deg, #1976D2, #0D47A1);
                            color: white;
                            border-radius: 12px;
                            cursor: pointer;
                            font-weight: 600;
                        ">Tutup</button>
                    </div>
                </div>
            `;

            document.body.appendChild(modal);
        }

        // Logout function
        function confirmLogout() {
            if (confirm('Apakah Anda yakin ingin keluar dari akun?')) {
                performLogout();
            }
        }

        function performLogout() {
            // Create hidden form for logout
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/logout';
            form.style.display = 'none';
            
            // Add CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                const tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = '_token';
                tokenInput.value = csrfToken.getAttribute('content');
                form.appendChild(tokenInput);
            }
            
            // Add to page and submit
            document.body.appendChild(form);
            form.submit();
        }

        // Helper functions
        function showSuccess(message) {
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: #4CAF50;
                color: white;
                padding: 16px 20px;
                border-radius: 12px;
                box-shadow: 0 8px 25px rgba(76, 175, 80, 0.3);
                z-index: 10000;
                display: flex;
                align-items: center;
                gap: 8px;
            `;
            notification.innerHTML = `
                <i class="fas fa-check-circle"></i>
                <span>${message}</span>
            `;
            document.body.appendChild(notification);
            
            setTimeout(() => notification.remove(), 3000);
        }

        function showError(message) {
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: #D32F2F;
                color: white;
                padding: 16px 20px;
                border-radius: 12px;
                box-shadow: 0 8px 25px rgba(211, 47, 47, 0.3);
                z-index: 10000;
                display: flex;
                align-items: center;
                gap: 8px;
            `;
            notification.innerHTML = `
                <i class="fas fa-exclamation-circle"></i>
                <span>${message}</span>
            `;
            document.body.appendChild(notification);
            
            setTimeout(() => notification.remove(), 3000);
        }
    </script>
</body>
</html>
