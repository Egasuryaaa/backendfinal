<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>IwakMart - Fish Market</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
            background: #F0F8FF; /* Alice Blue background */
            min-height: 100vh;
            line-height: 1.6;
        }

        .container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Custom App Bar with Gradient */
        .custom-app-bar {
            background: linear-gradient(135deg, #1565C0, #0D47A1, #002171);
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            position: relative;
            overflow: hidden;
        }

        .custom-app-bar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(255,255,255,0.1) 25%, transparent 25%, transparent 75%, rgba(255,255,255,0.1) 75%);
            background-size: 30px 30px;
            animation: movePattern 20s linear infinite;
        }

        @keyframes movePattern {
            0% { transform: translateX(0); }
            100% { transform: translateX(60px); }
        }

        .app-bar-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 2;
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

        .app-bar-right {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .cart-button {
            position: relative;
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

        .cart-button:hover {
            background: rgba(255,255,255,0.3);
            transform: scale(1.1);
        }

        .cart-button:active {
            transform: scale(0.95);
        }

        .cart-badge {
            position: absolute;
            top: -6px;
            right: -6px;
            background: #FF5722;
            color: white;
            font-size: 10px;
            font-weight: 600;
            min-width: 18px;
            height: 18px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.3);
            border: 2px solid white;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        .cart-badge.hidden {
            display: none;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .user-avatar:hover {
            background: rgba(255,255,255,0.3);
            transform: scale(1.1);
        }

        .user-name {
            font-size: 14px;
            font-weight: 500;
            color: white;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
            max-width: 120px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 0;
        }

        /* Welcome Section */
        .welcome-section {
            margin: 20px;
            padding: 24px;
            background: linear-gradient(135deg, #2196F3, #1976D2, #0D47A1);
            border-radius: 24px;
            box-shadow: 0 10px 20px rgba(25, 118, 210, 0.4);
            position: relative;
            overflow: hidden;
            animation: fadeInUp 1s ease-out;
        }

        .welcome-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 100%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .welcome-content {
            display: flex;
            align-items: center;
            position: relative;
            z-index: 2;
        }

        .welcome-text {
            flex: 1;
        }

        .welcome-greeting {
            color: rgba(255,255,255,0.9);
            font-size: 16px;
            font-weight: 400;
            margin-bottom: 4px;
        }

        .welcome-title {
            color: white;
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 8px;
        }

                .welcome-subtitle {
            color: #666;
            font-size: 18px;
            line-height: 1.5;
        }

        .login-notice {
            background: linear-gradient(135deg, #E3F2FD 0%, #BBDEFB 100%);
            color: #1976D2;
            padding: 12px 16px;
            border-radius: 8px;
            margin-top: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            border: 1px solid #90CAF9;
        }

        .welcome-icon {
            padding: 16px;
            background: rgba(255,255,255,0.2);
            border-radius: 20px;
            color: white;
            font-size: 40px;
        }

        /* Image Slider */
        .image-slider {
            height: 200px;
            margin: 20px 0;
            overflow: hidden;
        }

        .slider-container {
            display: flex;
            gap: 16px;
            padding: 0 20px;
            overflow-x: auto;
            scroll-behavior: smooth;
        }

        .slider-container::-webkit-scrollbar {
            height: 6px;
        }

        .slider-container::-webkit-scrollbar-track {
            background: rgba(0,0,0,0.1);
            border-radius: 3px;
        }

        .slider-container::-webkit-scrollbar-thumb {
            background: #1976D2;
            border-radius: 3px;
        }

        .slider-card {
            min-width: 280px;
            height: 200px;
            border-radius: 20px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 15px rgba(0,0,0,0.15);
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .slider-card:hover {
            transform: translateY(-5px);
        }

        .slider-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .slider-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(transparent, rgba(0,0,0,0.7));
        }

        .slider-content {
            position: absolute;
            bottom: 20px;
            left: 20px;
            right: 20px;
            color: white;
        }

        .slider-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .slider-subtitle {
            font-size: 14px;
            color: rgba(255,255,255,0.8);
        }

        /* Category Section */
        .category-section {
            padding: 20px;
            background: white;
            margin-bottom: 20px;
        }

        /* Quick Actions Section */
        .quick-actions-section {
            padding: 20px;
            background: white;
            margin-bottom: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .quick-actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .quick-action-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .quick-action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            text-decoration: none;
            color: white;
        }

        .quick-action-card.collector {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            box-shadow: 0 4px 15px rgba(245, 87, 108, 0.3);
        }

        .quick-action-card.collector:hover {
            box-shadow: 0 8px 25px rgba(245, 87, 108, 0.4);
        }

        .quick-action-card.farm-dashboard {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
        }

        .quick-action-card.farm-dashboard:hover {
            box-shadow: 0 8px 25px rgba(76, 175, 80, 0.4);
        }

        .quick-action-card.collector-dashboard {
            background: linear-gradient(135deg, #673AB7 0%, #5E35B1 100%);
            box-shadow: 0 4px 15px rgba(103, 58, 183, 0.3);
        }

        .quick-action-card.collector-dashboard:hover {
            box-shadow: 0 8px 25px rgba(103, 58, 183, 0.4);
        }

        .quick-action-icon {
            font-size: 2rem;
            margin-bottom: 10px;
            display: block;
        }

        .quick-action-title {
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }

        .quick-action-subtitle {
            font-size: 0.75rem;
            opacity: 0.9;
        }

        .section-title {
            font-size: 20px;
            font-weight: 700;
            color: #0D47A1;
            margin-bottom: 16px;
        }

        .category-chips {
            display: flex;
            gap: 12px;
            overflow-x: auto;
            padding-bottom: 8px;
        }

        .category-chips::-webkit-scrollbar {
            height: 4px;
        }

        .category-chips::-webkit-scrollbar-track {
            background: rgba(0,0,0,0.1);
            border-radius: 2px;
        }

        .category-chips::-webkit-scrollbar-thumb {
            background: #1976D2;
            border-radius: 2px;
        }

        .category-chip {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            border-radius: 25px;
            border: 1.5px solid rgba(25, 118, 210, 0.3);
            background: white;
            color: #1976D2;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .category-chip:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(25, 118, 210, 0.2);
        }

        .category-chip.active {
            background: linear-gradient(135deg, #1976D2, #0D47A1);
            color: white;
            border-color: transparent;
            box-shadow: 0 6px 12px rgba(25, 118, 210, 0.4);
        }

        .category-chip i {
            font-size: 18px;
        }

        /* Products Section */
        .products-section {
            margin: 0 20px;
            animation: fadeInUp 1s ease-out 0.5s both;
        }

        .products-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .product-count {
            padding: 6px 12px;
            background: rgba(25, 118, 210, 0.1);
            border: 1px solid rgba(25, 118, 210, 0.3);
            border-radius: 20px;
            font-size: 12px;
            color: #1976D2;
            font-weight: 600;
        }

        /* Product Grid */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 16px;
            padding: 0 16px;
            margin-bottom: 100px;
        }

        .product-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            cursor: pointer;
            animation: fadeInUp 0.6s ease-out both;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.15);
        }

        .product-image {
            position: relative;
            height: 180px;
            overflow: hidden;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-card:hover .product-image img {
            transform: scale(1.1);
        }

        .fish-type-badge {
            position: absolute;
            top: 8px;
            left: 8px;
            padding: 4px 8px;
            background: #1976D2;
            color: white;
            font-size: 10px;
            font-weight: 600;
            border-radius: 12px;
        }

        .rating-badge {
            position: absolute;
            top: 8px;
            right: 8px;
            padding: 4px 6px;
            background: #FFA000;
            color: white;
            font-size: 10px;
            font-weight: 700;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 2px;
        }

        .rating-badge i {
            font-size: 12px;
        }

        .product-info {
            padding: 12px;
        }

        .product-name {
            font-size: 14px;
            font-weight: 700;
            color: #0D47A1;
            margin-bottom: 6px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-seller {
            display: flex;
            align-items: center;
            gap: 4px;
            margin-bottom: 8px;
        }

        .product-seller i {
            font-size: 12px;
            color: #64B5F6;
        }

        .product-seller span {
            font-size: 11px;
            color: #64B5F6;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .product-price {
            padding: 4px 8px;
            background: #E8F5E8;
            color: #2E7D32;
            font-size: 12px;
            font-weight: 700;
            border-radius: 6px;
            display: inline-block;
        }

        .product-stock {
            color: #666;
            font-size: 11px;
            margin-top: 4px;
        }

        /* Loading Grid */
        .loading-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 16px;
            padding: 0 16px;
        }

        .loading-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .loading-image {
            height: 180px;
            background: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .loading-info {
            padding: 12px;
        }

        .loading-bar {
            height: 16px;
            background: #f0f0f0;
            border-radius: 8px;
            margin-bottom: 8px;
        }

        .loading-bar.short {
            width: 60%;
            height: 12px;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-icon {
            width: 80px;
            height: 80px;
            background: #BBDEFB;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: #1976D2;
            font-size: 40px;
        }

        .empty-title {
            font-size: 20px;
            font-weight: 700;
            color: #0D47A1;
            margin-bottom: 8px;
        }

        .empty-subtitle {
            color: #666;
            font-size: 14px;
            line-height: 1.5;
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

        .spinner {
            width: 40px;
            height: 40px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #1976D2;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
                gap: 12px;
            }

            .loading-grid {
                grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
                gap: 12px;
            }

            .welcome-section {
                margin: 16px;
                padding: 20px;
            }

            .welcome-title {
                font-size: 20px;
            }

            .slider-card {
                min-width: 240px;
            }

            .app-title {
                font-size: 20px;
            }
        }

        @media (max-width: 480px) {
            .products-grid {
                grid-template-columns: 1fr 1fr;
                gap: 8px;
                padding: 0 8px;
            }

            .loading-grid {
                grid-template-columns: 1fr 1fr;
                gap: 8px;
                padding: 0 8px;
            }

            .product-card {
                border-radius: 12px;
            }

            .product-image {
                height: 120px;
            }

            .welcome-content {
                flex-direction: column;
                text-align: center;
                gap: 16px;
            }

            .category-section, .products-section {
                margin: 0 16px 24px;
            }
        }

        /* Refresh Indicator */
        .refresh-indicator {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.2);
            z-index: 1000;
        }

        .refresh-indicator.show {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Custom App Bar with Gradient -->
        <div class="custom-app-bar">
            <div class="app-bar-content">
                <div class="app-bar-left">
                    <div class="logo-section">
                        <div class="logo-icon">
                            <i class="fas fa-fish"></i>
                        </div>
                        <h1 class="app-title">IwakMart</h1>
                    </div>
                </div>
                <div class="app-bar-right">
                    <a href="/profile" class="cart-button" id="profileButton" title="Profil Saya">
                        <i class="fas fa-user-circle"></i>
                    </a>
                    {{-- <a href="/locations" class="cart-button" id="locationsButton" title="Lokasi Penjual">
                        <i class="fas fa-map-marker-alt"></i>
                    </a> --}}
                    <a href="/fish-farms" class="cart-button" id="fishFarmButton" title="Tambak & Pengepul">
                        <i class="fas fa-fish"></i>
                    </a>
                    <!-- <a href="/fish-farms/dashboard" class="cart-button" id="farmDashboardButton" title="Dashboard Pemilik Tambak" style="display: none;">
                        <i class="fas fa-tachometer-alt"></i>
                    </a> -->
                    <a href="/cart" class="cart-button" id="cartButton">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-badge hidden" id="cartBadge">0</span>
                    </a>
                    <div class="user-avatar" onclick="showUserMenu()" id="userAvatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="user-name" id="userName" style="color: white; margin-left: 8px; display: none;"></div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Welcome Section -->
                        <!-- Welcome Section -->
            <div class="welcome-section">
                <div class="welcome-card">
                    <div class="welcome-info">
                        <h1 class="welcome-title">Selamat Datang di IwakMart</h1>
                        <p class="welcome-subtitle">Platform terpercaya untuk jual beli ikan segar berkualitas.</p>
                        <div id="loginNotice" class="login-notice" style="display: none;">
                            <i class="fas fa-info-circle"></i>
                            <span>Silakan <a href="/login" style="color: #1976D2; text-decoration: none; font-weight: 600;">login</a> untuk mengakses keranjang, profil, dan fitur lengkap lainnya</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modern Image Slider -->
            <div class="image-slider">
                <div class="slider-container">
                    <div class="slider-card">
                        <img src="https://images.unsplash.com/photo-1544943910-4c1dc44aab44?w=400" alt="Pasar Ikan Segar" onerror="this.src='https://via.placeholder.com/400x200/BBDEFB/1976D2?text=Pasar+Ikan+Segar'">
                        <div class="slider-overlay"></div>
                        <div class="slider-content">
                            <div class="slider-title">Pasar Ikan Segar</div>
                            <div class="slider-subtitle">Dapatkan ikan segar setiap hari</div>
                        </div>
                    </div>
                    <div class="slider-card">
                        <img src="https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=400" alt="Ikan Premium" onerror="this.src='https://via.placeholder.com/400x200/BBDEFB/1976D2?text=Ikan+Premium'">
                        <div class="slider-overlay"></div>
                        <div class="slider-content">
                            <div class="slider-title">Ikan Premium</div>
                            <div class="slider-subtitle">Kualitas terbaik untuk keluarga</div>
                        </div>
                    </div>
                    <div class="slider-card">
                        <img src="https://images.unsplash.com/photo-1615141982883-c7ad0e69fd62?w=400" alt="Seafood Berkualitas" onerror="this.src='https://via.placeholder.com/400x200/BBDEFB/1976D2?text=Seafood+Berkualitas'">
                        <div class="slider-overlay"></div>
                        <div class="slider-content">
                            <div class="slider-title">Seafood Berkualitas</div>
                            <div class="slider-subtitle">Beragam pilihan ikan laut</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Role-based Quick Actions -->
            <div class="quick-actions-section" id="quickActionsSection" style="display: none;">
                <h2 class="section-title">Akses Cepat</h2>
                <div class="quick-actions-grid" id="quickActionsGrid">
                    <!-- Quick actions will be loaded based on user role -->
                </div>
            </div>

            <!-- Category Section -->
            <div class="category-section">
                <h2 class="section-title">Kategori Ikan</h2>
                <div class="category-chips">
                    <div class="category-chip active" onclick="filterProducts(null)">
                        <i class="fas fa-th-large"></i>
                        <span>Semua Ikan</span>
                    </div>
                    <div class="category-chip" onclick="filterProducts(1)">
                        <i class="fas fa-tint"></i>
                        <span>Ikan Tawar</span>
                    </div>
                    <div class="category-chip" onclick="filterProducts(2)">
                        <i class="fas fa-water"></i>
                        <span>Ikan Laut</span>
                    </div>
                </div>
            </div>

            <!-- Products Section -->
            <div class="products-section">
                <div class="products-header">
                    <h2 class="section-title">Produk Terbaru</h2>
                    <div class="product-count" id="productCount">0 Produk</div>
                </div>

                <!-- Loading State -->
                <div class="loading-grid" id="loadingGrid">
                    <!-- Loading cards will be generated by JavaScript -->
                </div>

                <!-- Products Grid -->
                <div class="products-grid" id="productsGrid" style="display: none;">
                    <!-- Products will be loaded here -->
                </div>

                <!-- Empty State -->
                <div class="empty-state" id="emptyState" style="display: none;">
                    <div class="empty-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <div class="empty-title">Tidak Ada Produk</div>
                    <div class="empty-subtitle">Tidak ada produk dalam kategori ini.<br>Coba pilih kategori lain.</div>
                </div>
            </div>
        </div>

        <!-- Refresh Indicator -->
        <div class="refresh-indicator" id="refreshIndicator">
            <div class="spinner"></div>
            <div style="margin-top: 10px; text-align: center; color: #666;">Memuat ulang...</div>
        </div>
    </div>

    <script>
        let products = [];
        let isLoading = true;
        let selectedCategoryId = null;

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            generateLoadingCards();
            checkAuthenticationStatus();
            fetchCategories();
            fetchProducts();
            loadCartCount(); // Load cart count on page load
            loadQuickActions(); // Load role-based quick actions
        });

        // Load role-based quick actions
        async function loadQuickActions() {
            try {
                const token = getToken();
                if (!token) return;

                const response = await fetch('/api/user', {
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    const userData = await response.json();
                    const user = userData.data || userData;
                    displayQuickActions(user);
                }
            } catch (error) {
                console.error('Error loading user data for quick actions:', error);
            }
        }

        function displayQuickActions(user) {
            const quickActionsSection = document.getElementById('quickActionsSection');
            const quickActionsGrid = document.getElementById('quickActionsGrid');

            // Default actions for all users
            let actions = [
                {
                    href: '/fish-farms',
                    icon: 'fas fa-fish',
                    title: 'Tambak Ikan',
                    subtitle: 'Kelola tambak',
                    class: ''
                },
                {
                    href: '/collectors',
                    icon: 'fas fa-truck',
                    title: 'Pengepul',
                    subtitle: 'Kelola usaha',
                    class: 'collector'
                }
            ];

            // Role-specific actions
            if (user.role === 'pemilik_tambak' || user.user_type === 'pemilik_tambak') {
                // Farm owner - direct access to dashboard only
                actions = [
                    {
                        href: '/fish-farms/dashboard',
                        icon: 'fas fa-tachometer-alt',
                        title: 'Dashboard Tambak',
                        subtitle: 'Kelola tambak & appointment',
                        class: 'farm-dashboard'
                    }
                ];
            } else if (user.role === 'farmer' || user.user_type === 'farmer') {
                // Farmer-specific actions
                actions = [
                    {
                        href: '/fish-farms',
                        icon: 'fas fa-swimmer',
                        title: 'Tambak Saya',
                        subtitle: 'Kelola tambak ikan',
                        class: ''
                    },
                    {
                        href: '/fish-farms#collectors',
                        icon: 'fas fa-search',
                        title: 'Cari Pengepul',
                        subtitle: 'Temukan pembeli',
                        class: 'collector'
                    },
                    {
                        href: '/fish-farm-appointments',
                        icon: 'fas fa-calendar-check',
                        title: 'Janji Saya',
                        subtitle: 'Jadwal penjemputan',
                        class: ''
                    },
                    {
                        href: '/products',
                        icon: 'fas fa-shopping-cart',
                        title: 'Beli Produk',
                        subtitle: 'Belanja kebutuhan',
                        class: ''
                    }
                ];
            } else if (user.role === 'pengepul' || user.user_type === 'pengepul') {
                // Collector - direct access to dashboard only
                actions = [
                    {
                        href: '/collectors/',
                        icon: 'fas fa-tachometer-alt',
                        title: 'Dashboard Pengepul',
                        subtitle: 'Kelola bisnis & penjemputan',
                        class: 'collector-dashboard'
                    }
                ];
            }

            // Generate HTML for quick actions
            quickActionsGrid.innerHTML = actions.map(action => `
                <a href="${action.href}" class="quick-action-card ${action.class}">
                    <i class="${action.icon} quick-action-icon"></i>
                    <div class="quick-action-title">${action.title}</div>
                    <div class="quick-action-subtitle">${action.subtitle}</div>
                </a>
            `).join('');

            // Show the quick actions section
            quickActionsSection.style.display = 'block';
        }

        // Generate loading cards
        function generateLoadingCards() {
            const loadingGrid = document.getElementById('loadingGrid');
            loadingGrid.innerHTML = '';

            for (let i = 0; i < 6; i++) {
                const loadingCard = document.createElement('div');
                loadingCard.className = 'loading-card';
                loadingCard.innerHTML = `
                    <div class="loading-image">
                        <div class="spinner"></div>
                    </div>
                    <div class="loading-info">
                        <div class="loading-bar"></div>
                        <div class="loading-bar short"></div>
                    </div>
                `;
                loadingGrid.appendChild(loadingCard);
            }
        }

        // Fetch categories from API
        async function fetchCategories() {
            try {
                const token = getAuthToken ? getAuthToken() : null;

                const headers = {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                };

                if (token) {
                    headers['Authorization'] = `Bearer ${token}`;
                }

                const response = await fetch('/api/categories?parents_only=true&include_product_count=true', {
                    method: 'GET',
                    headers: headers
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.success && data.data) {
                        displayCategories(data.data);
                    }
                } else {
                    console.error('Failed to fetch categories');
                }
            } catch (error) {
                console.error('Error fetching categories:', error);
            }
        }

        // Display categories in filter section
        function displayCategories(categories) {
            // Implement category filter display if needed
            console.log('Categories loaded:', categories);
        }

        // Fetch products from API
        async function fetchProducts() {
            try {
                // Get auth token from auth.js
                const token = getAuthToken ? getAuthToken() : null;

                const headers = {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                };

                // Add Authorization header if token exists
                if (token) {
                    headers['Authorization'] = `Bearer ${token}`;
                }

                const response = await fetch('/api/products', {
                    method: 'GET',
                    headers: headers
                });

                if (response.ok) {
                    const data = await response.json();
                    products = data.data?.data || [];
                    isLoading = false;
                    displayProducts();
                } else {
                    throw new Error('Gagal memuat produk');
                }
            } catch (error) {
                console.error('Error fetching products:', error);
                isLoading = false;
                showError('Gagal memuat produk: ' + error.message);
                displayProducts();
            }
        }

        // Display products
        function displayProducts() {
            const loadingGrid = document.getElementById('loadingGrid');
            const productsGrid = document.getElementById('productsGrid');
            const emptyState = document.getElementById('emptyState');
            const productCount = document.getElementById('productCount');

            loadingGrid.style.display = 'none';

            const filteredProducts = getFilteredProducts();
            productCount.textContent = `${filteredProducts.length} Produk`;

            if (filteredProducts.length === 0) {
                productsGrid.style.display = 'none';
                emptyState.style.display = 'block';
            } else {
                emptyState.style.display = 'none';
                productsGrid.style.display = 'grid';
                productsGrid.innerHTML = '';

                filteredProducts.forEach((product, index) => {
                    const productCard = createProductCard(product, index);
                    productsGrid.appendChild(productCard);
                });
            }
        }

        // Get filtered products
        function getFilteredProducts() {
            if (selectedCategoryId === null) return products;
            return products.filter(p => p.kategori_id === selectedCategoryId);
        }

        // Create product card
        function createProductCard(product, index) {
            const card = document.createElement('div');
            card.className = 'product-card';
            card.style.animationDelay = `${index * 0.1}s`;

            let poster = 'https://via.placeholder.com/280x180/BBDEFB/1976D2?text=No+Image';
            if (product.main_image_url) {
                poster = product.main_image_url;
            } else if (product.gambar && product.gambar.length > 0) {
                // Fallback to manual path construction if main_image_url is not available
                poster = `/storage/${product.gambar[0]}`;
            }

            const rating = parseFloat(product.rating_rata || 0);
            const price = new Intl.NumberFormat('id-ID').format(product.harga || 0);

            card.innerHTML = `
                <div class="product-image">
                    <img src="${poster}" alt="${product.nama || 'Produk'}"
                         onerror="this.src='https://via.placeholder.com/280x180/BBDEFB/1976D2?text=No+Image'">
                    <div class="fish-type-badge">${product.jenis_ikan || '-'}</div>
                    ${rating > 0 ? `
                        <div class="rating-badge">
                            <i class="fas fa-star"></i>
                            <span>${rating.toFixed(1)}</span>
                        </div>
                    ` : ''}
                </div>
                <div class="product-info">
                    <div class="product-name">${product.nama || 'Produk Tidak Diketahui'}</div>
                    <div class="product-seller">
                        <i class="fas fa-store"></i>
                        <span>${product.seller?.name || '-'}</span>
                    </div>
                    <div class="product-price">Rp ${price}</div>
                    <div class="product-stock">Stok: ${product.stok || 0}</div>
                </div>
            `;

            card.addEventListener('click', () => {
                // Navigate to product detail
                window.location.href = `/product/${product.id}`;
            });

            return card;
        }

        // Filter products by category
        function filterProducts(categoryId) {
            selectedCategoryId = categoryId;

            // Update active category chip
            document.querySelectorAll('.category-chip').forEach(chip => {
                chip.classList.remove('active');
            });
            event.target.closest('.category-chip').classList.add('active');

            displayProducts();
        }

        // Refresh products
        async function refreshProducts() {
            const refreshIndicator = document.getElementById('refreshIndicator');
            refreshIndicator.classList.add('show');

            try {
                await fetchProducts();
            } finally {
                setTimeout(() => {
                    refreshIndicator.classList.remove('show');
                }, 500);
            }
        }

        // Show error message
        function showError(message) {
            // Create and show error alert (implement as needed)
            console.error(message);
        }

        // Show user menu
        function showUserMenu() {
            // Check if user is authenticated first
            const token = getAuthToken ? getAuthToken() : null;
            if (!token) {
                // User is not authenticated - avatar should be login button
                return;
            }
            
            // Use the improved confirmLogout function from auth.js
            if (typeof window.confirmLogout === 'function') {
                window.confirmLogout();
            } else {
                // Fallback if auth.js not loaded
                if (confirm('Apakah Anda ingin logout?')) {
                    fallbackLogout();
                }
            }
        }

        // Fallback logout function if auth.js not available
        async function fallbackLogout() {
            try {
                const response = await fetch('/logout', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    credentials: 'same-origin'
                });

                // Clear any leftover tokens
                localStorage.removeItem('auth_token');
                sessionStorage.removeItem('auth_token');

                // Redirect to login
                window.location.href = '/login';
            } catch (error) {
                console.error('Logout error:', error);
                // Fallback - just redirect to login
                window.location.href = '/login';
            }
        }

        // Check authentication status when page loads
        async function checkAuthenticationStatus() {
            // First test basic auth status
            try {
                console.log('Testing auth status...');

                // Get auth token from auth.js
                const token = getAuthToken ? getAuthToken() : null;

                const headers = {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                };

                // Add Authorization header if token exists
                if (token) {
                    headers['Authorization'] = `Bearer ${token}`;
                }

                const testResponse = await fetch('/auth-test', {
                    method: 'GET',
                    headers: headers,
                    credentials: 'same-origin'
                });

                const testData = await testResponse.json();
                console.log('Auth test result:', testData);

                if (testData.authenticated && testData.user) {
                    // User is authenticated, update UI directly
                    const userAvatar = document.getElementById('userAvatar');
                    const userName = document.getElementById('userName');

                    userAvatar.innerHTML = '<i class="fas fa-user-check" style="color: #4CAF50;"></i>';
                    userName.textContent = testData.user.name;
                    userName.style.display = 'block';
                    console.log('User authenticated:', testData.user.name);

                    // Show protected elements for authenticated users
                    showProtectedElements();

                    // Show farm dashboard button for farm owners
                    if (testData.user.role === 'pemilik_tambak') {
                        const farmDashboardButton = document.getElementById('farmDashboardButton');
                        if (farmDashboardButton) {
                            farmDashboardButton.style.display = 'flex';
                        }
                    }

                    // Load quick actions based on user role
                    displayQuickActions(testData.user);
                } else {
                    console.log('User not authenticated');
                    updateUIForLoggedOut();
                }
            } catch (error) {
                console.error('Error checking authentication:', error);
                updateUIForLoggedOut();
            }
        }

        function redirectToLogin() {
            // Show a brief message before redirecting
            const message = document.createElement('div');
            message.style.cssText = `
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 20px 30px;
                border-radius: 12px;
                text-align: center;
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
                z-index: 10000;
                font-family: 'Inter', sans-serif;
            `;
            message.innerHTML = `
                <i class="fas fa-lock" style="font-size: 24px; margin-bottom: 10px;"></i><br>
                <strong>Login Required</strong><br>
                <small>Redirecting to login page...</small>
            `;
            document.body.appendChild(message);

            setTimeout(() => {
                window.location.href = '/login';
            }, 1500);
        }

        function updateUIForLoggedOut() {
            const userAvatar = document.getElementById('userAvatar');
            const userName = document.getElementById('userName');

            userAvatar.innerHTML = '<i class="fas fa-user"></i>';
            userName.style.display = 'none';

            // Hide protected elements for non-authenticated users
            hideProtectedElements();
            
            // Show login button instead of user menu
            showLoginButton();
        }

        function hideProtectedElements() {
            // Hide cart button and badge for non-authenticated users
            const cartButton = document.getElementById('cartButton');
            if (cartButton) cartButton.style.display = 'none';
            
            // Hide profile button
            const profileButton = document.getElementById('profileButton');
            if (profileButton) profileButton.style.display = 'none';
            
            // Hide fish farm button (requires authentication)
            const fishFarmButton = document.getElementById('fishFarmButton');
            if (fishFarmButton) fishFarmButton.style.display = 'none';
            
            // Hide quick actions section
            const quickActionsSection = document.getElementById('quickActionsSection');
            if (quickActionsSection) quickActionsSection.style.display = 'none';
            
            // Show login notice
            const loginNotice = document.getElementById('loginNotice');
            if (loginNotice) loginNotice.style.display = 'flex';
        }

        function showProtectedElements() {
            // Show cart button for authenticated users
            const cartButton = document.getElementById('cartButton');
            if (cartButton) cartButton.style.display = 'flex';
            
            // Show profile button
            const profileButton = document.getElementById('profileButton');
            if (profileButton) profileButton.style.display = 'flex';
            
            // Show fish farm button
            const fishFarmButton = document.getElementById('fishFarmButton');
            if (fishFarmButton) fishFarmButton.style.display = 'flex';
            
            // Hide login notice
            const loginNotice = document.getElementById('loginNotice');
            if (loginNotice) loginNotice.style.display = 'none';
        }

        function showLoginButton() {
            const userAvatar = document.getElementById('userAvatar');
            if (userAvatar) {
                userAvatar.innerHTML = '<a href="/login" style="color: white; text-decoration: none;"><i class="fas fa-sign-in-alt"></i></a>';
                userAvatar.title = 'Login untuk akses lengkap';
            }
        }

        function requireAuth(callback) {
            // Check if user is authenticated
            const token = getAuthToken ? getAuthToken() : null;
            if (!token) {
                // Show login required message
                const message = document.createElement('div');
                message.style.cssText = `
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    padding: 20px 30px;
                    border-radius: 12px;
                    text-align: center;
                    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
                    z-index: 10000;
                    font-family: 'Inter', sans-serif;
                    display: flex;
                    flex-direction: column;
                    gap: 15px;
                `;
                message.innerHTML = `
                    <i class="fas fa-lock" style="font-size: 24px;"></i>
                    <div>
                        <strong>Login Diperlukan</strong><br>
                        <small>Silakan login untuk mengakses fitur ini</small>
                    </div>
                    <div style="display: flex; gap: 10px; justify-content: center;">
                        <button onclick="window.location.href='/login'" style="background: white; color: #667eea; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-weight: 600;">Login</button>
                        <button onclick="this.parentElement.parentElement.remove()" style="background: transparent; color: white; border: 1px solid white; padding: 8px 16px; border-radius: 6px; cursor: pointer;">Batal</button>
                    </div>
                `;
                document.body.appendChild(message);
                
                // Auto remove message after 5 seconds
                setTimeout(() => {
                    if (message.parentElement) {
                        message.remove();
                    }
                }, 5000);
                
                return false;
            }
            
            // User is authenticated, execute callback
            if (callback) callback();
            return true;
        }

        // Load cart count and update badge
        async function loadCartCount() {
            try {
                // Get auth token from auth.js
                const token = getAuthToken ? getAuthToken() : null;
                
                // Don't load cart count if user is not authenticated
                if (!token) {
                    return;
                }

                const headers = {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };

                // Add Authorization header if token exists
                if (token) {
                    headers['Authorization'] = `Bearer ${token}`;
                }

                const response = await fetch('/api/cart', {
                    method: 'GET',
                    headers: headers,
                    credentials: 'same-origin'
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.success && data.data) {
                        const itemCount = data.data.cart ? data.data.cart.item_count : 0;
                        updateCartBadge(itemCount);
                    } else {
                        updateCartBadge(0);
                    }
                } else {
                    // If unauthorized or error, hide badge
                    updateCartBadge(0);
                }
            } catch (error) {
                console.log('Cart loading failed (user might not be logged in):', error);
                updateCartBadge(0);
            }
        }

        // Update cart badge display
        function updateCartBadge(count) {
            const cartBadge = document.getElementById('cartBadge');
            if (count > 0) {
                cartBadge.textContent = count > 99 ? '99+' : count.toString();
                cartBadge.classList.remove('hidden');
            } else {
                cartBadge.classList.add('hidden');
            }
        }

        // Refresh cart count (can be called after adding items)
        function refreshCartCount() {
            loadCartCount();
        }
    </script>
</body>
</html>
