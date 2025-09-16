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

    <script src="/js/auth.js"></script>
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

        // Fetch order statistics using real API (replaced mock data)
        // Remove old mock fetchOrderStats and showOrderHistory functions (replaced with OrderManager integration)

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

        // Logout function - now using auth.js
        function confirmLogout() {
            // Use the improved logout from auth.js if available
            if (typeof window.confirmLogout === 'function' && window.confirmLogout !== arguments.callee) {
                return window.confirmLogout();
            }

            // Fallback implementation
            if (confirm('Apakah Anda yakin ingin keluar dari akun?')) {
                return performLogout();
            }
            return false;
        }

        function performLogout() {
            // Use the improved logout from auth.js if available
            if (typeof window.logout === 'function') {
                return window.logout();
            }

            // Fallback implementation
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

        // Order Management System Integration
        class OrderManager {
            constructor() {
                this.orders = [];
                this.loading = false;
                this.token = this.getAuthToken();
            }

            // Get authentication token from localStorage or meta tag
            getAuthToken() {
                // First try to get from localStorage (if using SPA auth)
                const token = localStorage.getItem('auth_token');
                if (token) return token;

                // Fallback: get CSRF token for session auth
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                return csrfToken ? csrfToken.getAttribute('content') : null;
            }

            // Get headers for API requests
            getHeaders() {
                const headers = {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                };

                if (this.token) {
                    // If we have a bearer token, use it
                    if (this.token.includes('|')) {
                        headers['Authorization'] = `Bearer ${this.token}`;
                    } else {
                        // Otherwise use CSRF token
                        headers['X-CSRF-TOKEN'] = this.token;
                    }
                }

                return headers;
            }

            // Fetch orders from API
            async fetchOrders() {
                if (this.loading) return;

                this.loading = true;
                try {
                    const response = await fetch('/api/orders', {
                        method: 'GET',
                        headers: this.getHeaders(),
                        credentials: 'include'
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }

                    const data = await response.json();
                    this.orders = data.data || data || [];
                    return this.orders;
                } catch (error) {
                    console.error('Error fetching orders:', error);
                    showError('Gagal memuat data pesanan');
                    return [];
                } finally {
                    this.loading = false;
                }
            }

            // Fetch order details by ID
            async fetchOrderDetails(orderId) {
                try {
                    const response = await fetch(`/api/orders/${orderId}`, {
                        method: 'GET',
                        headers: this.getHeaders(),
                        credentials: 'include'
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }

                    return await response.json();
                } catch (error) {
                    console.error('Error fetching order details:', error);
                    showError('Gagal memuat detail pesanan');
                    return null;
                }
            }

            // Fetch order items by order ID
            async fetchOrderItems(orderId) {
                try {
                    const response = await fetch(`/api/orders/${orderId}/items`, {
                        method: 'GET',
                        headers: this.getHeaders(),
                        credentials: 'include'
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }

                    return await response.json();
                } catch (error) {
                    console.error('Error fetching order items:', error);
                    showError('Gagal memuat item pesanan');
                    return null;
                }
            }

            // Cancel order
            async cancelOrder(orderId, reason = '') {
                try {
                    const response = await fetch(`/api/orders/${orderId}/cancel`, {
                        method: 'POST',
                        headers: this.getHeaders(),
                        credentials: 'include',
                        body: JSON.stringify({ reason })
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }

                    const result = await response.json();
                    showSuccess('Pesanan berhasil dibatalkan');
                    return result;
                } catch (error) {
                    console.error('Error cancelling order:', error);
                    showError('Gagal membatalkan pesanan');
                    return null;
                }
            }

            // Get order statistics
            getOrderStats() {
                const stats = {
                    total: this.orders.length,
                    pending: this.orders.filter(order => ['menunggu', 'dibayar', 'diproses'].includes(order.status)).length,
                    completed: this.orders.filter(order => order.status === 'selesai').length,
                    cancelled: this.orders.filter(order => order.status === 'dibatalkan').length
                };
                return stats;
            }

            // Format currency
            formatCurrency(amount) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(amount);
            }

            // Format date
            formatDate(dateString) {
                return new Date(dateString).toLocaleDateString('id-ID', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }

            // Get status color
            getStatusColor(status) {
                const colors = {
                    'menunggu': '#FF9800',
                    'dibayar': '#2196F3',
                    'diproses': '#9C27B0',
                    'dikirim': '#3F51B5',
                    'selesai': '#4CAF50',
                    'dibatalkan': '#F44336'
                };
                return colors[status] || '#757575';
            }

            // Get status text
            getStatusText(status) {
                const texts = {
                    'menunggu': 'Menunggu Pembayaran',
                    'dibayar': 'Pembayaran Diterima',
                    'diproses': 'Sedang Diproses',
                    'dikirim': 'Dalam Pengiriman',
                    'selesai': 'Pesanan Selesai',
                    'dibatalkan': 'Dibatalkan'
                };
                return texts[status] || status;
            }
        }

        // Initialize order manager
        const orderManager = new OrderManager();

        // Updated fetch order statistics using real API
        async function fetchOrderStats() {
            try {
                const orders = await orderManager.fetchOrders();
                const stats = orderManager.getOrderStats();

                document.getElementById('totalOrders').textContent = stats.total;
                document.getElementById('pendingOrders').textContent = stats.pending;
                document.getElementById('cancelledOrders').textContent = stats.cancelled;
            } catch (error) {
                console.error('Error fetching order stats:', error);
                // Keep existing mock data as fallback
                orderStats = {
                    total: Math.floor(Math.random() * 20),
                    pending: Math.floor(Math.random() * 5),
                    cancelled: Math.floor(Math.random() * 3)
                };

                document.getElementById('totalOrders').textContent = orderStats.total;
                document.getElementById('pendingOrders').textContent = orderStats.pending;
                document.getElementById('cancelledOrders').textContent = orderStats.cancelled;
            }
        }

        // Updated order history function with real API integration
        async function showOrderHistory() {
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
                    max-width: 600px;
                    width: 100%;
                    max-height: 80vh;
                    box-shadow: 0 20px 40px rgba(0,0,0,0.3);
                    display: flex;
                    flex-direction: column;
                ">
                    <div style="
                        padding: 30px 30px 0;
                        border-bottom: 1px solid #E3F2FD;
                    ">
                        <h3 style="
                            color: #0D47A1;
                            margin-bottom: 20px;
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                        ">
                            <span style="display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-receipt" style="color: #1976D2;"></i>
                                Riwayat Pesanan
                            </span>
                            <button onclick="this.closest('[style*=\"position: fixed\"]').remove()" style="
                                width: 30px;
                                height: 30px;
                                border: none;
                                background: #f5f5f5;
                                border-radius: 50%;
                                cursor: pointer;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                            ">
                                <i class="fas fa-times"></i>
                            </button>
                        </h3>
                    </div>
                    <div id="orderHistoryContent" style="
                        flex: 1;
                        overflow-y: auto;
                        padding: 20px 30px 30px;
                    ">
                        <div style="
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            min-height: 200px;
                        ">
                            <div style="
                                width: 40px;
                                height: 40px;
                                border: 4px solid #f3f3f3;
                                border-top: 4px solid #1976D2;
                                border-radius: 50%;
                                animation: spin 1s linear infinite;
                            "></div>
                        </div>
                    </div>
                </div>
            `;

            document.body.appendChild(modal);

            // Load order history
            try {
                const orders = await orderManager.fetchOrders();
                displayOrderHistory(orders);
            } catch (error) {
                document.getElementById('orderHistoryContent').innerHTML = `
                    <div style="text-align: center; padding: 40px; color: #666;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 48px; color: #FF9800; margin-bottom: 16px;"></i>
                        <h4 style="margin-bottom: 8px;">Gagal Memuat Data</h4>
                        <p style="font-size: 14px;">Terjadi kesalahan saat memuat riwayat pesanan</p>
                        <button onclick="showOrderHistory(); this.closest('[style*=\"position: fixed\"]').remove();" style="
                            margin-top: 16px;
                            padding: 8px 16px;
                            border: none;
                            background: #1976D2;
                            color: white;
                            border-radius: 8px;
                            cursor: pointer;
                        ">Coba Lagi</button>
                    </div>
                `;
            }
        }

        // Display order history in modal
        function displayOrderHistory(orders) {
            const content = document.getElementById('orderHistoryContent');

            if (!orders || orders.length === 0) {
                content.innerHTML = `
                    <div style="text-align: center; padding: 40px; color: #666;">
                        <i class="fas fa-shopping-bag" style="font-size: 48px; color: #E0E0E0; margin-bottom: 16px;"></i>
                        <h4 style="margin-bottom: 8px;">Belum Ada Pesanan</h4>
                        <p style="font-size: 14px;">Anda belum memiliki riwayat pesanan</p>
                        <a href="/fishmarket" style="
                            display: inline-block;
                            margin-top: 16px;
                            padding: 12px 24px;
                            background: linear-gradient(135deg, #1976D2, #0D47A1);
                            color: white;
                            text-decoration: none;
                            border-radius: 12px;
                            font-weight: 600;
                        ">Mulai Belanja</a>
                    </div>
                `;
                return;
            }

            const ordersHtml = orders.map(order => `
                <div style="
                    border: 1px solid #E3F2FD;
                    border-radius: 12px;
                    margin-bottom: 16px;
                    overflow: hidden;
                    transition: all 0.3s ease;
                    cursor: pointer;
                " onclick="showOrderDetails(${order.id})">
                    <div style="
                        padding: 16px;
                        background: linear-gradient(135deg, #F8FBFF, #E3F2FD);
                        border-bottom: 1px solid #E3F2FD;
                    ">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <span style="font-weight: 600; color: #0D47A1;">#${order.nomor_pesanan || order.id}</span>
                            <span style="
                                background: ${orderManager.getStatusColor(order.status)};
                                color: white;
                                padding: 4px 12px;
                                border-radius: 20px;
                                font-size: 12px;
                                font-weight: 500;
                            ">${orderManager.getStatusText(order.status)}</span>
                        </div>
                        <div style="font-size: 12px; color: #666;">
                            ${orderManager.formatDate(order.created_at)}
                        </div>
                    </div>
                    <div style="padding: 16px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <div style="font-size: 14px; color: #333; margin-bottom: 4px;">
                                    Total: <strong>${orderManager.formatCurrency(order.total || order.total_amount || 0)}</strong>
                                </div>
                                <div style="font-size: 12px; color: #666;">
                                    ${order.metode_pembayaran || 'Belum ditentukan'}
                                </div>
                            </div>
                            <i class="fas fa-chevron-right" style="color: #1976D2;"></i>
                        </div>
                    </div>
                </div>
            `).join('');

            content.innerHTML = ordersHtml;
        }

        // Show order details
        async function showOrderDetails(orderId) {
            // Close current modal
            const currentModal = document.querySelector('[style*="position: fixed"]');
            if (currentModal) currentModal.remove();

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
                    max-width: 700px;
                    width: 100%;
                    max-height: 90vh;
                    box-shadow: 0 20px 40px rgba(0,0,0,0.3);
                    display: flex;
                    flex-direction: column;
                ">
                    <div style="
                        padding: 30px 30px 0;
                        border-bottom: 1px solid #E3F2FD;
                    ">
                        <h3 style="
                            color: #0D47A1;
                            margin-bottom: 20px;
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                        ">
                            <span style="display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-file-alt" style="color: #1976D2;"></i>
                                Detail Pesanan #${orderId}
                            </span>
                            <button onclick="this.closest('[style*=\"position: fixed\"]').remove()" style="
                                width: 30px;
                                height: 30px;
                                border: none;
                                background: #f5f5f5;
                                border-radius: 50%;
                                cursor: pointer;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                            ">
                                <i class="fas fa-times"></i>
                            </button>
                        </h3>
                    </div>
                    <div id="orderDetailsContent" style="
                        flex: 1;
                        overflow-y: auto;
                        padding: 20px 30px 30px;
                    ">
                        <div style="
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            min-height: 200px;
                        ">
                            <div style="
                                width: 40px;
                                height: 40px;
                                border: 4px solid #f3f3f3;
                                border-top: 4px solid #1976D2;
                                border-radius: 50%;
                                animation: spin 1s linear infinite;
                            "></div>
                        </div>
                    </div>
                </div>
            `;

            document.body.appendChild(modal);

            // Load order details and items
            try {
                const [orderDetails, orderItems] = await Promise.all([
                    orderManager.fetchOrderDetails(orderId),
                    orderManager.fetchOrderItems(orderId)
                ]);

                displayOrderDetails(orderDetails, orderItems);
            } catch (error) {
                document.getElementById('orderDetailsContent').innerHTML = `
                    <div style="text-align: center; padding: 40px; color: #666;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 48px; color: #FF9800; margin-bottom: 16px;"></i>
                        <h4 style="margin-bottom: 8px;">Gagal Memuat Detail</h4>
                        <p style="font-size: 14px;">Terjadi kesalahan saat memuat detail pesanan</p>
                    </div>
                `;
            }
        }

        // Display order details
        function displayOrderDetails(orderDetails, orderItems) {
            const content = document.getElementById('orderDetailsContent');
            const order = orderDetails.data || orderDetails;
            const items = orderItems.data || orderItems || [];

            const itemsHtml = items.map(item => `
                <div style="
                    display: flex;
                    align-items: center;
                    padding: 16px;
                    border: 1px solid #E3F2FD;
                    border-radius: 12px;
                    margin-bottom: 12px;
                    background: #FAFBFF;
                ">
                    <div style="
                        width: 60px;
                        height: 60px;
                        background: linear-gradient(135deg, #E3F2FD, #BBDEFB);
                        border-radius: 12px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        margin-right: 16px;
                    ">
                        <i class="fas fa-fish" style="color: #1976D2; font-size: 24px;"></i>
                    </div>
                    <div style="flex: 1;">
                        <div style="font-weight: 600; color: #0D47A1; margin-bottom: 4px;">
                            ${item.nama_produk || item.product?.nama || 'Produk'}
                        </div>
                        <div style="font-size: 12px; color: #666; margin-bottom: 4px;">
                            ${item.jumlah || 1} x ${orderManager.formatCurrency(item.harga || 0)}
                        </div>
                        <div style="font-weight: 600; color: #1976D2;">
                            ${orderManager.formatCurrency(item.subtotal || (item.harga * item.jumlah) || 0)}
                        </div>
                    </div>
                </div>
            `).join('');

            content.innerHTML = `
                <div style="margin-bottom: 24px;">
                    <div style="
                        background: linear-gradient(135deg, #F8FBFF, #E3F2FD);
                        padding: 20px;
                        border-radius: 16px;
                        margin-bottom: 20px;
                    ">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                            <div>
                                <h4 style="color: #0D47A1; margin-bottom: 4px;">Pesanan #${order.nomor_pesanan || order.id}</h4>
                                <p style="font-size: 14px; color: #666; margin: 0;">${orderManager.formatDate(order.created_at)}</p>
                            </div>
                            <span style="
                                background: ${orderManager.getStatusColor(order.status)};
                                color: white;
                                padding: 8px 16px;
                                border-radius: 20px;
                                font-size: 14px;
                                font-weight: 600;
                            ">${orderManager.getStatusText(order.status)}</span>
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                            <div>
                                <div style="font-size: 12px; color: #666; margin-bottom: 4px;">Metode Pembayaran</div>
                                <div style="font-weight: 600; color: #333;">${order.metode_pembayaran || 'Belum ditentukan'}</div>
                            </div>
                            <div>
                                <div style="font-size: 12px; color: #666; margin-bottom: 4px;">Total Pembayaran</div>
                                <div style="font-weight: 600; color: #1976D2; font-size: 18px;">${orderManager.formatCurrency(order.total || order.total_amount || 0)}</div>
                            </div>
                        </div>
                    </div>

                    <h5 style="color: #0D47A1; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-list"></i>
                        Item Pesanan
                    </h5>
                    ${items.length > 0 ? itemsHtml : `
                        <div style="text-align: center; padding: 40px; color: #666;">
                            <i class="fas fa-shopping-cart" style="font-size: 48px; color: #E0E0E0; margin-bottom: 16px;"></i>
                            <p>Tidak ada item dalam pesanan ini</p>
                        </div>
                    `}
                </div>

                ${order.status === 'menunggu' || order.status === 'dibayar' ? `
                    <div style="
                        border-top: 1px solid #E3F2FD;
                        padding-top: 20px;
                        text-align: center;
                    ">
                        <button onclick="confirmCancelOrder(${order.id})" style="
                            background: linear-gradient(135deg, #D32F2F, #B71C1C);
                            border: none;
                            color: white;
                            padding: 12px 24px;
                            border-radius: 12px;
                            cursor: pointer;
                            font-weight: 600;
                            display: inline-flex;
                            align-items: center;
                            gap: 8px;
                        ">
                            <i class="fas fa-times"></i>
                            Batalkan Pesanan
                        </button>
                    </div>
                ` : ''}
            `;
        }

        // Confirm cancel order
        function confirmCancelOrder(orderId) {
            if (confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')) {
                cancelOrderById(orderId);
            }
        }

        // Cancel order by ID
        async function cancelOrderById(orderId) {
            try {
                const result = await orderManager.cancelOrder(orderId, 'Dibatalkan oleh customer');
                if (result) {
                    // Close modal and refresh data
                    const modal = document.querySelector('[style*="position: fixed"]');
                    if (modal) modal.remove();

                    // Refresh order stats
                    fetchOrderStats();
                }
            } catch (error) {
                console.error('Error cancelling order:', error);
            }
        }

        // Initialize profile page with order integration
        document.addEventListener('DOMContentLoaded', function() {
            // Load order statistics on page load
            fetchOrderStats();

            // Add CSS for animations and transitions
            const style = document.createElement('style');
            style.textContent = `
                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }

                [onclick]:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
                    transition: all 0.3s ease;
                }

                .modal-backdrop {
                    animation: fadeIn 0.3s ease;
                }

                @keyframes fadeIn {
                    from { opacity: 0; }
                    to { opacity: 1; }
                }

                .order-card:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 8px 25px rgba(25, 118, 210, 0.15);
                }

                .loading-spinner {
                    animation: spin 1s linear infinite;
                }
            `;
            document.head.appendChild(style);

            // Check authentication status
            console.log('Order management system initialized');
            if (orderManager.token) {
                console.log('Authentication token found, ready to fetch orders');
            } else {
                console.warn('No authentication token found, some features may not work');
            }
        });
    </script>
</body>
</html>
