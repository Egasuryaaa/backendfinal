<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Penjual - IwakMart</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">

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
            background: linear-gradient(135deg, #F0F8FF 0%, #E3F2FD 50%, #BBDEFB 100%);
            color: #333;
            line-height: 1.6;
            min-height: 100vh;
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

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
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
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-content">
            <button class="back-btn" onclick="window.location.href='/profile'">
                <i class="fas fa-arrow-left"></i>
            </button>
            <div class="header-icon">
                <i class="fas fa-tachometer-alt"></i>
            </div>
            <div class="header-info">
                <h1>Dashboard Penjual</h1>
            </div>
        </div>
    </div>

    <div class="container">
        <div style="padding: 20px;">
    <div class="row">

    </div>

    <!-- Seller Navigation Tabs -->
    <div class="row mb-4">
        <div class="col-12">
            <ul class="nav nav-tabs nav-fill">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('seller.dashboard') }}">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('seller.products') }}">
                        <i class="fas fa-fish me-2"></i>Produk
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('seller.orders') }}">
                        <i class="fas fa-shopping-cart me-2"></i>Pesanan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('seller.locations') }}">
                        <i class="fas fa-store me-2"></i>Info Toko
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Loading State -->
    <div id="loading" class="text-center py-5">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-3 text-muted">Memuat data dashboard...</p>
    </div>

    <!-- Dashboard Content -->
    <div id="dashboardContent" style="display: none;">
        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-money-bill-wave text-success fa-2x"></i>
                            </div>
                        </div>
                        <h6 class="text-muted mb-1">Total Pendapatan</h6>
                        <h4 class="text-success mb-0" id="totalRevenue">Rp 0</h4>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-shopping-bag text-primary fa-2x"></i>
                            </div>
                        </div>
                        <h6 class="text-muted mb-1">Total Pesanan</h6>
                        <h4 class="text-primary mb-0" id="orderCount">0</h4>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <div class="bg-info bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-box text-info fa-2x"></i>
                            </div>
                        </div>
                        <h6 class="text-muted mb-1">Total Produk</h6>
                        <h4 class="text-info mb-0" id="productCount">0</h4>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-star text-warning fa-2x"></i>
                            </div>
                        </div>
                        <h6 class="text-muted mb-1">Rating Rata-rata</h6>
                        <h4 class="text-warning mb-0" id="avgRating">0.0</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Cards -->
        <div class="row mb-4" id="alertCards" style="display: none;">
            <div class="col-md-6 mb-3" id="outOfStockAlert" style="display: none;">
                <div class="alert alert-warning d-flex align-items-center" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <div>
                        <strong>Perhatian!</strong> Ada <span id="outOfStockCount">0</span> produk yang stoknya habis.
                        <a href="{{ route('seller.products') }}" class="alert-link">Lihat Produk</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-3" id="unrepliedReviewsAlert" style="display: none;">
                <div class="alert alert-info d-flex align-items-center" role="alert">
                    <i class="fas fa-comment me-2"></i>
                    <div>
                        <strong>Info!</strong> Ada <span id="unrepliedReviews">0</span> ulasan yang belum dibalas.
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Sales Chart -->
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Penjualan 7 Hari Terakhir</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="salesChart" height="100"></canvas>
                    </div>
                </div>
            </div>

            <!-- Order Stats -->
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Status Pesanan</h5>
                    </div>
                    <div class="card-body">
                        <div id="orderStats"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Latest Orders -->
            <div class="col-12 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Pesanan Terbaru</h5>
                        <a href="{{ route('seller.orders') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-external-link-alt me-1"></i>Lihat Semua
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>No. Pesanan</th>
                                        <th>Produk</th>
                                        <th>Pelanggan</th>
                                        <th>Status</th>
                                        <th>Total</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody id="latestOrdersTable">
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">
                                            Belum ada pesanan
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Error State -->
    <div id="errorState" style="display: none;">
        <div class="alert alert-danger" role="alert">
            <h4 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Terjadi Kesalahan</h4>
            <p class="mb-0">Gagal memuat data dashboard. Silakan coba lagi.</p>
            <hr>
            <button class="btn btn-outline-danger" onclick="loadDashboard()">
                <i class="fas fa-redo me-1"></i>Coba Lagi
            </button>
        </div>
    </div>
</div>

<!-- Inline Styles -->
<style>
    .card {
        transition: transform 0.2s;
    }

    .card:hover {
        transform: translateY(-2px);
    }

    .bg-opacity-10 {
        background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }

    .status-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }

    .appointment-card {
        border-left: 4px solid var(--bs-primary);
        background: rgba(var(--bs-primary-rgb), 0.02);
    }
</style>

<!-- JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="/js/auth.js"></script>
<script>
    let salesChart = null;

    document.addEventListener('DOMContentLoaded', function() {
        loadDashboard();
    });

    function loadDashboard() {
        const loading = document.getElementById('loading');
        const content = document.getElementById('dashboardContent');
        const errorState = document.getElementById('errorState');

        loading.style.display = 'block';
        content.style.display = 'none';
        errorState.style.display = 'none';

        authenticatedFetch('/api/seller/dashboard')
        .then(response => {
            if (!response) {
                throw new Error('No response received');
            }
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                populateDashboard(data.data);
                loading.style.display = 'none';
                content.style.display = 'block';
            } else {
                throw new Error(data.message || 'Unknown error');
            }
        })
        .catch(error => {
            console.error('Error loading dashboard:', error);
            loading.style.display = 'none';
            errorState.style.display = 'block';
        });
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

    function populateDashboard(data) {
        // Update stats
        safeSetText('totalRevenue', data.formatted_total_revenue);
        safeSetText('orderCount', data.order_count);
        safeSetText('productCount', data.product_count);
        safeSetText('avgRating', data.avg_rating);

        // Show alerts if needed
        const alertCards = safeGetElement('alertCards');
        let hasAlerts = false;

        if (data.out_of_stock_count > 0) {
            safeSetText('outOfStockCount', data.out_of_stock_count);
            safeSetDisplay('outOfStockAlert', 'block');
            hasAlerts = true;
        }

        if (data.unreplied_reviews > 0) {
            safeSetText('unrepliedReviews', data.unreplied_reviews);
            safeSetDisplay('unrepliedReviewsAlert', 'block');
            hasAlerts = true;
        }

        if (hasAlerts && alertCards) {
            alertCards.style.display = 'flex';
        }

        // Update sales chart
        updateSalesChart(data.sales_data);

        // Update order stats
        updateOrderStats(data.order_stats);

        // Update latest orders
        updateLatestOrders(data.latest_orders);
    }

    function updateSalesChart(salesData) {
        const ctx = safeGetElement('salesChart')?.getContext('2d');
        if (!ctx) {
            console.error('Sales chart canvas not found');
            return;
        }

        if (salesChart) {
            salesChart.destroy();
        }

        salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: salesData.map(item => item.date),
                datasets: [{
                    label: 'Penjualan (Rp)',
                    data: salesData.map(item => item.sales),
                    borderColor: '#1565C0',
                    backgroundColor: 'rgba(21, 101, 192, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Penjualan: Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    }

    function updateOrderStats(orderStats) {
        const container = document.getElementById('orderStats');
        const statuses = {
            'menunggu': { label: 'Menunggu', color: 'warning' },
            'dibayar': { label: 'Dibayar', color: 'info' },
            'diproses': { label: 'Diproses', color: 'primary' },
            'dikirim': { label: 'Dikirim', color: 'dark' },
            'selesai': { label: 'Selesai', color: 'success' },
            'dibatalkan': { label: 'Dibatalkan', color: 'danger' }
        };

        let html = '';
        for (const [status, config] of Object.entries(statuses)) {
            const count = orderStats[status] || 0;
            html += `
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge bg-${config.color} status-badge">${config.label}</span>
                    <span class="fw-bold">${count}</span>
                </div>
            `;
        }

        container.innerHTML = html;
    }

    function updateLatestOrders(orders) {
        const tbody = document.getElementById('latestOrdersTable');

        if (orders.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">
                        Belum ada pesanan
                    </td>
                </tr>
            `;
            return;
        }

        let html = '';
        orders.forEach(order => {
            const statusColors = {
                'menunggu': 'warning',
                'dibayar': 'info',
                'diproses': 'primary',
                'dikirim': 'dark',
                'selesai': 'success',
                'dibatalkan': 'danger'
            };

            const statusColor = statusColors[order.status] || 'secondary';

            html += `
                <tr>
                    <td><small class="fw-bold">${order.order_number}</small></td>
                    <td>
                        <div class="fw-bold">${order.product_name}</div>
                        <small class="text-muted">Qty: ${order.quantity}</small>
                    </td>
                    <td>${order.customer_name}</td>
                    <td><span class="badge bg-${statusColor} status-badge">${order.status_text}</span></td>
                    <td class="fw-bold">${order.formatted_subtotal}</td>
                    <td><small>${order.created_at}</small></td>
                </tr>
            `;
        });

        tbody.innerHTML = html;
    }

    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
        return '';
    }

</script>

</body>
</html>
