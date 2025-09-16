<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Checkout - IwakMart</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Auth Script -->
    <script src="/js/auth.js"></script>

    <style>
        /* General Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #F0F8FF;
            color: #333;
            line-height: 1.6;
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

        .header-icon {
            background: rgba(255, 255, 255, 0.2);
            padding: 12px;
            border-radius: 16px;
        }

        .header-info h1 {
            font-size: 20px;
            font-weight: 700;
        }

        .header-info p {
            font-size: 12px;
            opacity: 0.8;
        }

        /* Content */
        .content {
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

        /* Modern Card */
        .modern-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            padding: 24px;
            margin-bottom: 20px;
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 20px;
        }

        .card-icon {
            padding: 12px;
            border-radius: 12px;
        }

        .card-icon i {
            font-size: 20px;
        }

        .card-title {
            font-size: 18px;
            font-weight: 700;
            color: #0D47A1;
        }

        /* Order Summary */
        .order-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }
        .order-item:last-child {
            border-bottom: none;
        }
        .order-item-img {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            object-fit: cover;
        }
        .order-item-info {
            flex: 1;
        }
        .order-item-name {
            font-weight: 600;
        }
        .order-item-price {
            font-size: 12px;
            color: #666;
        }
        .order-item-total {
            font-weight: bold;
            color: #4CAF50;
        }

        /* Address */
        .address-list .address-item {
            border: 1px solid #ddd;
            border-radius: 12px;
            margin-bottom: 12px;
            transition: all 0.3s ease;
        }
        .address-list .address-item.selected {
            border-color: #1976D2;
            background: #E3F2FD;
        }
        .address-radio {
            padding: 16px;
            cursor: pointer;
        }
        .address-radio label {
            display: block;
            cursor: pointer;
        }
        .address-name {
            font-weight: bold;
        }
        .address-main-badge {
            background: #4CAF50;
            color: white;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 6px;
            margin-left: 8px;
        }

        /* Shipping & Payment */
        .option-list .option-item {
            border: 1px solid #ddd;
            border-radius: 12px;
            margin-bottom: 8px;
            transition: all 0.3s ease;
        }
        .option-list .option-item.selected {
            border-color: #1976D2;
            background: #E3F2FD;
        }
        .option-radio {
            padding: 16px;
            cursor: pointer;
        }
        .option-radio label {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
        }
        .option-title {
            font-weight: 600;
        }
        .option-subtitle {
            font-size: 12px;
            color: #666;
        }
        .option-price {
            margin-left: auto;
            font-weight: bold;
            color: #4CAF50;
        }

        /* Payment Groups */
        .payment-group {
            margin-bottom: 20px;
        }
        .payment-group h4 {
            margin-bottom: 12px;
            color: #333;
            font-size: 16px;
            font-weight: 600;
            padding-left: 4px;
        }
        .payment-channels .option-item {
            margin-bottom: 8px;
        }
        .payment-icon {
            font-size: 20px;
            min-width: 24px;
            text-align: center;
        }

        /* Notes */
        .notes-field {
            width: 100%;
            padding: 12px;
            border-radius: 12px;
            border: 1px solid #ddd;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
        }

        /* Total Summary */
        .total-summary-card {
            background: linear-gradient(135deg, #E8F5E9, #C8E6C9);
            border: 2px solid rgba(76, 175, 80, 0.3);
        }
        .price-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
        }
        .price-row.total {
            font-weight: bold;
            font-size: 18px;
            color: #2E7D32;
            border-top: 2px solid rgba(76, 175, 80, 0.3);
            margin-top: 8px;
            padding-top: 12px;
        }

        /* Floating Footer */
        .floating-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 16px;
            border-radius: 25px 25px 0 0;
            box-shadow: 0 -5px 20px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .footer-total-section {
            flex: 1;
        }
        .footer-total-label {
            font-size: 12px;
            color: #666;
        }
        .footer-total-price {
            font-size: 20px;
            font-weight: 700;
            color: #4CAF50;
        }
        .process-checkout-btn {
            flex: 1;
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
        }
        .process-checkout-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        /* Success Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        .modal.show {
            display: flex;
        }
        .modal-content {
            background: white;
            border-radius: 20px;
            padding: 30px;
            max-width: 400px;
            width: 90%;
            text-align: center;
        }
        .modal-icon {
            padding: 20px;
            background: linear-gradient(135deg, #4CAF50, #2E7D32);
            border-radius: 50%;
            display: inline-block;
            margin-bottom: 24px;
        }
        .modal-icon i {
            color: white;
            font-size: 40px;
        }
        .modal-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin-top: 24px;
        }
        .btn {
            padding: 12px 24px;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
        }
        .btn-secondary {
            background: #f5f5f5;
            color: #666;
        }
        .btn-primary {
            background: #1976D2;
            color: white;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-content">
            <button class="back-btn" onclick="window.location.href='/cart'">
                <i class="fas fa-arrow-left"></i>
            </button>
            <div class="header-icon">
                <i class="fas fa-receipt"></i>
            </div>
            <div class="header-info">
                <h1>Checkout</h1>
                <p id="itemCountHeader">0 item dipilih</p>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="content">
            <div id="loadingState" class="loading">
                <div class="spinner"></div>
            </div>

            <div id="checkoutContent" style="display: none;">
                <!-- Order Summary -->
                <div class="modern-card">
                    <div class="card-header">
                        <div class="card-icon" style="background-color: rgba(25, 118, 210, 0.1);"><i class="fas fa-receipt" style="color: #1976D2;"></i></div>
                        <h2 class="card-title">Ringkasan Pesanan</h2>
                    </div>
                    <div id="orderSummary"></div>
                </div>

                <!-- Shipping Address -->
                <div class="modern-card">
                    <div class="card-header">
                        <div class="card-icon" style="background-color: rgba(76, 175, 80, 0.1);"><i class="fas fa-location-dot" style="color: #4CAF50;"></i></div>
                        <h2 class="card-title">Alamat Pengiriman</h2>
                    </div>
                    <div id="addressList" class="address-list"></div>
                    <button onclick="window.location.href='/addresses'">Kelola Alamat</button>
                </div>

                <!-- Shipping Method -->
                <div class="modern-card">
                    <div class="card-header">
                        <div class="card-icon" style="background-color: rgba(156, 39, 176, 0.1);"><i class="fas fa-truck-fast" style="color: #9C27B0;"></i></div>
                        <h2 class="card-title">Metode Pengiriman</h2>
                    </div>
                    <div id="shippingMethodList" class="option-list"></div>
                </div>

                <!-- Payment Method -->
                <div class="modern-card">
                    <div class="card-header">
                        <div class="card-icon" style="background-color: rgba(46, 125, 50, 0.1);"><i class="fas fa-credit-card" style="color: #2E7D32;"></i></div>
                        <h2 class="card-title">Metode Pembayaran</h2>
                    </div>
                    <div id="paymentMethodList" class="option-list"></div>
                </div>

                <!-- Notes -->
                <div class="modern-card">
                    <div class="card-header">
                        <div class="card-icon" style="background-color: rgba(255, 152, 0, 0.1);"><i class="fas fa-note-sticky" style="color: #FF9800;"></i></div>
                        <h2 class="card-title">Catatan (Opsional)</h2>
                    </div>
                    <textarea id="notes" class="notes-field" placeholder="Tambahkan catatan untuk penjual..."></textarea>
                </div>

                <!-- Total Summary -->
                <div class="modern-card total-summary-card">
                    <div class="card-header">
                        <div class="card-icon" style="background-color: rgba(76, 175, 80, 0.2);"><i class="fas fa-receipt" style="color: #2E7D32;"></i></div>
                        <h2 class="card-title">Ringkasan Pembayaran</h2>
                    </div>
                    <div class="price-row">
                        <span>Subtotal</span>
                        <span id="subtotalPrice">Rp 0</span>
                    </div>
                    <div class="price-row">
                        <span>Ongkir</span>
                        <span id="shippingPrice">Rp 0</span>
                    </div>
                    <div class="price-row total">
                        <span>Total</span>
                        <span id="grandTotalPrice">Rp 0</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Footer -->
    <div class="floating-footer">
        <div class="footer-total-section">
            <div class="footer-total-label">Total Pembayaran</div>
            <div class="footer-total-price" id="footerTotalPrice">Rp 0</div>
        </div>
        <button id="processCheckoutBtn" class="process-checkout-btn" disabled>
            <i class="fas fa-shield-alt"></i>
            <span>Proses Checkout</span>
        </button>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="modal">
        <div class="modal-content">
            <div class="modal-icon">
                <i class="fas fa-check"></i>
            </div>
            <h2>Pesanan Berhasil!</h2>
            <p>Pesanan Anda telah berhasil dibuat. Anda akan menerima konfirmasi segera.</p>
            <div class="modal-actions">
                <button class="btn btn-secondary" onclick="window.location.href='/fishmarket'">Kembali ke Beranda</button>
                <button class="btn btn-primary" onclick="window.location.href='/orders'">Lihat Pesanan</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // State
            let cartItems = [];
            let addresses = [];
            let selectedAddressId = null;
            let selectedShippingMethod = 'reguler';
            let selectedPaymentMethod = 'cod';
            let selectedPaymentType = 'cod';
            let selectedXenditMethod = '';
            let selectedPaymentChannel = '';
            let shippingCost = 10000;
            let isLoading = false;

            const formatPrice = (price) => new Intl.NumberFormat('id-ID').format(Math.round(price));

            // Functions
            function init() {
                const itemsFromStorage = sessionStorage.getItem('checkoutItems');
                if (!itemsFromStorage) {
                    alert('Tidak ada item untuk di-checkout. Kembali ke keranjang.');
                    window.location.href = '/cart';
                    return;
                }
                cartItems = JSON.parse(itemsFromStorage);
                document.getElementById('itemCountHeader').textContent = `${cartItems.length} item dipilih`;

                renderOrderSummary();
                fetchAddresses();
                renderShippingMethods();
                fetchAndRenderPaymentMethods();
                updateTotals();

                document.getElementById('loadingState').style.display = 'none';
                document.getElementById('checkoutContent').style.display = 'block';
            }

            function renderOrderSummary() {
                const container = document.getElementById('orderSummary');
                container.innerHTML = cartItems.map(item => `
                    <div class="order-item">
                        <img src="/storage/${item.product.gambar[0]}" class="order-item-img" alt="${item.product.nama}">
                        <div class="order-item-info">
                            <div class="order-item-name">${item.product.nama} (${item.jumlah}x)</div>
                            <div class="order-item-price">Rp ${formatPrice(item.product.harga)}/kg</div>
                        </div>
                        <div class="order-item-total">Rp ${formatPrice(item.product.harga * item.jumlah)}</div>
                    </div>
                `).join('');
            }

            async function fetchAddresses() {
                try {
                    // Get auth token from auth.js
                    const token = getAuthToken ? getAuthToken() : null;

                    const headers = {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    };

                    // Add Authorization header if token exists
                    if (token) {
                        headers['Authorization'] = `Bearer ${token}`;
                    }

                    const response = await fetch('/api/addresses', {
                        headers: headers
                    });
                    const data = await response.json();
                    if (data.success) {
                        addresses = data.data;
                        const mainAddress = addresses.find(addr => addr.alamat_utama) || addresses[0];
                        if (mainAddress) {
                            selectedAddressId = mainAddress.id;
                        }
                        renderAddresses();
                        updateTotals();
                    }
                } catch (error) {
                    console.error('Failed to fetch addresses:', error);
                }
            }

            function renderAddresses() {
                const container = document.getElementById('addressList');
                if (addresses.length === 0) {
                    container.innerHTML = '<p>Belum ada alamat. Silakan tambahkan alamat terlebih dahulu.</p>';
                    return;
                }
                container.innerHTML = addresses.map(addr => `
                    <div class="address-item ${selectedAddressId === addr.id ? 'selected' : ''}" onclick="selectAddress(${addr.id})">
                        <div class="address-radio">
                            <input type="radio" id="addr_${addr.id}" name="address" value="${addr.id}" ${selectedAddressId === addr.id ? 'checked' : ''}>
                            <label for="addr_${addr.id}">
                                <div>
                                    <span class="address-name">${addr.nama_penerima}</span>
                                    ${addr.alamat_utama ? '<span class="address-main-badge">Utama</span>' : ''}
                                    <div>${addr.telepon}</div>
                                    <div>${addr.alamat_lengkap}, ${addr.kota}, ${addr.provinsi}</div>
                                </div>
                            </label>
                        </div>
                    </div>
                `).join('');
            }

            window.selectAddress = (id) => {
                selectedAddressId = id;
                renderAddresses();
                updateTotals();
            };

            function renderShippingMethods() {
                const methods = [
                    { id: 'reguler', name: 'Reguler (2-3 hari)', desc: 'Pengiriman standar', price: 10000 },
                    { id: 'express', name: 'Express (1 hari)', desc: 'Pengiriman cepat', price: 20000 }
                ];
                const container = document.getElementById('shippingMethodList');
                container.innerHTML = methods.map(method => `
                    <div class="option-item ${selectedShippingMethod === method.id ? 'selected' : ''}" onclick="selectShipping('${method.id}', ${method.price})">
                        <div class="option-radio">
                             <label>
                                <input type="radio" name="shipping" value="${method.id}" ${selectedShippingMethod === method.id ? 'checked' : ''}>
                                <div>
                                    <div class="option-title">${method.name}</div>
                                    <div class="option-subtitle">${method.desc}</div>
                                </div>
                                <div class="option-price">Rp ${formatPrice(method.price)}</div>
                            </label>
                        </div>
                    </div>
                `).join('');
            }

            window.selectShipping = (id, price) => {
                selectedShippingMethod = id;
                shippingCost = price;
                renderShippingMethods();
                updateTotals();
            };

            async function fetchAndRenderPaymentMethods() {
                try {
                    const response = await fetch('/api/payment/methods');
                    const data = await response.json();

                    if (data.success) {
                        renderPaymentMethods(data.data);
                    } else {
                        console.error('Failed to fetch payment methods');
                        renderDefaultPaymentMethods();
                    }
                } catch (error) {
                    console.error('Error fetching payment methods:', error);
                    renderDefaultPaymentMethods();
                }
            }

            function renderPaymentMethods(paymentData) {
                const container = document.getElementById('paymentMethodList');
                let methodsHtml = '';

                // COD Option
                methodsHtml += `
                    <div class="payment-group" style="margin-bottom: 20px;">
                        <h4 style="margin-bottom: 12px; color: #333; font-size: 16px; font-weight: 600;">💰 Bayar di Tempat</h4>
                        <div class="option-item ${selectedPaymentMethod === 'cod' ? 'selected' : ''}" onclick="selectPayment('cod', 'cod', '', '')">
                            <div class="option-radio">
                                <label>
                                    <input type="radio" name="payment" value="cod" ${selectedPaymentMethod === 'cod' ? 'checked' : ''}>
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <div style="font-size: 24px;">💵</div>
                                        <div>
                                            <div class="option-title">Cash on Delivery (COD)</div>
                                            <div class="option-subtitle">Bayar saat barang diterima</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                `;

                // Xendit Payment Methods
                paymentData.forEach(method => {
                    if (method.is_active) {
                        const iconMap = {
                            'bank_transfer': '🏦',
                            'e_wallet': '💳',
                            'retail': '🏪',
                            'qris': '📱',
                            'credit_card': '💳'
                        };

                        methodsHtml += `
                            <div class="payment-group" style="margin-bottom: 20px;">
                                <h4 style="margin-bottom: 12px; color: #333; font-size: 16px; font-weight: 600;">${iconMap[method.id] || '💰'} ${method.name}</h4>
                                <div class="payment-channels">
                        `;

                        method.channels.forEach(channel => {
                            const channelId = `${method.id}_${channel.code}`;
                            methodsHtml += `
                                <div class="option-item ${selectedPaymentMethod === channelId ? 'selected' : ''}" onclick="selectPayment('${channelId}', 'xendit', '${method.id}', '${channel.code}')" style="margin-bottom: 8px;">
                                    <div class="option-radio">
                                        <label>
                                            <input type="radio" name="payment" value="${channelId}" ${selectedPaymentMethod === channelId ? 'checked' : ''}>
                                            <div style="display: flex; align-items: center; gap: 12px;">
                                                <div class="payment-icon" style="font-size: 20px;">
                                                    ${getPaymentIcon(channel.code)}
                                                </div>
                                                <div>
                                                    <div class="option-title">${channel.name}</div>
                                                    <div class="option-subtitle">${getPaymentDescription(method.id, channel.code)}</div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            `;
                        });

                        methodsHtml += `
                                </div>
                            </div>
                        `;
                    }
                });

                container.innerHTML = methodsHtml;
            }

            function renderDefaultPaymentMethods() {
                const methods = [
                    { id: 'cod', name: 'Cash on Delivery (COD)', desc: 'Bayar saat barang diterima', type: 'cod' },
                    { id: 'bank_transfer_BCA', name: 'Bank BCA', desc: 'Transfer via Virtual Account BCA', type: 'xendit', payment_method: 'bank_transfer', payment_channel: 'BCA' },
                    { id: 'bank_transfer_BRI', name: 'Bank BRI', desc: 'Transfer via Virtual Account BRI', type: 'xendit', payment_method: 'bank_transfer', payment_channel: 'BRI' },
                    { id: 'e_wallet_OVO', name: 'OVO', desc: 'Bayar dengan OVO', type: 'xendit', payment_method: 'e_wallet', payment_channel: 'OVO' },
                    { id: 'e_wallet_DANA', name: 'DANA', desc: 'Bayar dengan DANA', type: 'xendit', payment_method: 'e_wallet', payment_channel: 'DANA' },
                    { id: 'qris_QRIS', name: 'QRIS', desc: 'Scan QR Code untuk bayar', type: 'xendit', payment_method: 'qris', payment_channel: 'QRIS' }
                ];

                const container = document.getElementById('paymentMethodList');
                container.innerHTML = methods.map(method => `
                    <div class="option-item ${selectedPaymentMethod === method.id ? 'selected' : ''}" onclick="selectPayment('${method.id}', '${method.type}', '${method.payment_method || ''}', '${method.payment_channel || ''}')">
                        <div class="option-radio">
                            <label>
                                <input type="radio" name="payment" value="${method.id}" ${selectedPaymentMethod === method.id ? 'checked' : ''}>
                                <div>
                                    <div class="option-title">${method.name}</div>
                                    <div class="option-subtitle">${method.desc}</div>
                                </div>
                            </label>
                        </div>
                    </div>
                `).join('');
            }

            function renderPaymentMethodsSelection() {
                // Re-render untuk update selection
                fetchAndRenderPaymentMethods();
            }

            function getPaymentIcon(channelCode) {
                const icons = {
                    'BCA': '🔵',
                    'BRI': '🟢',
                    'BNI': '🟠',
                    'MANDIRI': '🔴',
                    'BSI': '🟢',
                    'CIMB': '🔴',
                    'PERMATA': '⚫',
                    'OVO': '🟣',
                    'DANA': '🔵',
                    'LINKAJA': '🔴',
                    'SHOPEEPAY': '🟠',
                    'ALFAMART': '🔴',
                    'INDOMARET': '🔵',
                    'QRIS': '📱',
                    'CREDIT_CARD': '💳'
                };
                return icons[channelCode] || '💰';
            }

            function getPaymentDescription(methodType, channelCode) {
                const descriptions = {
                    'bank_transfer': {
                        'BCA': 'Transfer via Virtual Account BCA',
                        'BRI': 'Transfer via Virtual Account BRI',
                        'BNI': 'Transfer via Virtual Account BNI',
                        'MANDIRI': 'Transfer via Virtual Account Mandiri',
                        'BSI': 'Transfer via Virtual Account BSI',
                        'CIMB': 'Transfer via Virtual Account CIMB',
                        'PERMATA': 'Transfer via Virtual Account Permata'
                    },
                    'e_wallet': {
                        'OVO': 'Bayar dengan saldo OVO',
                        'DANA': 'Bayar dengan saldo DANA',
                        'LINKAJA': 'Bayar dengan saldo LinkAja',
                        'SHOPEEPAY': 'Bayar dengan saldo ShopeePay'
                    },
                    'retail': {
                        'ALFAMART': 'Bayar di kasir Alfamart',
                        'INDOMARET': 'Bayar di kasir Indomaret'
                    },
                    'qr_code': {
                        'QRIS': 'Scan QR code dengan aplikasi e-wallet'
                    },
                    'credit_card': {
                        'CREDIT_CARD': 'Visa, Mastercard, JCB'
                    }
                };

                return descriptions[methodType]?.[channelCode] || 'Metode pembayaran digital';
            }

            window.selectPayment = (id, type, paymentMethod, paymentChannel) => {
                selectedPaymentMethod = id;
                selectedPaymentType = type || 'cod';
                selectedXenditMethod = paymentMethod || '';
                selectedPaymentChannel = paymentChannel || '';
                renderPaymentMethodsSelection();
            };

            function updateTotals() {
                const subtotal = cartItems.reduce((sum, item) => sum + (item.product.harga * item.jumlah), 0);
                const grandTotal = subtotal + shippingCost;

                document.getElementById('subtotalPrice').textContent = `Rp ${formatPrice(subtotal)}`;
                document.getElementById('shippingPrice').textContent = `Rp ${formatPrice(shippingCost)}`;
                document.getElementById('grandTotalPrice').textContent = `Rp ${formatPrice(grandTotal)}`;
                document.getElementById('footerTotalPrice').textContent = `Rp ${formatPrice(grandTotal)}`;

                document.getElementById('processCheckoutBtn').disabled = !selectedAddressId || isLoading;
            }

            async function processCheckout() {
                if (!selectedAddressId) {
                    alert('Pilih alamat pengiriman terlebih dahulu.');
                    return;
                }
                isLoading = true;
                updateTotals();

                const checkoutData = {
                    alamat_id: selectedAddressId,
                    metode_pengiriman: selectedShippingMethod,
                    biaya_kirim: shippingCost,
                    metode_pembayaran: selectedPaymentMethod,
                    items: cartItems.map(item => ({ product_id: item.product.id, jumlah: item.jumlah })),
                    catatan: document.getElementById('notes').value
                };

                // Tambahkan data Xendit jika bukan COD
                if (selectedPaymentType === 'xendit') {
                    checkoutData.payment_method = selectedXenditMethod;
                    checkoutData.payment_channel = selectedPaymentChannel;
                }

                try {
                    // Get auth token from auth.js
                    const token = getAuthToken ? getAuthToken() : null;

                    const headers = {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    };

                    // Add Authorization header if token exists
                    if (token) {
                        headers['Authorization'] = `Bearer ${token}`;
                    }

                    const response = await fetch('/api/orders/checkout', {
                        method: 'POST',
                        headers: headers,
                        body: JSON.stringify(checkoutData)
                    });

                    const data = await response.json();
                    if (response.ok && data.success) {
                        sessionStorage.removeItem('checkoutItems');

                        // Jika ada payment_url, redirect ke Xendit
                        if (data.data.payment_url && data.data.requires_payment) {
                            // Simpan order info untuk redirect nanti
                            sessionStorage.setItem('checkout_success', JSON.stringify({
                                order_id: data.data.order.id,
                                order_number: data.data.order.nomor_pesanan,
                                payment_id: data.data.payment_id
                            }));

                            // Redirect ke halaman pembayaran Xendit
                            window.location.href = data.data.payment_url;
                        } else {
                            // COD atau tidak perlu payment, tampilkan modal sukses
                            document.getElementById('successModal').classList.add('show');
                        }
                    } else {
                        alert(`Checkout gagal: ${data.message || 'Terjadi kesalahan'}`);
                    }
                } catch (error) {
                    console.error('Checkout error:', error);
                    alert('Checkout gagal. Silakan coba lagi.');
                } finally {
                    isLoading = false;
                    updateTotals();
                }
            }

            document.getElementById('processCheckoutBtn').addEventListener('click', processCheckout);

            // Init
            init();
        });
    </script>
</body>
</html>
