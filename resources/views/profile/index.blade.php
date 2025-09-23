<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>IwakMart - Profil Saya</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', sans-serif;
        background: linear-gradient(135deg, #F0F8FF 0%, #E3F2FD 50%, #BBDEFB 100%);
        min-height: 100vh;
        line-height: 1.6;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding-bottom: 120px; /* Space for floating footer */
    }

    /* Header */
    .header {
        background: linear-gradient(135deg, #1565C0 0%, #0D47A1 50%, #002171 100%);
        color: white;
        padding: 20px;
        box-shadow: 0 8px 32px rgba(21, 101, 192, 0.3);
        position: relative;
        overflow: hidden;
    }

    .header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
        animation: float 20s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }

    .header-content {
        display: flex;
        align-items: center;
        gap: 16px;
        margin: 0 auto;
        position: relative;
        z-index: 1;
        padding: 0 20px;
    }

    .back-btn {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        padding: 12px;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        backdrop-filter: blur(10px);
    }

    .back-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .back-btn:active {
        transform: translateY(0) scale(0.95);
    }

    .header-icon {
        background: rgba(255, 255, 255, 0.2);
        padding: 12px;
        border-radius: 16px;
        backdrop-filter: blur(10px);
        animation: pulse 3s ease-in-out infinite;
    }

    .header-info h1 {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 2px;
    }

    .header-info p {
        opacity: 0.9;
        font-size: 14px;
    }



    /* Card styling seperti fishmarket */
    .profile-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border: none;
        margin-bottom: 20px;
    }

    .profile-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }

    .profile-card .card-header {
        background: linear-gradient(135deg, #1976D2, #0D47A1);
        color: white;
        border: none;
        padding: 20px;
        border-radius: 16px 16px 0 0 !important;
    }

    .profile-card .card-body {
        padding: 24px;
    }

    /* Avatar styling */
    .avatar-container {
        position: relative;
        display: inline-block;
    }

    .avatar-circle {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: linear-gradient(135deg, #1976D2, #0D47A1);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 4px solid white;
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    .avatar-button {
        position: absolute;
        bottom: 8px;
        right: 8px;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #FF5722;
        border: 2px solid white;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    .avatar-button:hover {
        transform: scale(1.1);
        background: #E64A19;
    }

    /* Menu item styling seperti fishmarket */
    .menu-item {
        display: flex;
        align-items: center;
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 8px;
        background: white;
        border: 1.5px solid rgba(25, 118, 210, 0.1);
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
    }

    .menu-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(25, 118, 210, 0.15);
        border-color: rgba(25, 118, 210, 0.3);
        color: inherit;
        text-decoration: none;
    }

    .menu-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 16px;
        font-size: 20px;
    }

    .menu-content {
        flex: 1;
    }

    .menu-title {
        font-size: 16px;
        font-weight: 600;
        color: #0D47A1;
        margin-bottom: 4px;
    }

    .menu-subtitle {
        font-size: 14px;
        color: #666;
    }

    .menu-arrow {
        color: #1976D2;
        font-size: 16px;
    }

    /* Seller menu highlight */
    .menu-item.seller-active {
        background: linear-gradient(135deg, rgba(255, 152, 0, 0.1), rgba(255, 193, 7, 0.1));
        border-color: #FFA000;
    }

    .menu-item.seller-active .menu-title {
        color: #F57C00;
        font-weight: 700;
    }

    .menu-item.seller-active .menu-arrow {
        color: #F57C00;
    }

    /* Loading animation */
    .fade-in {
        animation: fadeInUp 0.6s ease-out both;
    }

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

    /* Simple profile info styling */
    .profile-info-simple {
        padding: 20px 0;
    }

    .profile-info-simple h4 {
        font-size: 28px;
        font-weight: 700;
        color: #0D47A1;
        margin-bottom: 16px;
    }

    .profile-info-simple .text-muted {
        font-size: 16px;
        color: #666 !important;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .profile-header {
            margin: -20px -15px 20px -15px;
            padding: 20px 0;
        }

        .profile-title {
            font-size: 24px;
        }

        .avatar-circle {
            width: 100px;
            height: 100px;
        }
    }
</style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-content">
            <button class="back-btn" onclick="window.location.href='/fishmarket'">
                <i class="fas fa-arrow-left"></i>
            </button>
            <div class="header-icon">
                <i class="fas fa-user-circle"></i>
            </div>
            <div class="header-info">
                <h1>Profil Saya</h1>
            </div>
        </div>
    </div>

    <div class="container">

        <!-- Content Area -->
        <div class="container" style="margin-top: 0;">
            <div style="padding: 20px;">

            <!-- Loading State -->
            <div id="loadingState" class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3 text-muted">Memuat profil...</p>
            </div>

            <!-- Profile Content -->
            <div id="profileContent" style="display: none;">
                <!-- Profile Header Card -->
                <div class="row mb-4">
            <div class="col-12">
                <div class="profile-card fade-in">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-3 text-center">
                                <div class="avatar-container">
                                    <div class="avatar-circle">
                                        <i class="fas fa-user text-white fa-4x" id="avatarPlaceholder"></i>
                                        <img id="avatarImage" src="" alt="Avatar" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover; display: none;">
                                    </div>
                                    <button class="avatar-button" onclick="changeAvatar()">
                                        <i class="fas fa-camera"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="profile-info-simple">
                                    <h4 class="mb-3 text-primary" id="profileName">-</h4>
                                    <div class="mb-3">
                                        <i class="fas fa-envelope text-muted me-2"></i>
                                        <span id="profileEmail" class="text-muted">-</span>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button class="btn btn-primary me-2" onclick="showEditDialog()">
                                        <i class="fas fa-edit me-1"></i>Edit Profil
                                    </button>
                                    <button class="btn btn-outline-secondary" onclick="showChangePasswordDialog()">
                                        <i class="fas fa-key me-1"></i>Ubah Password
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!-- Order Statistics -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="profile-card fade-in">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-chart-bar me-2"></i>Statistik Pesanan
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="menu-item" style="margin-bottom: 0; cursor: default;">
                                    <div class="menu-icon bg-primary bg-opacity-10">
                                        <i class="fas fa-shopping-bag text-primary"></i>
                                    </div>
                                    <div class="menu-content text-center">
                                        <h4 class="text-primary mb-1" id="totalOrders">0</h4>
                                        <div class="menu-subtitle">Total Pesanan</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="menu-item" style="margin-bottom: 0; cursor: default;">
                                    <div class="menu-icon bg-warning bg-opacity-10">
                                        <i class="fas fa-clock text-warning"></i>
                                    </div>
                                    <div class="menu-content text-center">
                                        <h4 class="text-warning mb-1" id="pendingOrders">0</h4>
                                        <div class="menu-subtitle">Menunggu</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="menu-item" style="margin-bottom: 0; cursor: default;">
                                    <div class="menu-icon bg-danger bg-opacity-10">
                                        <i class="fas fa-times-circle text-danger"></i>
                                    </div>
                                    <div class="menu-content text-center">
                                        <h4 class="text-danger mb-1" id="cancelledOrders">0</h4>
                                        <div class="menu-subtitle">Dibatalkan</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menu Items -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="profile-card fade-in">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-list me-2"></i>Menu Akun
                        </h6>
                    </div>
                    <div class="card-body">
                        <!-- Riwayat Pesanan -->
                        <a href="#" class="menu-item" onclick="showOrderHistory()">
                            <div class="menu-icon bg-primary bg-opacity-10">
                                <i class="fas fa-receipt text-primary"></i>
                            </div>
                            <div class="menu-content">
                                <div class="menu-title">Riwayat Pesanan</div>
                                <div class="menu-subtitle">Lihat semua pesanan Anda</div>
                            </div>
                            <i class="fas fa-chevron-right menu-arrow"></i>
                        </a>

                        <!-- Kelola Alamat -->
                        <a href="/addresses" class="menu-item">
                            <div class="menu-icon bg-success bg-opacity-10">
                                <i class="fas fa-map-marker-alt text-success"></i>
                            </div>
                            <div class="menu-content">
                                <div class="menu-title">Kelola Alamat</div>
                                <div class="menu-subtitle">Atur alamat pengiriman</div>
                            </div>
                            <i class="fas fa-chevron-right menu-arrow"></i>
                        </a>

                        <!-- Seller Dashboard Menu - Show for seller users -->
                        <div id="sellerDashboardMenuItem" style="display: none;">
                            <a href="/seller/dashboard" class="menu-item seller-active">
                                <div class="menu-icon bg-warning bg-opacity-20">
                                    <i class="fas fa-store text-warning seller-badge"></i>
                                </div>
                                <div class="menu-content">
                                    <div class="menu-title" style="color: #F57C00 !important;">Dashboard Penjual</div>
                                    <div class="menu-subtitle">Kelola toko dan produk Anda</div>
                                </div>
                                <i class="fas fa-chevron-right" style="color: #F57C00;"></i>
                            </a>
                        </div>

                        <!-- Seller Dashboard Menu For Buyers - Show for non-seller users -->
                        <div id="sellerDashboardMenuItemForBuyers" style="display: none;">
                            <a href="#" class="menu-item" onclick="handleBuyerDashboardAccess()">
                                <div class="menu-icon bg-warning bg-opacity-10">
                                    <i class="fas fa-store text-warning"></i>
                                </div>
                                <div class="menu-content">
                                    <div class="menu-title">Dashboard Penjual</div>
                                    <div class="menu-subtitle">Kelola toko dan produk Anda</div>
                                </div>
                                <i class="fas fa-chevron-right menu-arrow"></i>
                            </a>
                        </div>

                        <!-- Mode Penjual Menu - Hidden by default -->
                        <div id="sellerMenuItem" style="display: none;">
                            <a href="#" class="menu-item" onclick="handleSellerAccess()">
                                <div class="menu-icon bg-warning bg-opacity-10">
                                    <i class="fas fa-store text-warning"></i>
                                </div>
                                <div class="menu-content">
                                    <div class="menu-title">Mode Penjual</div>
                                    <div class="menu-subtitle" id="sellerSubtitle">Mulai berjualan di IwakMart</div>
                                </div>
                                <i class="fas fa-chevron-right menu-arrow"></i>
                            </a>
                        </div>

                        <!-- Pengaturan Notifikasi -->
                        <a href="#" class="menu-item" onclick="showNotificationSettings()">
                            <div class="menu-icon bg-primary bg-opacity-10">
                                <i class="fas fa-bell text-primary"></i>
                            </div>
                            <div class="menu-content">
                                <div class="menu-title">Pengaturan Notifikasi</div>
                                <div class="menu-subtitle">Atur notifikasi aplikasi</div>
                            </div>
                            <i class="fas fa-chevron-right menu-arrow"></i>
                        </a>

                        <!-- Keamanan -->
                        <a href="#" class="menu-item" onclick="showSecuritySettings()">
                            <div class="menu-icon bg-info bg-opacity-10">
                                <i class="fas fa-shield-alt text-info"></i>
                            </div>
                            <div class="menu-content">
                                <div class="menu-title">Keamanan</div>
                                <div class="menu-subtitle">Ubah password dan keamanan</div>
                            </div>
                            <i class="fas fa-chevron-right menu-arrow"></i>
                        </a>

                        <!-- Pusat Bantuan -->
                        <a href="#" class="menu-item" onclick="showHelpCenter()">
                            <div class="menu-icon bg-secondary bg-opacity-10">
                                <i class="fas fa-question-circle text-secondary"></i>
                            </div>
                            <div class="menu-content">
                                <div class="menu-title">Pusat Bantuan</div>
                                <div class="menu-subtitle">FAQ dan panduan aplikasi</div>
                            </div>
                            <i class="fas fa-chevron-right menu-arrow"></i>
                        </a>

                        <!-- Tentang Aplikasi -->
                        <a href="#" class="menu-item" onclick="showAboutApp()">
                            <div class="menu-icon bg-dark bg-opacity-10">
                                <i class="fas fa-info-circle text-dark"></i>
                            </div>
                            <div class="menu-content">
                                <div class="menu-title">Tentang Aplikasi</div>
                                <div class="menu-subtitle">Informasi aplikasi IwakMart</div>
                            </div>
                            <i class="fas fa-chevron-right menu-arrow"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Logout Button -->
        <div class="row">
            <div class="col-12">
                <div class="profile-card fade-in" style="border: 1.5px solid rgba(244, 67, 54, 0.2);">
                    <div class="card-body text-center">
                        <button class="btn btn-danger px-4 py-2 rounded-pill" onclick="confirmLogout()" style="box-shadow: 0 4px 12px rgba(244, 67, 54, 0.3); font-weight: 600;">
                            <i class="fas fa-sign-out-alt me-2"></i>Keluar dari Akun
                        </button>
                    </div>
                </div>
            </div>
        </div>
                    </div>
                </div>
            </div>
            </div> <!-- Penutup padding wrapper -->
        </div> <!-- Penutup container -->
</body>
</html>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
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

    // Utility functions for safe DOM access
    function safeGetElement(id) {
        const element = document.getElementById(id);
        if (!element) {
            console.warn(`Element with ID '${id}' not found`);
        }
        return element;
    }

    function safeSetText(id, text) {
        const element = safeGetElement(id);
        if (element) {
            element.textContent = text;
        }
        return element;
    }

    function safeSetDisplay(id, displayValue) {
        const element = safeGetElement(id);
        if (element) {
            element.style.display = displayValue;
        }
        return element;
    }

    // Display profile data
    function displayProfile() {
        if (!user) return;

        // Hanya tampilkan nama dan email
        safeSetText('profileName', user.name || '-');
        safeSetText('profileEmail', user.email || '-');

        // Always show Dashboard Penjual menu - checking will be done on click
        safeSetDisplay('sellerDashboardMenuItemForBuyers', 'block');

        // Hide loading and show content
        safeSetDisplay('loadingState', 'none');
        safeSetDisplay('profileContent', 'block');
    }



    // Check if user has seller permissions
    function hasSellerPermissions(user) {
        return user.role === 'seller' ||
               user.role === 'penjual_biasa' ||
               user.role === 'penjual' ||
               user.is_seller === true ||
               (user.roles && user.roles.includes('seller')) ||
               (user.roles && user.roles.includes('penjual_biasa')) ||
               (user.permissions && user.permissions.includes('sell'));
    }

    // Fetch order statistics
    async function fetchOrderStats() {
        try {
            // Gunakan endpoint statistik yang baru dibuat
            const response = await authenticatedFetch('/api/orders/statistics');

            if (response && response.ok) {
                const data = await response.json();
                if (data.success) {
                    orderStats = data.data;

                    // Update tampilan statistik
                    safeSetText('totalOrders', orderStats.total_pesanan || 0);
                    safeSetText('pendingOrders', orderStats.menunggu || 0);
                    safeSetText('cancelledOrders', orderStats.dibatalkan || 0);

                    // Bisa ditambahkan statistik lainnya jika diperlukan
                    console.log('Order Statistics:', orderStats);
                } else {
                    throw new Error(data.message || 'Failed to fetch order statistics');
                }
            } else {
                throw new Error('Network response was not ok');
            }
        } catch (error) {
            console.error('Error fetching order stats:', error);

            // Fallback ke data default jika gagal
            orderStats = {
                total_pesanan: 0,
                menunggu: 0,
                dibatalkan: 0
            };

            safeSetText('totalOrders', '0');
            safeSetText('pendingOrders', '0');
            safeSetText('cancelledOrders', '0');
        }
    }



    // Handle dashboard penjual access - check role only when clicked
    function handleBuyerDashboardAccess() {
        if (!user) {
            showError('Silakan login terlebih dahulu');
            return;
        }

        // Check if user has seller role when clicking
        if (user.role === 'penjual_biasa' || user.role === 'seller' || hasSellerPermissions(user)) {
            // User is already a seller, redirect to dashboard immediately
            console.log('User is seller, redirecting to dashboard');
            window.location.href = '/seller/dashboard';
            return;
        }

        // User is not a seller, show registration modal
        console.log('User is not seller, showing registration modal');
        showSellerRegistrationModal();
    }

    // Show seller registration modal
    function showSellerRegistrationModal() {
        // Create beautiful registration modal
        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.innerHTML = `
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-store me-2"></i>Daftar Sebagai Penjual
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center mb-4">
                            <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <i class="fas fa-store text-warning fa-2x"></i>
                            </div>
                            <h4 class="mt-3 text-warning">Mulai Berjualan di IwakMart!</h4>
                            <p class="text-muted">Bergabunglah dengan ribuan penjual lainnya dan raih kesuksesan dalam bisnis ikan Anda.</p>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card border-0 bg-light h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-chart-line text-success fa-2x mb-3"></i>
                                        <h6>Tingkatkan Penjualan</h6>
                                        <small class="text-muted">Jangkauan lebih luas untuk produk ikan Anda</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 bg-light h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-users text-info fa-2x mb-3"></i>
                                        <h6>Komunitas Besar</h6>
                                        <small class="text-muted">Bergabung dengan ribuan pembeli aktif</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card border-0 bg-light h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-tools text-primary fa-2x mb-3"></i>
                                        <h6>Tools Lengkap</h6>
                                        <small class="text-muted">Dashboard dan tools untuk mengelola toko</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 bg-light h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-shield-alt text-success fa-2x mb-3"></i>
                                        <h6>Aman & Terpercaya</h6>
                                        <small class="text-muted">Platform yang aman untuk bertransaksi</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Gratis!</strong> Tidak ada biaya pendaftaran. Anda dapat langsung mulai berjualan setelah mendaftar.
                        </div>

                        <div class="text-muted small">
                            <p><strong>Dengan mendaftar sebagai penjual, Anda menyetujui:</strong></p>
                            <ul class="mb-0">
                                <li>Menjual produk ikan yang berkualitas dan segar</li>
                                <li>Memberikan informasi produk yang akurat</li>
                                <li>Melayani pembeli dengan baik dan responsif</li>
                                <li>Mengikuti kebijakan dan aturan platform IwakMart</li>
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Batal
                        </button>
                        <button type="button" class="btn btn-warning" onclick="registerAsSeller()" id="registerSellerBtn">
                            <i class="fas fa-store me-1"></i>Daftar Sebagai Penjual
                        </button>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(modal);
        const bootstrapModal = new bootstrap.Modal(modal);
        bootstrapModal.show();

        // Remove modal from DOM when hidden
        modal.addEventListener('hidden.bs.modal', function () {
            modal.remove();
        });
    }

    // Handle seller registration
    function registerAsSeller() {
        const registerBtn = document.getElementById('registerSellerBtn');
        const originalText = registerBtn.innerHTML;

        // Show loading state
        registerBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Mendaftar...';
        registerBtn.disabled = true;

        // Make API call to register as seller using authenticatedFetch
        authenticatedFetch('/api/register-seller', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.querySelector('.modal'));
                modal.hide();

                // Update user object with new role
                if (user) {
                    user.role = 'penjual_biasa';

                    // Update role badge immediately
                    const roleElement = document.getElementById('profileRole');
                    if (roleElement) {
                        roleElement.textContent = 'Penjual';
                        roleElement.className = 'badge bg-warning';
                    }
                }

                // Show success message and redirect to seller dashboard
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Anda berhasil terdaftar sebagai penjual. Mengarahkan ke dashboard penjual...',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    // Redirect to seller dashboard
                    window.location.href = '/seller/dashboard';
                });
            } else {
                throw new Error(data.message || 'Gagal mendaftar sebagai penjual');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: error.message || 'Terjadi kesalahan saat mendaftar sebagai penjual'
            });
        })
        .finally(() => {
            // Reset button state
            registerBtn.innerHTML = originalText;
            registerBtn.disabled = false;
        });
    }

    // Show edit profile dialog
    function showEditDialog() {
        // Create modal for edit profile
        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.innerHTML = `
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Profil</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control" id="editName" value="${user.name || ''}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" id="editEmail" value="${user.email || ''}" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" class="form-control" id="editPhone" value="${user.phone || ''}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary" onclick="updateProfile()">Simpan</button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
        const bootstrapModal = new bootstrap.Modal(modal);
        bootstrapModal.show();
    }

    // Update profile
    async function updateProfile() {
        const name = safeGetElement('editName')?.value || '';
        const phone = safeGetElement('editPhone')?.value || '';

        try {
            // Mock update - in real app, send to API
            user.name = name;
            user.phone = phone;

            displayProfile();

            // Close modal
            const modal = document.querySelector('.modal');
            if (modal) {
                const bootstrapModal = bootstrap.Modal.getInstance(modal);
                bootstrapModal.hide();
                modal.remove();
            }

            showSuccess('Profil berhasil diperbarui');
        } catch (error) {
            console.error('Error updating profile:', error);
            showError('Gagal memperbarui profil');
        }
    }

    // Menu functions
    function showOrderHistory() {
        // Redirect ke halaman riwayat pesanan yang sudah ada
        window.location.href = '/orders';
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
        alert('IwakMart v1.0.0 - Marketplace Ikan Segar Terpercaya');
    }

    // Logout function
    function confirmLogout() {
        if (confirm('Apakah Anda yakin ingin keluar dari akun?')) {
            // Use the logout route defined in web.php
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
    }

    // Helper functions
    function showSuccess(message) {
        // Create a simple toast notification
        const toast = document.createElement('div');
        toast.className = 'toast align-items-center text-white bg-success border-0 position-fixed';
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999;';
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-check-circle me-2"></i>${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        document.body.appendChild(toast);

        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();

        setTimeout(() => toast.remove(), 5000);
    }

    function showError(message) {
        // Create a simple toast notification
        const toast = document.createElement('div');
        toast.className = 'toast align-items-center text-white bg-danger border-0 position-fixed';
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999;';
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-exclamation-circle me-2"></i>${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        document.body.appendChild(toast);

        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();

        setTimeout(() => toast.remove(), 5000);
    }

    // Change avatar function
    function changeAvatar() {
        // Create file input
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = 'image/*';

        input.onchange = function(e) {
            const file = e.target.files[0];
            if (!file) return;

            // Validate file size (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                showError('Ukuran file maksimal 2MB');
                return;
            }

            // Validate file type
            if (!file.type.startsWith('image/')) {
                showError('File harus berupa gambar');
                return;
            }

            // Create FormData
            const formData = new FormData();
            formData.append('avatar', file);

            // Upload avatar
            authenticatedFetch('/api/user/avatar', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response || !response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showSuccess('Avatar berhasil diperbarui');
                    fetchProfile(); // Reload to show new avatar
                } else {
                    throw new Error(data.message || 'Gagal mengupload avatar');
                }
            })
            .catch(error => {
                console.error('Error uploading avatar:', error);
                showError('Gagal mengupload avatar: ' + error.message);
            });
        };

        input.click();
    }

    // Show change password dialog
    function showChangePasswordDialog() {
        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.innerHTML = `
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-key text-primary me-2"></i>Ubah Password
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="changePasswordForm">
                            <div class="mb-3">
                                <label for="currentPassword" class="form-label">Password Saat Ini</label>
                                <input type="password" class="form-control" id="currentPassword" required>
                            </div>
                            <div class="mb-3">
                                <label for="newPassword" class="form-label">Password Baru</label>
                                <input type="password" class="form-control" id="newPassword" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" class="form-control" id="confirmPassword" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary" onclick="changePassword()">
                            <i class="fas fa-save me-1"></i>Ubah Password
                        </button>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(modal);
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();

        modal.addEventListener('hidden.bs.modal', () => modal.remove());
    }

    // Change password function
    function changePassword() {
        const currentPassword = safeGetElement('currentPassword')?.value || '';
        const newPassword = safeGetElement('newPassword')?.value || '';
        const confirmPassword = safeGetElement('confirmPassword')?.value || '';

        if (!currentPassword || !newPassword || !confirmPassword) {
            showError('Semua field password wajib diisi');
            return;
        }

        if (newPassword !== confirmPassword) {
            showError('Konfirmasi password tidak sesuai');
            return;
        }

        if (newPassword.length < 6) {
            showError('Password baru minimal 6 karakter');
            return;
        }

        authenticatedFetch('/api/user/change-password', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                current_password: currentPassword,
                new_password: newPassword,
                new_password_confirmation: confirmPassword
            })
        })
        .then(response => {
            if (!response || !response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showSuccess('Password berhasil diubah');
                bootstrap.Modal.getInstance(document.querySelector('.modal')).hide();
            } else {
                throw new Error(data.message || 'Gagal mengubah password');
            }
        })
        .catch(error => {
            console.error('Error changing password:', error);
            showError('Gagal mengubah password: ' + error.message);
        });
    }
</script>

<style>
    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
    }

    .list-group-item {
        transition: background-color 0.2s ease, transform 0.2s ease;
    }

    .list-group-item:hover {
        background-color: rgba(13, 110, 253, 0.05) !important;
        transform: translateX(5px);
    }

    .seller-badge {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.7; }
        100% { opacity: 1; }
    }


</script>
</body>
</html>
