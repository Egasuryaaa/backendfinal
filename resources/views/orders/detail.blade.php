<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detail Pesanan - IwakMart</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Auth Script -->
    <script src="/js/auth.js"></script>

    <style>
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
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
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
        }

        .back-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .header-title {
            font-size: 24px;
            font-weight: 700;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 20px;
        }

        .detail-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
            border: 1px solid #E5E5E5;
            margin-bottom: 20px;
        }

        .detail-card .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid #E5E5E5;
        }

        .order-number {
            font-size: 20px;
            font-weight: 700;
            color: #1565C0;
        }

        .order-status {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            text-transform: capitalize;
        }

        .order-status.menunggu { background: #FFF3E0; color: #F57C00; }
        .order-status.diproses { background: #E3F2FD; color: #1976D2; }
        .order-status.dikirim { background: #E8F5E8; color: #388E3C; }
        .order-status.selesai { background: #E8F5E8; color: #2E7D32; }
        .order-status.dibatalkan { background: #FFEBEE; color: #D32F2F; }

        .order-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .info-item {
            padding: 12px;
            background: #F8F9FA;
            border-radius: 8px;
        }

        .info-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 14px;
            font-weight: 500;
            color: #333;
        }

        /* Order Items */
        .order-items {
            margin-bottom: 24px;
        }

        .order-item {
            display: flex;
            gap: 16px;
            padding: 16px;
            border: 1px solid #E5E5E5;
            border-radius: 12px;
            margin-bottom: 12px;
            background: #FAFAFA;
        }

        .item-image {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            object-fit: cover;
        }

        .item-info {
            flex: 1;
        }

        .item-name {
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 4px;
            color: #333;
        }

        .item-details {
            color: #666;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .item-price {
            font-weight: 600;
            color: #1565C0;
        }

        /* Address Info */
        .address-info {
            background: #F8F9FA;
            padding: 16px;
            border-radius: 8px;
            border-left: 4px solid #4CAF50;
        }

        .address-name {
            font-weight: 600;
            margin-bottom: 4px;
        }

        .address-details {
            color: #666;
            font-size: 14px;
        }

        /* Price Summary */
        .price-summary {
            border-top: 2px solid #E5E5E5;
            padding-top: 16px;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
        }

        .price-row.total {
            font-weight: 700;
            font-size: 18px;
            color: #1565C0;
            border-top: 1px solid #E5E5E5;
            margin-top: 8px;
            padding-top: 8px;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 12px;
            margin-top: 20px;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: #1565C0;
            color: white;
        }

        .btn-primary:hover {
            background: #0D47A1;
            transform: translateY(-1px);
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
            transform: translateY(-1px);
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-success:hover {
            background: #218838;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-1px);
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
            border-top: 3px solid #1565C0;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .error-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .error-state i {
            font-size: 64px;
            color: #DDD;
            margin-bottom: 16px;
        }

        /* Section Headers */
        .section-header {
            font-size: 18px;
            font-weight: 700;
            color: #333;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-icon {
            color: #1565C0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .order-info {
                grid-template-columns: 1fr;
            }

            .order-item {
                flex-direction: column;
                text-align: center;
            }

            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-content">
                <button class="back-btn" onclick="history.back()">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <h1 class="header-title">Detail Pesanan</h1>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div id="loadingState" class="loading">
                <div class="spinner"></div>
            </div>

            <div id="errorState" class="error-state" style="display: none;">
                <i class="fas fa-exclamation-triangle"></i>
                <h3>Pesanan Tidak Ditemukan</h3>
                <p>Pesanan yang Anda cari tidak ditemukan atau Anda tidak memiliki akses ke pesanan ini.</p>
                <button class="btn btn-primary" onclick="window.location.href='/orders'">
                    <i class="fas fa-arrow-left"></i>
                    Kembali ke Daftar Pesanan
                </button>
            </div>

            <div id="orderDetail" style="display: none;">
                <!-- Order Header -->
                <div class="detail-card">
                    <div class="card-header">
                        <div class="order-number" id="orderNumber">#-</div>
                        <div class="order-status" id="orderStatus">-</div>
                    </div>

                    <div class="order-info">
                        <div class="info-item">
                            <div class="info-label">Tanggal Pesanan</div>
                            <div class="info-value" id="orderDate">-</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Metode Pembayaran</div>
                            <div class="info-value" id="paymentMethod">-</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Status Pembayaran</div>
                            <div class="info-value" id="paymentStatus">-</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Metode Pengiriman</div>
                            <div class="info-value" id="shippingMethod">-</div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons" id="actionButtons">
                        <!-- Buttons will be dynamically added based on order status -->
                    </div>
                </div>

                <!-- Address Info -->
                <div class="detail-card">
                    <div class="section-header">
                        <i class="fas fa-map-marker-alt section-icon"></i>
                        Alamat Pengiriman
                    </div>
                    <div class="address-info" id="addressInfo">
                        <!-- Address details will be loaded here -->
                    </div>
                </div>

                <!-- Order Items -->
                <div class="detail-card">
                    <div class="section-header">
                        <i class="fas fa-shopping-bag section-icon"></i>
                        Item Pesanan
                    </div>
                    <div class="order-items" id="orderItems">
                        <!-- Order items will be loaded here -->
                    </div>
                </div>

                <!-- Price Summary -->
                <div class="detail-card">
                    <div class="section-header">
                        <i class="fas fa-receipt section-icon"></i>
                        Ringkasan Pembayaran
                    </div>
                    <div class="price-summary">
                        <div class="price-row">
                            <span>Subtotal</span>
                            <span id="subtotal">Rp 0</span>
                        </div>
                        <div class="price-row">
                            <span>Ongkos Kirim</span>
                            <span id="shippingCost">Rp 0</span>
                        </div>
                        <div class="price-row">
                            <span>Pajak</span>
                            <span id="tax">Rp 0</span>
                        </div>
                        <div class="price-row total">
                            <span>Total</span>
                            <span id="totalPrice">Rp 0</span>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="detail-card" id="notesSection" style="display: none;">
                    <div class="section-header">
                        <i class="fas fa-sticky-note section-icon"></i>
                        Catatan
                    </div>
                    <p id="orderNotes" class="info-value"></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        let order = null;
        const orderId = {{ $orderId }};

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            fetchOrderDetail();
        });

        // Fetch order detail
        async function fetchOrderDetail() {
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

                const response = await fetch(`/api/orders/${orderId}`, {
                    method: 'GET',
                    headers: headers
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.success) {
                        order = data.data;
                        displayOrderDetail();
                    } else {
                        showError();
                    }
                } else {
                    throw new Error('Gagal memuat detail pesanan');
                }
            } catch (error) {
                console.error('Error fetching order detail:', error);
                showError();
            } finally {
                hideLoading();
            }
        }

        // Display order detail
        function displayOrderDetail() {
            if (!order) return;

            // Order header
            document.getElementById('orderNumber').textContent = `#${order.nomor_pesanan || '-'}`;

            const statusElement = document.getElementById('orderStatus');
            statusElement.textContent = order.status_label || order.status || '-';
            statusElement.className = `order-status ${order.status || ''}`;

            // Order info
            document.getElementById('orderDate').textContent = order.tanggal_pesan_formatted || '-';
            document.getElementById('paymentMethod').textContent = order.metode_pembayaran_label || order.metode_pembayaran || '-';
            document.getElementById('paymentStatus').textContent = order.status_pembayaran_label || order.status_pembayaran || '-';
            document.getElementById('shippingMethod').textContent = order.metode_pengiriman || '-';

            // Address info
            if (order.address) {
                const addressHtml = `
                    <div class="address-name">${order.address.nama_penerima || '-'}</div>
                    <div class="address-details">${order.address.telepon || '-'}</div>
                    <div class="address-details">
                        ${order.address.alamat_lengkap || '-'}<br>
                        ${order.address.kecamatan || ''}, ${order.address.kota || ''}<br>
                        ${order.address.provinsi || ''} ${order.address.kode_pos || ''}
                    </div>
                `;
                document.getElementById('addressInfo').innerHTML = addressHtml;
            }

            // Order items
            fetchOrderItems();

            // Price summary
            document.getElementById('subtotal').textContent = order.subtotal_formatted || 'Rp 0';
            document.getElementById('shippingCost').textContent = order.biaya_kirim_formatted || 'Rp 0';
            document.getElementById('tax').textContent = order.pajak_formatted || 'Rp 0';
            document.getElementById('totalPrice').textContent = order.total_formatted || 'Rp 0';

            // Notes
            if (order.catatan) {
                document.getElementById('orderNotes').textContent = order.catatan;
                document.getElementById('notesSection').style.display = 'block';
            }

            // Action buttons
            renderActionButtons();

            // Show order detail
            document.getElementById('orderDetail').style.display = 'block';
        }

        // Fetch order items
        async function fetchOrderItems() {
            try {
                const token = getAuthToken ? getAuthToken() : null;

                const headers = {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                };

                if (token) {
                    headers['Authorization'] = `Bearer ${token}`;
                }

                const response = await fetch(`/api/orders/${orderId}/items`, {
                    method: 'GET',
                    headers: headers
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.success) {
                        renderOrderItems(data.data);
                    }
                }
            } catch (error) {
                console.error('Error fetching order items:', error);
            }
        }

        // Render order items
        function renderOrderItems(items) {
            const container = document.getElementById('orderItems');

            if (!items || items.length === 0) {
                container.innerHTML = '<p class="text-center text-muted">Tidak ada item dalam pesanan ini.</p>';
                return;
            }

            const itemsHtml = items.map(item => {
                // Get image source
                let imageSrc = 'https://via.placeholder.com/80x80/BBDEFB/1976D2?text=No+Image';
                if (item.produk && item.produk.gambar) {
                    if (Array.isArray(item.produk.gambar) && item.produk.gambar.length > 0) {
                        imageSrc = `/storage/${item.produk.gambar[0]}`;
                    } else if (typeof item.produk.gambar === 'string') {
                        imageSrc = `/storage/${item.produk.gambar}`;
                    }
                }

                return `
                    <div class="order-item">
                        <img src="${imageSrc}"
                             alt="${item.nama_produk || 'Product'}" class="item-image"
                             onerror="this.src='https://via.placeholder.com/80x80/BBDEFB/1976D2?text=No+Image'">
                        <div class="item-info">
                            <div class="item-name">${item.nama_produk || 'Unknown Product'}</div>
                            <div class="item-details">
                                Jumlah: ${item.jumlah || 0} pcs<br>
                                Harga: ${item.harga_formatted || 'Rp 0'}/pcs
                            </div>
                            <div class="item-price">Subtotal: ${item.subtotal_formatted || 'Rp 0'}</div>
                        </div>
                    </div>
                `;
            }).join('');

            container.innerHTML = itemsHtml;
        }

        // Render action buttons based on order status
        function renderActionButtons() {
            const container = document.getElementById('actionButtons');
            let buttonsHtml = '';

            if (!order) return;

            // Cancel button for orders that can be cancelled
            if (order.can_cancel) {
                buttonsHtml += `
                    <button class="btn btn-danger" onclick="cancelOrder()">
                        <i class="fas fa-times"></i>
                        Batalkan Pesanan
                    </button>
                `;
            }

            // Complete button for orders that can be completed
            if (order.can_complete) {
                buttonsHtml += `
                    <button class="btn btn-success" onclick="completeOrder()">
                        <i class="fas fa-check"></i>
                        Pesanan Diterima
                    </button>
                `;
            }

            // Always show back to orders button
            buttonsHtml += `
                <button class="btn btn-secondary" onclick="window.location.href='/orders'">
                    <i class="fas fa-list"></i>
                    Kembali ke Daftar Pesanan
                </button>
            `;

            container.innerHTML = buttonsHtml;
        }

        // Cancel order
        async function cancelOrder() {
            if (!confirm(`Apakah Anda yakin ingin membatalkan pesanan #${order.nomor_pesanan}?`)) {
                return;
            }

            try {
                const token = getAuthToken ? getAuthToken() : null;

                const headers = {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };

                if (token) {
                    headers['Authorization'] = `Bearer ${token}`;
                }

                const response = await fetch(`/api/orders/${orderId}/cancel`, {
                    method: 'POST',
                    headers: headers
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    alert('Pesanan berhasil dibatalkan');
                    // Reload page to show updated status
                    window.location.reload();
                } else {
                    alert(`Gagal membatalkan pesanan: ${data.message || 'Terjadi kesalahan'}`);
                }
            } catch (error) {
                console.error('Cancel order error:', error);
                alert('Terjadi kesalahan saat membatalkan pesanan');
            }
        }

        // Complete order (mark as received)
        async function completeOrder() {
            if (!confirm(`Konfirmasi bahwa Anda telah menerima pesanan #${order.nomor_pesanan}?`)) {
                return;
            }

            try {
                const token = getAuthToken ? getAuthToken() : null;

                const headers = {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };

                if (token) {
                    headers['Authorization'] = `Bearer ${token}`;
                }

                const response = await fetch(`/api/orders/${orderId}/complete`, {
                    method: 'POST',
                    headers: headers
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    alert('Pesanan berhasil diselesaikan. Terima kasih!');
                    // Reload page to show updated status
                    window.location.reload();
                } else {
                    alert(`Gagal menyelesaikan pesanan: ${data.message || 'Terjadi kesalahan'}`);
                }
            } catch (error) {
                console.error('Complete order error:', error);
                alert('Terjadi kesalahan saat menyelesaikan pesanan');
            }
        }

        // Show error state
        function showError() {
            document.getElementById('errorState').style.display = 'block';
        }

        // Hide loading
        function hideLoading() {
            document.getElementById('loadingState').style.display = 'none';
        }
    </script>
</body>
</html>
