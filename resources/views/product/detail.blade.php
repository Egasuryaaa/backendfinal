<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detail Produk - IwakMart</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #E3F2FD 0%, #F0F8FF 100%);
            color: #333;
            line-height: 1.6;
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #1976D2 0%, #0D47A1 100%);
            color: white;
            padding: 20px 0;
            box-shadow: 0 4px 20px rgba(25, 118, 210, 0.3);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .back-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            padding: 12px 16px;
            border-radius: 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        .header-title {
            font-size: 24px;
            font-weight: 700;
        }

        .header-actions {
            display: flex;
            gap: 12px;
        }

        .action-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            padding: 12px;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        /* Product Detail */
        .product-detail {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-top: 20px;
        }

        .product-images {
            position: relative;
            height: 400px;
            background: #f5f5f5;
            overflow: hidden;
        }

        .image-carousel {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none;
        }

        .product-image.active {
            display: block;
        }

        .image-indicators {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 8px;
        }

        .indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .indicator.active {
            background: white;
            width: 24px;
            border-radius: 4px;
        }

        .product-info {
            padding: 30px;
        }

        .product-category {
            display: inline-block;
            background: rgba(25, 118, 210, 0.1);
            color: #1976D2;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .product-title {
            font-size: 28px;
            font-weight: 700;
            color: #0D47A1;
            margin-bottom: 16px;
            line-height: 1.3;
        }

        .product-rating {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }

        .rating-stars {
            display: flex;
            gap: 2px;
        }

        .star {
            color: #FFD700;
            font-size: 16px;
        }

        .rating-text {
            color: #666;
            font-size: 14px;
        }

        .price-section {
            background: linear-gradient(135deg, #E8F5E8 0%, #C8E6C9 100%);
            padding: 20px;
            border-radius: 16px;
            margin-bottom: 24px;
        }

        .price-label {
            color: #2E7D32;
            font-size: 12px;
            margin-bottom: 4px;
        }

        .price {
            font-size: 28px;
            font-weight: 700;
            color: #2E7D32;
        }

        .seller-info {
            background: #F8FBFF;
            padding: 20px;
            border-radius: 16px;
            border: 1px solid rgba(25, 118, 210, 0.2);
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .seller-icon {
            background: rgba(25, 118, 210, 0.1);
            padding: 12px;
            border-radius: 12px;
            color: #1976D2;
        }

        .seller-details h3 {
            color: #0D47A1;
            font-size: 16px;
            font-weight: 600;
        }

        .seller-details p {
            color: #666;
            font-size: 12px;
        }

        .description-section h3 {
            color: #0D47A1;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .description {
            background: #f9f9f9;
            padding: 16px;
            border-radius: 12px;
            color: #333;
            line-height: 1.6;
            margin-bottom: 24px;
        }

        .specs-section {
            background: #F8FBFF;
            padding: 20px;
            border-radius: 16px;
            border: 1px solid rgba(25, 118, 210, 0.2);
            margin-bottom: 24px;
        }

        .specs-section h3 {
            color: #0D47A1;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .spec-row {
            display: flex;
            margin-bottom: 12px;
        }

        .spec-label {
            color: #666;
            font-size: 14px;
            min-width: 120px;
        }

        .spec-value {
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }

        .spec-value.highlight {
            color: #1976D2;
        }

        .stock-info {
            padding: 16px;
            border-radius: 16px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .stock-info.available {
            background: #E8F5E9;
            color: #2E7D32;
        }

        .stock-info.unavailable {
            background: #FFEBEE;
            color: #E53935;
        }

        .quantity-section {
            background: #F8FBFF;
            padding: 20px;
            border-radius: 16px;
            border: 1px solid rgba(25, 118, 210, 0.2);
            margin-bottom: 24px;
        }

        .quantity-section h3 {
            color: #0D47A1;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .quantity-btn {
            background: #1976D2;
            border: none;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .quantity-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .quantity-btn:hover:not(:disabled) {
            background: #0D47A1;
            transform: translateY(-2px);
        }

        .quantity-display {
            background: white;
            padding: 8px 20px;
            border-radius: 12px;
            border: 1px solid rgba(25, 118, 210, 0.2);
            font-weight: 600;
            color: #0D47A1;
        }

        .stock-available {
            color: #666;
            font-size: 14px;
            margin-left: auto;
        }

        /* Action Buttons */
        .action-buttons {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 20px;
            box-shadow: 0 -5px 20px rgba(0, 0, 0, 0.1);
            display: flex;
            gap: 12px;
            z-index: 100;
        }

        .add-to-cart-btn {
            flex: 3;
            background: linear-gradient(135deg, #1976D2 0%, #0D47A1 100%);
            color: white;
            border: none;
            padding: 16px;
            border-radius: 16px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(25, 118, 210, 0.4);
        }

        .add-to-cart-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(25, 118, 210, 0.5);
        }

        .add-to-cart-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .quick-buy-btn {
            width: 56px;
            height: 56px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.4);
        }

        .quick-buy-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(76, 175, 80, 0.5);
        }

        /* Loading */
        .loading {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 200px;
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

        /* Snackbar */
        .snackbar {
            position: fixed;
            bottom: 100px;
            left: 50%;
            transform: translateX(-50%);
            background: #333;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .snackbar.show {
            opacity: 1;
            visibility: visible;
        }

        .snackbar.success {
            background: #4CAF50;
        }

        .snackbar.error {
            background: #f44336;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            .product-info {
                padding: 20px;
            }

            .product-title {
                font-size: 24px;
            }

            .price {
                font-size: 24px;
            }

            .action-buttons {
                padding: 16px;
            }
        }

        /* Add bottom padding to prevent content hiding behind fixed action buttons */
        .content-wrapper {
            padding-bottom: 100px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-content">
            <button class="back-btn" onclick="goBack()">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </button>
            <h1 class="header-title">Detail Produk</h1>
            <div class="header-actions">
                <button class="action-btn" onclick="toggleFavorite()">
                    <i class="fas fa-heart" id="favoriteIcon"></i>
                </button>
                <button class="action-btn" onclick="shareProduct()">
                    <i class="fas fa-share"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="content-wrapper">
        <div class="container">
            <!-- Loading State -->
            <div id="loadingState" class="loading">
                <div class="spinner"></div>
            </div>

            <!-- Product Detail -->
            <div id="productDetail" class="product-detail" style="display: none;">
                <!-- Product Images -->
                <div class="product-images">
                    <div class="image-carousel" id="imageCarousel">
                        <!-- Images will be dynamically loaded -->
                    </div>
                    <div class="image-indicators" id="imageIndicators">
                        <!-- Indicators will be dynamically loaded -->
                    </div>
                </div>

                <!-- Product Info -->
                <div class="product-info">
                    <!-- Product Header -->
                    <div class="product-category" id="productCategory"></div>
                    <h1 class="product-title" id="productTitle"></h1>
                    <div class="product-rating" id="productRating"></div>

                    <!-- Price -->
                    <div class="price-section">
                        <div class="price-label">Harga per kg</div>
                        <div class="price" id="productPrice"></div>
                    </div>

                    <!-- Seller Info -->
                    <div class="seller-info">
                        <div class="seller-icon">
                            <i class="fas fa-store"></i>
                        </div>
                        <div class="seller-details">
                            <p>Penjual</p>
                            <h3 id="sellerName"></h3>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="description-section">
                        <h3>Deskripsi Produk</h3>
                        <div class="description" id="productDescription"></div>
                    </div>

                    <!-- Specifications -->
                    <div class="specs-section">
                        <h3>Spesifikasi Produk</h3>
                        <div id="productSpecs"></div>
                    </div>

                    <!-- Stock Info -->
                    <div class="stock-info" id="stockInfo">
                        <i class="fas fa-check-circle"></i>
                        <span id="stockText"></span>
                    </div>

                    <!-- Quantity Selector -->
                    <div class="quantity-section">
                        <h3>Jumlah Pesanan</h3>
                        <div class="quantity-controls">
                            <button class="quantity-btn" id="decreaseBtn" onclick="decreaseQuantity()">
                                <i class="fas fa-minus"></i>
                            </button>
                            <div class="quantity-display">
                                <span id="quantityValue">1</span> kg
                            </div>
                            <button class="quantity-btn" id="increaseBtn" onclick="increaseQuantity()">
                                <i class="fas fa-plus"></i>
                            </button>
                            <div class="stock-available">
                                Tersedia: <span id="availableStock"></span> kg
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="action-buttons">
        <button class="add-to-cart-btn" id="addToCartBtn" onclick="addToCart()">
            <i class="fas fa-shopping-cart"></i>
            <span id="addToCartText">Tambah ke Keranjang</span>
        </button>
        <button class="quick-buy-btn" onclick="quickBuy()">
            <i class="fas fa-bolt"></i>
        </button>
    </div>

    <!-- Snackbar -->
    <div class="snackbar" id="snackbar">
        <span id="snackbarText"></span>
    </div>

    <script>
        // Global variables
        let currentProduct = null;
        let currentQuantity = 1;
        let isFavorite = false;
        let isLoading = false;
        let currentImageIndex = 0;
        const productId = {{ $productId }};

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            loadProductDetail();
        });

        // Load product detail from API
        async function loadProductDetail() {
            try {
                showLoading(true);
                
                const response = await fetch(`/api/products/${productId}`, {
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                
                if (data.success) {
                    currentProduct = data.data.product;
                    displayProduct(currentProduct);
                } else {
                    throw new Error(data.message || 'Failed to load product');
                }
            } catch (error) {
                console.error('Error loading product:', error);
                showSnackbar('Gagal memuat detail produk', 'error');
                // Redirect back to fishmarket if product not found
                setTimeout(() => {
                    window.location.href = '/fishmarket';
                }, 2000);
            } finally {
                showLoading(false);
            }
        }

        // Display product information
        function displayProduct(product) {
            // Update page title
            document.title = `${product.nama} - IwakMart`;

            // Product category
            document.getElementById('productCategory').textContent = product.jenis_ikan || 'Ikan';

            // Product title
            document.getElementById('productTitle').textContent = product.nama;

            // Rating
            const ratingContainer = document.getElementById('productRating');
            const rating = parseFloat(product.rating_rata || 0);
            const reviewCount = product.jumlah_ulasan || 0;
            
            ratingContainer.innerHTML = `
                <div class="rating-stars">
                    ${generateStars(rating)}
                </div>
                <span class="rating-text">${rating.toFixed(1)} (${reviewCount} ulasan)</span>
            `;

            // Price
            const price = parseFloat(product.harga || 0);
            document.getElementById('productPrice').textContent = `Rp ${formatPrice(price)}`;

            // Seller
            document.getElementById('sellerName').textContent = product.seller?.name || 'Nama Penjual';

            // Description
            document.getElementById('productDescription').textContent = stripHtmlTags(product.deskripsi || 'Tidak ada deskripsi');

            // Specifications
            displaySpecs(product);

            // Stock info
            displayStockInfo(product);

            // Images
            displayImages(product.gambar || []);

            // Update available stock display
            document.getElementById('availableStock').textContent = product.stok || 0;

            // Update quantity controls
            updateQuantityControls();

            // Show product detail
            document.getElementById('productDetail').style.display = 'block';
        }

        // Generate star rating
        function generateStars(rating) {
            let stars = '';
            for (let i = 1; i <= 5; i++) {
                if (i <= rating) {
                    stars += '<i class="fas fa-star star"></i>';
                } else if (i - 0.5 <= rating) {
                    stars += '<i class="fas fa-star-half-alt star"></i>';
                } else {
                    stars += '<i class="far fa-star star"></i>';
                }
            }
            return stars;
        }

        // Display specifications
        function displaySpecs(product) {
            const specsContainer = document.getElementById('productSpecs');
            const specs = [
                { label: 'Kategori', value: product.category?.nama || '-' },
                { label: 'Jenis Ikan', value: product.jenis_ikan || '-' },
                { label: 'Spesies', value: product.spesies_ikan || '-' },
                { label: 'Berat', value: `${product.berat || 0} kg` },
                { label: 'Stok', value: `${product.stok || 0} kg` }
            ];

            if (product.unggulan) {
                specs.push({ label: 'Status', value: 'Produk Unggulan', highlight: true });
            }

            specsContainer.innerHTML = specs.map(spec => `
                <div class="spec-row">
                    <span class="spec-label">${spec.label}:</span>
                    <span class="spec-value ${spec.highlight ? 'highlight' : ''}">${spec.value}</span>
                </div>
            `).join('');
        }

        // Display stock information
        function displayStockInfo(product) {
            const stockContainer = document.getElementById('stockInfo');
            const stock = product.stok || 0;
            
            if (stock > 0) {
                stockContainer.className = 'stock-info available';
                stockContainer.innerHTML = `
                    <i class="fas fa-check-circle"></i>
                    <span>Stok tersedia: ${stock} kg</span>
                `;
            } else {
                stockContainer.className = 'stock-info unavailable';
                stockContainer.innerHTML = `
                    <i class="fas fa-exclamation-circle"></i>
                    <span>Stok habis</span>
                `;
            }
        }

        // Display product images
        function displayImages(images) {
            const carousel = document.getElementById('imageCarousel');
            const indicators = document.getElementById('imageIndicators');
            
            if (!images || images.length === 0) {
                carousel.innerHTML = `
                    <img src="https://via.placeholder.com/400x400/BBDEFB/1976D2?text=No+Image" 
                         alt="No Image" class="product-image active">
                `;
                indicators.innerHTML = '';
                return;
            }

            // Display images
            carousel.innerHTML = images.map((image, index) => `
                <img src="/storage/${image}" 
                     alt="Product Image ${index + 1}" 
                     class="product-image ${index === 0 ? 'active' : ''}"
                     onerror="this.src='https://via.placeholder.com/400x400/BBDEFB/1976D2?text=No+Image'">
            `).join('');

            // Display indicators if multiple images
            if (images.length > 1) {
                indicators.innerHTML = images.map((_, index) => `
                    <div class="indicator ${index === 0 ? 'active' : ''}" 
                         onclick="showImage(${index})"></div>
                `).join('');
            }
        }

        // Show specific image
        function showImage(index) {
            const images = document.querySelectorAll('.product-image');
            const indicators = document.querySelectorAll('.indicator');
            
            // Hide all images
            images.forEach(img => img.classList.remove('active'));
            indicators.forEach(ind => ind.classList.remove('active'));
            
            // Show selected image
            if (images[index]) {
                images[index].classList.add('active');
                currentImageIndex = index;
            }
            
            if (indicators[index]) {
                indicators[index].classList.add('active');
            }
        }

        // Quantity controls
        function decreaseQuantity() {
            if (currentQuantity > 1) {
                currentQuantity--;
                updateQuantityDisplay();
                updateQuantityControls();
            }
        }

        function increaseQuantity() {
            const maxStock = currentProduct?.stok || 0;
            if (currentQuantity < maxStock) {
                currentQuantity++;
                updateQuantityDisplay();
                updateQuantityControls();
            }
        }

        function updateQuantityDisplay() {
            document.getElementById('quantityValue').textContent = currentQuantity;
        }

        function updateQuantityControls() {
            const maxStock = currentProduct?.stok || 0;
            const decreaseBtn = document.getElementById('decreaseBtn');
            const increaseBtn = document.getElementById('increaseBtn');
            
            decreaseBtn.disabled = currentQuantity <= 1;
            increaseBtn.disabled = currentQuantity >= maxStock;
        }

        // Add to cart
        async function addToCart() {
            if (isLoading || !currentProduct) return;
            
            const stock = currentProduct.stok || 0;
            if (stock <= 0) {
                showSnackbar('Produk sedang tidak tersedia', 'error');
                return;
            }

            try {
                setLoading(true);
                
                const formData = new FormData();
                formData.append('produk_id', currentProduct.id);
                formData.append('jumlah', currentQuantity);

                const response = await fetch('/api/cart', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    showSnackbar('Produk berhasil ditambahkan ke keranjang!', 'success');
                    
                    // Update cart badge if function exists (for fishmarket page)
                    if (typeof refreshCartCount === 'function') {
                        refreshCartCount();
                    }
                    
                    // Optionally show success dialog or redirect to cart
                    setTimeout(() => {
                        if (confirm('Produk berhasil ditambahkan ke keranjang. Lihat keranjang sekarang?')) {
                            window.location.href = '/cart';
                        }
                    }, 1000);
                } else {
                    throw new Error(data.message || 'Gagal menambahkan ke keranjang');
                }
            } catch (error) {
                console.error('Error adding to cart:', error);
                showSnackbar(error.message || 'Terjadi kesalahan saat menambahkan ke keranjang', 'error');
            } finally {
                setLoading(false);
            }
        }

        // Quick buy (placeholder)
        function quickBuy() {
            showSnackbar('Fitur beli langsung akan segera hadir', 'error');
        }

        // Toggle favorite (placeholder)
        function toggleFavorite() {
            isFavorite = !isFavorite;
            const icon = document.getElementById('favoriteIcon');
            icon.className = isFavorite ? 'fas fa-heart' : 'far fa-heart';
            showSnackbar(isFavorite ? 'Ditambahkan ke favorit' : 'Dihapus dari favorit', 'success');
        }

        // Share product (placeholder)
        function shareProduct() {
            if (navigator.share && currentProduct) {
                navigator.share({
                    title: currentProduct.nama,
                    text: `Lihat ${currentProduct.nama} di IwakMart`,
                    url: window.location.href
                });
            } else {
                // Fallback: copy to clipboard
                navigator.clipboard.writeText(window.location.href).then(() => {
                    showSnackbar('Link berhasil disalin', 'success');
                });
            }
        }

        // Navigation
        function goBack() {
            if (window.history.length > 1) {
                window.history.back();
            } else {
                window.location.href = '/fishmarket';
            }
        }

        // Utility functions
        function formatPrice(price) {
            return new Intl.NumberFormat('id-ID').format(price);
        }

        function stripHtmlTags(html) {
            const temp = document.createElement('div');
            temp.innerHTML = html;
            return temp.textContent || temp.innerText || '';
        }

        function showLoading(show) {
            const loadingState = document.getElementById('loadingState');
            const productDetail = document.getElementById('productDetail');
            
            if (show) {
                loadingState.style.display = 'flex';
                productDetail.style.display = 'none';
            } else {
                loadingState.style.display = 'none';
            }
        }

        function setLoading(loading) {
            isLoading = loading;
            const addToCartBtn = document.getElementById('addToCartBtn');
            const addToCartText = document.getElementById('addToCartText');
            
            if (loading) {
                addToCartBtn.disabled = true;
                addToCartText.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
            } else {
                addToCartBtn.disabled = false;
                addToCartText.innerHTML = '<i class="fas fa-shopping-cart"></i> Tambah ke Keranjang';
            }
        }

        function showSnackbar(message, type = 'success') {
            const snackbar = document.getElementById('snackbar');
            const snackbarText = document.getElementById('snackbarText');
            
            snackbarText.textContent = message;
            snackbar.className = `snackbar ${type}`;
            snackbar.classList.add('show');
            
            setTimeout(() => {
                snackbar.classList.remove('show');
            }, 3000);
        }
    </script>
</body>
</html>
