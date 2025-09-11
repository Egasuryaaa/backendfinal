<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Keranjang Belanja - IwakMart</title>
    
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
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Modern Header */
        .header {
            background: linear-gradient(135deg, #1565C0 0%, #0D47A1 50%, #002171 100%);
            color: white;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .header-content {
            display: flex;
            align-items: center;
            gap: 16px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .back-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            padding: 12px;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        .cart-icon {
            background: rgba(255, 255, 255, 0.2);
            padding: 12px;
            border-radius: 16px;
        }

        .header-info h1 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .header-info p {
            font-size: 12px;
            opacity: 0.8;
        }

        /* Content */
        .content {
            flex: 1;
            padding: 20px;
        }

        /* Loading */
        .loading {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 300px;
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

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .empty-state .icon {
            background: rgba(25, 118, 210, 0.1);
            padding: 32px;
            border-radius: 50px;
            display: inline-block;
            margin-bottom: 24px;
        }

        .empty-state .icon i {
            font-size: 64px;
            color: #1976D2;
        }

        .empty-state h3 {
            font-size: 20px;
            font-weight: 700;
            color: #0D47A1;
            margin-bottom: 12px;
        }

        .empty-state p {
            color: #666;
            margin-bottom: 32px;
            line-height: 1.5;
        }

        .start-shopping-btn {
            background: linear-gradient(135deg, #1976D2 0%, #0D47A1 100%);
            color: white;
            border: none;
            padding: 16px 32px;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 8px 20px rgba(25, 118, 210, 0.4);
        }

        .start-shopping-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(25, 118, 210, 0.5);
        }

        /* Cart Items */
        .cart-items {
            display: none; /* Initially hidden, will be shown via JS */
            flex-direction: column;
            gap: 16px;
        }
        
        .cart-items.show {
            display: flex !important;
        }

        .cart-item {
            background: white;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(25, 118, 210, 0.1);
            transition: all 0.3s ease;
            animation: slideIn 0.5s ease-out;
        }

        .cart-item.selected {
            border-color: rgba(25, 118, 210, 0.3);
            box-shadow: 0 10px 30px rgba(25, 118, 210, 0.1);
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .cart-item-content {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
        }

        .custom-checkbox {
            width: 24px;
            height: 24px;
            border: 2px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .custom-checkbox.checked {
            background: #1976D2;
            border-color: #1976D2;
        }

        .custom-checkbox i {
            color: white;
            font-size: 12px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .custom-checkbox.checked i {
            opacity: 1;
        }

        .product-image {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            overflow: hidden;
            background: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-image .placeholder {
            color: #1976D2;
            font-size: 32px;
        }

        .product-info {
            flex: 1;
        }

        .product-category {
            background: rgba(25, 118, 210, 0.1);
            color: #1976D2;
            padding: 4px 8px;
            border-radius: 8px;
            font-size: 10px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 8px;
        }

        .product-name {
            font-size: 16px;
            font-weight: 700;
            color: #0D47A1;
            margin-bottom: 4px;
            line-height: 1.3;
        }

        .product-price {
            font-size: 14px;
            font-weight: 600;
            color: #4CAF50;
            margin-bottom: 12px;
        }

        .product-controls {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            background: #F0F8FF;
            border: 1px solid rgba(25, 118, 210, 0.3);
            border-radius: 8px;
            overflow: hidden;
        }

        .quantity-btn {
            background: #1976D2;
            border: none;
            color: white;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .quantity-btn:hover {
            background: #0D47A1;
        }

        .quantity-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .quantity-display {
            padding: 8px 12px;
            font-weight: 600;
            color: #0D47A1;
            min-width: 40px;
            text-align: center;
        }

        .delete-btn {
            background: #FFEBEE;
            border: none;
            color: #D32F2F;
            padding: 8px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .delete-btn:hover {
            background: #FFCDD2;
        }

        /* Footer */
        .cart-footer {
            background: white;
            padding: 24px 20px;
            border-radius: 25px 25px 0 0;
            box-shadow: 0 -5px 20px rgba(0, 0, 0, 0.1);
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 100;
            display: none;
        }

        .cart-footer.show {
            display: block !important;
        }

        /* Add padding to content when footer is visible */
        .content.with-footer {
            padding-bottom: 140px;
        }

        .select-all-section {
            background: #F0F8FF;
            padding: 12px 16px;
            border-radius: 12px;
            border: 1px solid rgba(25, 118, 210, 0.2);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .select-all-text {
            flex: 1;
            font-size: 16px;
            font-weight: 600;
            color: #0D47A1;
        }

        .selected-count {
            font-size: 12px;
            color: #1976D2;
            font-weight: 500;
        }

        .checkout-section {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .total-section {
            flex: 2;
        }

        .total-label {
            font-size: 12px;
            color: #666;
            margin-bottom: 4px;
        }

        .total-price {
            font-size: 20px;
            font-weight: 700;
            color: #4CAF50;
        }

        .checkout-btn {
            flex: 3;
            background: linear-gradient(135deg, #1976D2 0%, #0D47A1 100%);
            color: white;
            border: none;
            padding: 16px;
            border-radius: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 6px 20px rgba(25, 118, 210, 0.4);
        }

        .checkout-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            box-shadow: none;
        }

        .checkout-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(25, 118, 210, 0.5);
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 1;
        }

        .modal-content {
            background: white;
            border-radius: 20px;
            padding: 30px;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            transform: scale(0.9);
            transition: transform 0.3s ease;
        }

        .modal.show .modal-content {
            transform: scale(1);
        }

        .modal-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
        }

        .modal-header i {
            color: #D32F2F;
            font-size: 24px;
        }

        .modal-header h3 {
            font-size: 18px;
            font-weight: 700;
        }

        .modal-body p {
            color: #666;
            line-height: 1.5;
            margin-bottom: 24px;
        }

        .modal-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }

        .btn-cancel {
            background: #f5f5f5;
            color: #666;
            border: none;
            padding: 12px 24px;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-cancel:hover {
            background: #eee;
        }

        .btn-delete {
            background: #D32F2F;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-delete:hover {
            background: #B71C1C;
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
            display: flex;
            align-items: center;
            gap: 8px;
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
            .header {
                padding: 16px;
            }

            .content {
                padding: 16px;
            }

            .cart-item {
                padding: 16px;
            }

            .cart-item-content {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .product-info {
                width: 100%;
            }

            .product-controls {
                width: 100%;
                justify-content: space-between;
            }

            .cart-footer {
                padding: 20px 16px;
            }

            .checkout-section {
                flex-direction: column;
                gap: 12px;
            }

            .total-section,
            .checkout-btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-content">
            <button class="back-btn" onclick="goBack()">
                <i class="fas fa-arrow-left"></i>
            </button>
            
            <div class="cart-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            
            <div class="header-info">
                <h1>Keranjang Belanja</h1>
                <p id="headerSummary">0 item - Rp 0</p>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="content">
            <!-- Loading State -->
            <div id="loadingState" class="loading">
                <div class="spinner"></div>
            </div>

            <!-- Empty State -->
            <div id="emptyState" class="empty-state" style="display: none;">
                <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h3>Keranjang Kosong</h3>
                <p>Belum ada produk di keranjang Anda.<br>Mulai berbelanja sekarang!</p>
                <a href="/fishmarket" class="start-shopping-btn">
                    <i class="fas fa-shopping-bag"></i>
                    Mulai Belanja
                </a>
            </div>

            <!-- Cart Items -->
            <div id="cartItems" class="cart-items">
                <!-- Cart items will be dynamically loaded here -->
            </div>
        </div>

        <!-- Footer -->
        <div id="cartFooter" class="cart-footer" style="display: none;">
            <!-- Select All -->
            <div class="select-all-section">
                <div class="custom-checkbox" id="selectAllCheckbox" onclick="toggleSelectAll()">
                    <i class="fas fa-check"></i>
                </div>
                <span class="select-all-text">Pilih Semua</span>
                <span class="selected-count" id="selectedCount">0 item dipilih</span>
            </div>

            <!-- Checkout -->
            <div class="checkout-section">
                <div class="total-section">
                    <div class="total-label">Total Pembayaran</div>
                    <div class="total-price" id="totalPrice">Rp 0</div>
                </div>
                <button class="checkout-btn" id="checkoutBtn" onclick="proceedToCheckout()">
                    <i class="fas fa-shopping-bag"></i>
                    <span id="checkoutText">Checkout (0)</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal" id="deleteModal">
        <div class="modal-content">
            <div class="modal-header">
                <i class="fas fa-trash"></i>
                <h3>Hapus Item</h3>
            </div>
            <div class="modal-body">
                <p id="deleteMessage">Apakah Anda yakin ingin menghapus item ini dari keranjang?</p>
            </div>
            <div class="modal-actions">
                <button class="btn-cancel" onclick="closeDeleteModal()">Batal</button>
                <button class="btn-delete" onclick="confirmDelete()">Hapus</button>
            </div>
        </div>
    </div>

    <!-- Snackbar -->
    <div class="snackbar" id="snackbar">
        <i class="fas fa-info-circle"></i>
        <span id="snackbarText"></span>
    </div>

    <script>
        // Global variables
        let cartItems = [];
        let selectedItems = [];
        let quantities = [];
        let deleteItemIndex = -1;

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            fetchCartItems();
        });

        // Fetch cart items from API
        async function fetchCartItems() {
            try {
                showLoading(true);
                
                const response = await fetch('/api/cart', {
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                console.log('Cart API Response:', data);
                
                if (data.success) {
                    const items = data.data?.items || [];
                    console.log('Cart Items:', items);
                    cartItems = items;
                    selectedItems = new Array(items.length).fill(true);
                    quantities = items.map(item => {
                        const qty = item.quantity || item.jumlah || 1;
                        return typeof qty === 'number' ? qty : parseInt(qty) || 1;
                    });
                    
                    console.log('Quantities:', quantities);
                    console.log('Selected Items:', selectedItems);
                    
                    displayCartItems();
                    updateHeaderSummary();
                    updateFooter();
                } else {
                    throw new Error(data.message || 'Failed to load cart');
                }
            } catch (error) {
                console.error('Error loading cart:', error);
                showEmptyState();
                showSnackbar('Gagal mengambil data keranjang', 'error');
            } finally {
                showLoading(false);
            }
        }

        // Display cart items
        function displayCartItems() {
            const container = document.getElementById('cartItems');
            const content = document.querySelector('.content');
            console.log('Displaying cart items:', cartItems);
            
            if (cartItems.length === 0) {
                console.log('No cart items, showing empty state');
                showEmptyState();
                return;
            }

            console.log('Cart has items, rendering...');
            container.innerHTML = '';
            
            cartItems.forEach((item, index) => {
                console.log(`Processing item ${index}:`, item);
                const product = item.product || {};
                const imageUrl = product.gambar && product.gambar.length > 0 
                    ? `/storage/${product.gambar[0]}` 
                    : null;
                
                const cartItemElement = createCartItemElement(item, product, imageUrl, index);
                container.appendChild(cartItemElement);
            });

            console.log('Setting container display to flex');
            container.classList.add('show');
            container.style.visibility = 'visible';
            
            // Show footer when there are cart items and keep it fixed
            const footer = document.getElementById('cartFooter');
            footer.classList.add('show');
            footer.style.display = 'block';
            footer.style.visibility = 'visible';
            
            // Add padding to content to account for fixed footer
            content.classList.add('with-footer');
            
            console.log('Container after setting display:', {
                classList: container.classList.toString(),
                visibility: container.style.visibility,
                children: container.children.length,
                innerHTML: container.innerHTML.length
            });
            
            console.log('Footer visibility set to block and fixed');
            
            // Ensure containers are visible
            document.getElementById('emptyState').style.display = 'none';
            document.getElementById('loadingState').style.display = 'none';
            
            // Force container to be visible
            console.log('Final container computed style:', window.getComputedStyle(container).display);
        }

        // Create cart item element
        function createCartItemElement(item, product, imageUrl, index) {
            console.log(`Creating cart item element for index ${index}:`, {item, product, imageUrl});
            
            const div = document.createElement('div');
            div.className = `cart-item ${selectedItems[index] ? 'selected' : ''}`;
            div.style.animationDelay = `${index * 0.1}s`;
            
            const productName = product.nama || 'Produk';
            const productPrice = product.harga || 0;
            const productCategory = product.jenis_ikan || '-';
            const currentQuantity = quantities[index] || 1;
            
            console.log(`Product details: ${productName}, ${productPrice}, ${productCategory}, qty: ${currentQuantity}`);
            
            div.innerHTML = `
                <div class="cart-item-content">
                    <div class="checkbox-container">
                        <div class="custom-checkbox ${selectedItems[index] ? 'checked' : ''}" 
                             onclick="toggleItemSelection(${index})">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>
                    
                    <div class="product-image">
                        ${imageUrl ? 
                            `<img src="${imageUrl}" alt="${productName}" 
                                  onerror="this.parentElement.innerHTML='<i class=\\"fas fa-image placeholder\\"></i>'">` :
                            '<i class="fas fa-image placeholder"></i>'
                        }
                    </div>
                    
                    <div class="product-info">
                        <div class="product-category">${productCategory}</div>
                        <div class="product-name">${productName}</div>
                        <div class="product-price">Rp ${formatPrice(productPrice)}/kg</div>
                        
                        <div class="product-controls">
                            <div class="quantity-controls">
                                <button class="quantity-btn" onclick="updateQuantity(${index}, ${currentQuantity - 1})"
                                        ${currentQuantity <= 1 ? 'disabled' : ''}>
                                    <i class="fas fa-minus"></i>
                                </button>
                                <div class="quantity-display">${currentQuantity}</div>
                                <button class="quantity-btn" onclick="updateQuantity(${index}, ${currentQuantity + 1})">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            
                            <button class="delete-btn" onclick="showDeleteConfirmation(${index}, '${productName.replace(/'/g, "\\'")}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            console.log('Cart item HTML created:', div.outerHTML);
            return div;
        }

        // Toggle item selection
        function toggleItemSelection(index) {
            selectedItems[index] = !selectedItems[index];
            updateDisplay();
        }

        // Toggle select all
        function toggleSelectAll() {
            const allSelected = selectedItems.every(item => item);
            selectedItems.fill(!allSelected);
            updateDisplay();
        }

        // Update quantity
        async function updateQuantity(index, newQuantity) {
            if (newQuantity < 1) return;

            const oldQuantity = quantities[index];
            quantities[index] = newQuantity;
            cartItems[index].jumlah = newQuantity;
            
            updateDisplay();

            try {
                const item = cartItems[index];
                const cartItemId = item.id;

                const formData = new FormData();
                formData.append('jumlah', newQuantity.toString());
                formData.append('_method', 'PUT');

                const response = await fetch(`/api/cart/${cartItemId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                if (!response.ok) {
                    throw new Error('Failed to update quantity');
                }
            } catch (error) {
                console.error('Error updating quantity:', error);
                quantities[index] = oldQuantity;
                cartItems[index].jumlah = oldQuantity;
                updateDisplay();
                showSnackbar('Gagal mengupdate jumlah', 'error');
            }
        }

        // Show delete confirmation
        function showDeleteConfirmation(index, productName) {
            deleteItemIndex = index;
            document.getElementById('deleteMessage').textContent = 
                `Apakah Anda yakin ingin menghapus "${productName}" dari keranjang?`;
            document.getElementById('deleteModal').classList.add('show');
        }

        // Close delete modal
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('show');
            deleteItemIndex = -1;
        }

        // Confirm delete
        async function confirmDelete() {
            if (deleteItemIndex === -1) return;

            try {
                const item = cartItems[deleteItemIndex];
                const cartItemId = item.id;
                console.log('Deleting cart item with ID:', cartItemId);
                console.log('Cart item data:', item);

                const response = await fetch(`/api/cart/${cartItemId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                console.log('Delete response status:', response.status);
                
                if (response.ok) {
                    cartItems.splice(deleteItemIndex, 1);
                    selectedItems.splice(deleteItemIndex, 1);
                    quantities.splice(deleteItemIndex, 1);
                    
                    if (cartItems.length === 0) {
                        showEmptyState();
                    } else {
                        updateDisplay();
                    }
                    
                    showSnackbar('Item berhasil dihapus dari keranjang', 'success');
                } else {
                    const errorData = await response.text();
                    console.log('Delete error response:', errorData);
                    throw new Error('Failed to delete item');
                }
            } catch (error) {
                console.error('Error deleting item:', error);
                showSnackbar('Gagal menghapus item', 'error');
            } finally {
                closeDeleteModal();
            }
        }

        // Update display
        function updateDisplay() {
            displayCartItems();
            updateHeaderSummary();
            updateFooter();
        }

        // Update header summary
        function updateHeaderSummary() {
            const totalPrice = calculateTotalPrice();
            document.getElementById('headerSummary').textContent = 
                `${cartItems.length} item - Rp ${formatPrice(totalPrice)}`;
        }

        // Update footer
        function updateFooter() {
            const allSelected = selectedItems.length > 0 && selectedItems.every(item => item);
            const selectedCount = selectedItems.filter(item => item).length;
            const totalPrice = calculateTotalPrice();

            // Update select all checkbox
            const selectAllCheckbox = document.getElementById('selectAllCheckbox');
            if (allSelected) {
                selectAllCheckbox.classList.add('checked');
            } else {
                selectAllCheckbox.classList.remove('checked');
            }

            // Update selected count
            document.getElementById('selectedCount').textContent = `${selectedCount} item dipilih`;

            // Update total price
            document.getElementById('totalPrice').textContent = `Rp ${formatPrice(totalPrice)}`;

            // Update checkout button
            const checkoutBtn = document.getElementById('checkoutBtn');
            const checkoutText = document.getElementById('checkoutText');
            
            checkoutBtn.disabled = selectedCount === 0;
            checkoutText.textContent = `Checkout (${selectedCount})`;
        }

        // Calculate total price
        function calculateTotalPrice() {
            let total = 0;
            for (let i = 0; i < cartItems.length; i++) {
                if (selectedItems[i]) {
                    const product = cartItems[i].product || {};
                    const price = parseFloat(product.harga || 0);
                    const qty = quantities[i];
                    total += price * qty;
                }
            }
            return total;
        }

        // Proceed to checkout
        function proceedToCheckout() {
            const selectedCount = selectedItems.filter(item => item).length;
            if (selectedCount === 0) {
                showSnackbar('Pilih item yang ingin di-checkout', 'error');
                return;
            }

            const itemsToCheckout = [];
            for (let i = 0; i < cartItems.length; i++) {
                if (selectedItems[i]) {
                    itemsToCheckout.push({
                        ...cartItems[i],
                        jumlah: quantities[i] // Ensure the latest quantity is used
                    });
                }
            }

            // Store the selected items in sessionStorage to be picked up by the checkout page
            sessionStorage.setItem('checkoutItems', JSON.stringify(itemsToCheckout));

            // Redirect to the checkout page
            window.location.href = '/checkout';
        }

        // Show states
        function showLoading(show) {
            const content = document.querySelector('.content');
            const footer = document.getElementById('cartFooter');
            
            document.getElementById('loadingState').style.display = show ? 'flex' : 'none';
            document.getElementById('emptyState').style.display = 'none';
            document.getElementById('cartItems').style.display = 'none';
            
            if (show) {
                // Hide footer during loading
                footer.classList.remove('show');
                footer.style.display = 'none';
                content.classList.remove('with-footer');
            }
        }

        function showEmptyState() {
            const content = document.querySelector('.content');
            const footer = document.getElementById('cartFooter');
            
            document.getElementById('loadingState').style.display = 'none';
            document.getElementById('emptyState').style.display = 'block';
            document.getElementById('cartItems').style.display = 'none';
            
            // Hide footer when cart is empty
            footer.classList.remove('show');
            footer.style.display = 'none';
            
            // Remove content padding when footer is hidden
            content.classList.remove('with-footer');
        }

        // Utility functions
        function formatPrice(price) {
            return new Intl.NumberFormat('id-ID').format(Math.round(price));
        }

        function goBack() {
            window.location.href = '/fishmarket';
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

        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    </script>
</body>
</html>
