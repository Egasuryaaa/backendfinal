<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pesanan Saya - IwakMart</title>

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

        .orders-list {
            display: grid;
            gap: 16px;
        }

        .order-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
            border: 1px solid #E5E5E5;
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            padding-bottom: 16px;
            border-bottom: 1px solid #E5E5E5;
        }

        .order-number {
            font-weight: 600;
            color: #1565C0;
        }

        .order-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .order-status.pending { background: #FFF3E0; color: #F57C00; }
        .order-status.processing { background: #E3F2FD; color: #1976D2; }
        .order-status.shipped { background: #E8F5E8; color: #388E3C; }
        .order-status.delivered { background: #E8F5E8; color: #2E7D32; }
        .order-status.cancelled { background: #FFEBEE; color: #D32F2F; }

        .order-items {
            margin-bottom: 16px;
        }

        .order-item {
            display: flex;
            gap: 12px;
            margin-bottom: 12px;
        }

        .item-image {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            object-fit: cover;
        }

        .item-info {
            flex: 1;
        }

        .item-name {
            font-weight: 500;
            margin-bottom: 4px;
        }

        .item-quantity {
            color: #666;
            font-size: 14px;
        }

        .item-price {
            color: #1565C0;
            font-weight: 600;
        }

        .order-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 16px;
            border-top: 1px solid #E5E5E5;
        }

        .order-total {
            font-weight: 700;
            font-size: 18px;
            color: #1565C0;
        }

        .order-date {
            color: #666;
            font-size: 14px;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .empty-state i {
            font-size: 64px;
            color: #DDD;
            margin-bottom: 16px;
        }

        .loading {
            text-align: center;
            padding: 40px;
        }

        .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #1565C0;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 16px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Cancel button styling */
        .btn {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
            border: 1px solid;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-sm {
            padding: 4px 8px;
            font-size: 11px;
        }

        .btn-outline-danger {
            color: #dc3545;
            border-color: #dc3545;
            background: transparent;
        }

        .btn-primary {
            color: white;
            border-color: #1976D2;
            background: #1976D2;
        }

        .btn-primary:hover {
            color: white;
            background: #0D47A1;
            border-color: #0D47A1;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(25, 118, 210, 0.3);
        }

        /* Order card hover effect */
        .order-card {
            transition: all 0.3s ease;
        }

        .order-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
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
                <h1 class="header-title">Pesanan Saya</h1>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div id="loadingState" class="loading">
                <div class="spinner"></div>
                <p>Memuat pesanan...</p>
            </div>

            <div id="ordersContainer" class="orders-list" style="display: none;">
                <!-- Orders will be loaded here -->
            </div>

            <div id="emptyState" class="empty-state" style="display: none;">
                <i class="fas fa-shopping-bag"></i>
                <h3>Belum Ada Pesanan</h3>
                <p>Anda belum memiliki pesanan. Yuk belanja sekarang!</p>
            </div>
        </div>
    </div>

    <script>
        let orders = [];

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            fetchOrders();
        });

        // Fetch orders from API
        async function fetchOrders() {
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

                const response = await fetch('/api/orders', {
                    method: 'GET',
                    headers: headers
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.success) {
                        orders = data.data?.data || [];
                        displayOrders();
                    } else {
                        showEmptyState();
                    }
                } else {
                    throw new Error('Gagal memuat pesanan');
                }
            } catch (error) {
                console.error('Error fetching orders:', error);
                showEmptyState();
            } finally {
                hideLoading();
            }
        }

        // Display orders
        function displayOrders() {
            const container = document.getElementById('ordersContainer');

            if (orders.length === 0) {
                showEmptyState();
                return;
            }

            container.innerHTML = orders.map(order => createOrderCard(order)).join('');
            container.style.display = 'grid';
        }

        // Create order card HTML
        function createOrderCard(order) {
            const statusClass = order.status.toLowerCase();
            const statusText = getStatusText(order.status);

            // Handle date properly - use the formatted date if available, otherwise format created_at
            let orderDate = 'Invalid Date';
            if (order.tanggal_pesan_formatted) {
                orderDate = order.tanggal_pesan_formatted;
            } else if (order.created_at) {
                try {
                    orderDate = new Date(order.created_at).toLocaleDateString('id-ID', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                } catch (e) {
                    orderDate = 'Invalid Date';
                }
            }

            // Handle order items properly
            let orderItemsHtml = '';
            if (order.order_items && order.order_items.length > 0) {
                orderItemsHtml = order.order_items.map(item => {
                    // Get image source - handle both array and string formats
                    let imageSrc = 'https://via.placeholder.com/60x60/BBDEFB/1976D2?text=No+Image';
                    if (item.product && item.product.gambar) {
                        if (Array.isArray(item.product.gambar) && item.product.gambar.length > 0) {
                            imageSrc = `/storage/${item.product.gambar[0]}`;
                        } else if (typeof item.product.gambar === 'string') {
                            imageSrc = `/storage/${item.product.gambar}`;
                        }
                    }

                    return `
                        <div class="order-item">
                            <img src="${imageSrc}"
                                 alt="${item.nama_produk || 'Product'}" class="item-image"
                                 onerror="this.src='https://via.placeholder.com/60x60/BBDEFB/1976D2?text=No+Image'">
                            <div class="item-info">
                                <div class="item-name">${item.nama_produk || 'Unknown Product'}</div>
                                <div class="item-quantity">${item.jumlah || 0} pcs</div>
                            </div>
                            <div class="item-price">Rp ${formatPrice(item.subtotal || 0)}</div>
                        </div>
                    `;
                }).join('');

                if (order.has_more_items) {
                    const moreCount = (order.items_count || 0) - 2;
                    orderItemsHtml += `<p style="text-align: center; color: #666; font-size: 14px; margin-top: 8px;">+${moreCount} item lainnya</p>`;
                }
            } else {
                orderItemsHtml = '<p style="text-align: center; color: #666;">Tidak ada item</p>';
            }

            return `
                <div class="order-card" onclick="viewOrderDetail(${order.id})" style="cursor: pointer;">
                    <div class="order-header">
                        <div class="order-number">#${order.nomor_pesanan || 'Unknown'}</div>
                        <div class="order-status ${statusClass}">${statusText}</div>
                    </div>
                    <div class="order-items">
                        ${orderItemsHtml}
                    </div>
                    <div class="order-footer">
                        <div class="order-date">${orderDate}</div>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div class="order-total">Total: Rp ${formatPrice(order.total || 0)}</div>
                            ${order.status === 'menunggu' || order.status === 'diproses' ?
                                `<button class="btn btn-sm btn-outline-danger" onclick="event.stopPropagation(); cancelOrder(${order.id}, '${order.nomor_pesanan}')">
                                    <i class="fas fa-times me-1"></i>Batal
                                </button>` : ''}
                            <button class="btn btn-sm btn-primary" onclick="event.stopPropagation(); viewOrderDetail(${order.id})">
                                <i class="fas fa-eye me-1"></i>Detail
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }

        // Get status text in Indonesian
        function getStatusText(status) {
            const statusMap = {
                'pending': 'Menunggu',
                'processing': 'Diproses',
                'shipped': 'Dikirim',
                'delivered': 'Selesai',
                'cancelled': 'Dibatalkan'
            };
            return statusMap[status] || status;
        }

        // Format price
        function formatPrice(price) {
            if (price === null || price === undefined || isNaN(price)) {
                return '0';
            }
            return new Intl.NumberFormat('id-ID').format(Math.round(price));
        }

        // Cancel order function
        async function cancelOrder(orderId, orderNumber) {
            if (!confirm(`Apakah Anda yakin ingin membatalkan pesanan #${orderNumber}?`)) {
                return;
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

                const response = await fetch(`/api/orders/${orderId}/cancel`, {
                    method: 'POST',
                    headers: headers
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    alert('Pesanan berhasil dibatalkan');
                    // Reload orders list
                    fetchOrders();
                } else {
                    alert(`Gagal membatalkan pesanan: ${data.message || 'Terjadi kesalahan'}`);
                }
            } catch (error) {
                console.error('Cancel order error:', error);
                alert('Terjadi kesalahan saat membatalkan pesanan');
            }
        }

        // View order detail
        function viewOrderDetail(orderId) {
            window.location.href = `/orders/${orderId}`;
        }

        window.cancelOrder = cancelOrder;
        window.viewOrderDetail = viewOrderDetail;

        // Show empty state
        function showEmptyState() {
            document.getElementById('emptyState').style.display = 'block';
        }

        // Hide loading
        function hideLoading() {
            document.getElementById('loadingState').style.display = 'none';
        }
    </script>
</body>
</html>
