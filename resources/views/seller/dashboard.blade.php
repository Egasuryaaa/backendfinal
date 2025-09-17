@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0"><i class="fas fa-tachometer-alt text-primary me-2"></i>Dashboard Penjual</h2>
                <div class="text-muted">
                    <i class="fas fa-calendar me-1"></i>
                    <span id="currentDate"></span>
                </div>
            </div>
        </div>
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
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('seller.profile') }}">
                        <i class="fas fa-user me-2"></i>Profil
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

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-gradient-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-rocket me-2"></i>Aksi Cepat</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-lg-3 col-md-6">
                                <a href="/seller/products" class="btn btn-outline-primary w-100 py-3 text-start">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                            <i class="fas fa-fish text-primary fa-lg"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">Kelola Produk</div>
                                            <small class="text-muted">Tambah & edit produk</small>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <a href="/seller/orders" class="btn btn-outline-success w-100 py-3 text-start">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                                            <i class="fas fa-shopping-cart text-success fa-lg"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">Kelola Pesanan</div>
                                            <small class="text-muted">Lihat & proses pesanan</small>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <a href="/seller/locations" class="btn btn-outline-info w-100 py-3 text-start">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-info bg-opacity-10 rounded-circle p-3 me-3">
                                            <i class="fas fa-map-marker-alt text-info fa-lg"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">Lokasi Usaha</div>
                                            <small class="text-muted">Atur lokasi toko</small>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <a href="/profile" class="btn btn-outline-warning w-100 py-3 text-start">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                                            <i class="fas fa-user-edit text-warning fa-lg"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">Edit Profil</div>
                                            <small class="text-muted">Update informasi</small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
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
@endsection

@push('styles')
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
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="/js/auth.js"></script>
<script>
    let salesChart = null;

    document.addEventListener('DOMContentLoaded', function() {
        // Set current date
        document.getElementById('currentDate').textContent = new Date().toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

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

    function populateDashboard(data) {
        // Update stats
        document.getElementById('totalRevenue').textContent = data.formatted_total_revenue;
        document.getElementById('orderCount').textContent = data.order_count;
        document.getElementById('productCount').textContent = data.product_count;
        document.getElementById('avgRating').textContent = data.avg_rating;

        // Show alerts if needed
        const alertCards = document.getElementById('alertCards');
        let hasAlerts = false;

        if (data.out_of_stock_count > 0) {
            document.getElementById('outOfStockCount').textContent = data.out_of_stock_count;
            document.getElementById('outOfStockAlert').style.display = 'block';
            hasAlerts = true;
        }

        if (data.unreplied_reviews > 0) {
            document.getElementById('unrepliedReviews').textContent = data.unreplied_reviews;
            document.getElementById('unrepliedReviewsAlert').style.display = 'block';
            hasAlerts = true;
        }

        if (hasAlerts) {
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
        const ctx = document.getElementById('salesChart').getContext('2d');

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
@endpush
