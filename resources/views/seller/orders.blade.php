<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola Pesanan - IwakMart</title>

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
            <button class="back-btn" onclick="window.location.href='/seller/dashboard'">
                <i class="fas fa-arrow-left"></i>
            </button>
            <div class="header-icon">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <div class="header-info">
                <h1>Kelola Pesanan</h1>
            </div>
        </div>
    </div>

<div class="container">


    <!-- Seller Navigation Tabs -->
    <div class="row mb-4">
        <div class="col-12">
            <ul class="nav nav-tabs nav-fill">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('seller.dashboard') }}">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('seller.products') }}">
                        <i class="fas fa-fish me-2"></i>Produk
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('seller.orders') }}">
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

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-2">
                            <i class="fas fa-shopping-cart text-primary fa-lg"></i>
                        </div>
                    </div>
                    <h6 class="text-muted mb-1 small">Total</h6>
                    <h5 class="text-primary mb-0" id="totalOrders">0</h5>
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="bg-warning bg-opacity-10 rounded-circle p-2">
                            <i class="fas fa-clock text-warning fa-lg"></i>
                        </div>
                    </div>
                    <h6 class="text-muted mb-1 small">Menunggu</h6>
                    <h5 class="text-warning mb-0" id="pendingOrders">0</h5>
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="bg-info bg-opacity-10 rounded-circle p-2">
                            <i class="fas fa-credit-card text-info fa-lg"></i>
                        </div>
                    </div>
                    <h6 class="text-muted mb-1 small">Dibayar</h6>
                    <h5 class="text-info mb-0" id="paidOrders">0</h5>
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="bg-purple bg-opacity-10 rounded-circle p-2">
                            <i class="fas fa-cogs text-purple fa-lg"></i>
                        </div>
                    </div>
                    <h6 class="text-muted mb-1 small">Diproses</h6>
                    <h5 class="text-purple mb-0" id="processingOrders">0</h5>
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="bg-dark bg-opacity-10 rounded-circle p-2">
                            <i class="fas fa-truck text-dark fa-lg"></i>
                        </div>
                    </div>
                    <h6 class="text-muted mb-1 small">Dikirim</h6>
                    <h5 class="text-dark mb-0" id="shippedOrders">0</h5>
                </div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="bg-success bg-opacity-10 rounded-circle p-2">
                            <i class="fas fa-check-circle text-success fa-lg"></i>
                        </div>
                    </div>
                    <h6 class="text-muted mb-1 small">Selesai</h6>
                    <h5 class="text-success mb-0" id="completedOrders">0</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter and Search -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-4 mb-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" id="searchOrders" placeholder="Cari nomor pesanan atau nama pembeli...">
                    </div>
                </div>
                <div class="col-lg-3 mb-3">
                    <select class="form-select" id="filterStatus">
                        <option value="">Semua Status</option>
                        <option value="menunggu">Menunggu Pembayaran</option>
                        <option value="dibayar">Dibayar</option>
                        <option value="diproses">Sedang Diproses</option>
                        <option value="dikirim">Dikirim</option>
                        <option value="selesai">Selesai</option>
                        <option value="dibatalkan">Dibatalkan</option>
                    </select>
                </div>
                <div class="col-lg-3 mb-3">
                    <input type="date" class="form-control" id="filterDate" title="Filter berdasarkan tanggal">
                </div>
                <div class="col-lg-2 mb-3">
                    <button class="btn btn-outline-secondary w-100" onclick="clearFilters()">
                        <i class="fas fa-times me-1"></i>Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading State -->
    <div id="loading" class="text-center py-5">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-3 text-muted">Memuat pesanan...</p>
    </div>

    <!-- Orders List -->
    <div id="ordersContainer" style="display: none;">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Daftar Pesanan</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No. Pesanan</th>
                                <th>Pembeli</th>
                                <th>Produk</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="ordersTableBody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <nav aria-label="Orders pagination" class="mt-4">
            <ul class="pagination justify-content-center" id="ordersPagination">
            </ul>
        </nav>
    </div>

    <!-- Empty State -->
    <div id="emptyState" style="display: none;">
        <div class="text-center py-5">
            <i class="fas fa-clipboard-list fa-5x text-muted mb-3"></i>
            <h4 class="text-muted">Belum Ada Pesanan</h4>
            <p class="text-muted mb-4">Pesanan dari pembeli akan muncul di sini</p>
            <a href="/seller/products" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>Kelola Produk
            </a>
        </div>
    </div>

    <!-- Error State -->
    <div id="errorState" style="display: none;">
        <div class="alert alert-danger" role="alert">
            <h4 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Terjadi Kesalahan</h4>
            <p class="mb-0">Gagal memuat data pesanan. Silakan coba lagi.</p>
            <hr>
            <button class="btn btn-outline-danger" onclick="loadOrders()">
                <i class="fas fa-redo me-1"></i>Coba Lagi
            </button>
        </div>
    </div>
</div>

<!-- Order Detail Modal -->
<div class="modal fade" id="orderDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderDetailTitle">Detail Pesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="orderDetailBody">
                <!-- Order detail content will be loaded here -->
            </div>
            <div class="modal-footer" id="orderDetailFooter">
                <!-- Action buttons will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Status Pesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="updateStatusForm">
                    <input type="hidden" id="updateOrderId">

                    <div class="mb-3">
                        <label for="newStatus" class="form-label">Status Baru</label>
                        <select class="form-select" id="newStatus" required>
                            <option value="">Pilih Status</option>
                            <option value="diproses">Sedang Diproses</option>
                            <option value="dikirim">Dikirim</option>
                            <option value="selesai">Selesai</option>
                            <option value="dibatalkan">Dibatalkan</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="statusNote" class="form-label">Catatan (opsional)</label>
                        <textarea class="form-control" id="statusNote" rows="3" placeholder="Tambahkan catatan tentang perubahan status..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="updateOrderStatus()">
                    <i class="fas fa-save me-1"></i>Update Status
                </button>
            </div>
        </div>
    </div>
    </div>
</div>

<!-- Payment Verification Modal -->
<div class="modal fade" id="paymentVerificationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Verifikasi Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="mb-3">Informasi Pesanan</h6>
                        <div class="mb-2"><strong>No. Pesanan:</strong> <span id="verifyOrderNumber"></span></div>
                        <div class="mb-2"><strong>Total:</strong> <span id="verifyOrderTotal"></span></div>
                        <div class="mb-2"><strong>Status Pembayaran:</strong> <span id="verifyPaymentStatus"></span></div>
                        <div class="mb-3"><strong>Diupload pada:</strong> <span id="verifyUploadDate"></span></div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="mb-3">Bukti Pembayaran</h6>
                        <div class="text-center">
                            <img id="verifyPaymentImage" src="" alt="Bukti Pembayaran" class="img-fluid border rounded" style="max-height: 300px;">
                            <div class="mt-2">
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="openPaymentProofFull()">
                                    <i class="fas fa-expand"></i> Lihat Ukuran Penuh
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Perhatian:</strong> Pastikan bukti pembayaran sesuai dengan jumlah total pesanan sebelum memverifikasi.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success" id="confirmVerifyPayment">
                    <i class="fas fa-check me-1"></i>Verifikasi Pembayaran
                </button>
            </div>
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

    .order-status {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }

    .btn-action {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .text-purple {
        color: #6f42c1 !important;
    }

    .bg-purple {
        background-color: #6f42c1 !important;
    }

    .order-item {
        border-left: 4px solid #dee2e6;
        padding: 1rem;
        margin-bottom: 0.5rem;
        background: #f8f9fa;
    }

    .order-item.pending { border-left-color: #ffc107; }
    .order-item.paid { border-left-color: #0dcaf0; }
    .order-item.processing { border-left-color: #6f42c1; }
    .order-item.shipped { border-left-color: #212529; }
    .order-item.completed { border-left-color: #198754; }
<!-- Inline Styles -->
<style>
    .card {
        transition: transform 0.2s;
    }

    .card:hover {
        transform: translateY(-2px);
    }

    .order-number {
        font-family: 'Courier New', monospace;
        font-weight: bold;
        color: #007bff;
    }

    .order-status {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }

    .btn-action {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .order-item {
        border-left: 4px solid #6c757d;
        transition: all 0.3s ease;
    }

    .order-item:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .order-item.pending { border-left-color: #ffc107; }
    .order-item.paid { border-left-color: #17a2b8; }
    .order-item.processing { border-left-color: #007bff; }
    .order-item.shipped { border-left-color: #6f42c1; }
    .order-item.completed { border-left-color: #28a745; }
    .order-item.cancelled { border-left-color: #dc3545; }

    .nav-tabs .nav-link {
        border: none;
        color: #6c757d;
        padding: 1rem 1.5rem;
        transition: all 0.3s ease;
    }

    .nav-tabs .nav-link:hover {
        border: none;
        color: #0d6efd;
        background-color: rgba(13, 110, 253, 0.1);
    }

    .nav-tabs .nav-link.active {
        color: #0d6efd;
        background-color: rgba(13, 110, 253, 0.1);
        border: none;
        border-bottom: 3px solid #0d6efd;
    }
</style>

<!-- JavaScript -->
<script src="/js/auth.js"></script>
<script>
    let orders = [];
    let currentPage = 1;
    let perPage = 10;
    let totalPages = 1;

    document.addEventListener('DOMContentLoaded', function() {
        loadOrders();

        // Search functionality
        document.getElementById('searchOrders').addEventListener('input', function() {
            currentPage = 1;
            loadOrders();
        });

        // Filter functionality
        document.getElementById('filterStatus').addEventListener('change', function() {
            currentPage = 1;
            loadOrders();
        });

        document.getElementById('filterDate').addEventListener('change', function() {
            currentPage = 1;
            loadOrders();
        });
    });

    function loadOrders() {
        const loading = document.getElementById('loading');
        const container = document.getElementById('ordersContainer');
        const emptyState = document.getElementById('emptyState');
        const errorState = document.getElementById('errorState');

        loading.style.display = 'block';
        container.style.display = 'none';
        emptyState.style.display = 'none';
        errorState.style.display = 'none';

        const search = document.getElementById('searchOrders').value;
        const status = document.getElementById('filterStatus').value;
        const date = document.getElementById('filterDate').value;

        let url = `/api/seller/orders?page=${currentPage}&per_page=${perPage}`;
        if (search) url += `&search=${encodeURIComponent(search)}`;
        if (status) url += `&status=${status}`;
        if (date) url += `&date=${date}`;

        authenticatedFetch(url)
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
            console.log('Orders API response:', data);

            if (data.success) {
                // Handle pagination response structure
                if (data.data && data.data.data) {
                    orders = Array.isArray(data.data.data) ? data.data.data : [];
                    totalPages = data.data.last_page || 1;
                    console.log('Using paginated structure, orders:', orders);
                } else {
                    orders = Array.isArray(data.data) ? data.data : [];
                    totalPages = data.meta?.last_page || 1;
                    console.log('Using direct structure, orders:', orders);
                }

                updateOrderStats();
                displayOrders();
                loading.style.display = 'none';

                if (orders.length === 0) {
                    emptyState.style.display = 'block';
                } else {
                    container.style.display = 'block';
                    updatePagination();
                }
            } else {
                throw new Error(data.message || 'Unknown error');
            }
        })
        .catch(error => {
            console.error('Error loading orders:', error);
            loading.style.display = 'none';
            errorState.style.display = 'block';
        });
    }

    function updateOrderStats() {
        // Ensure orders is always an array before filtering
        if (!Array.isArray(orders)) {
            console.error('Orders is not an array:', orders);
            orders = [];
        }

        const total = orders.length;
        const pending = orders.filter(o => o.status === 'menunggu').length;
        const paid = orders.filter(o => o.status === 'dibayar').length;
        const processing = orders.filter(o => o.status === 'diproses').length;
        const shipped = orders.filter(o => o.status === 'dikirim').length;
        const completed = orders.filter(o => o.status === 'selesai').length;

        document.getElementById('totalOrders').textContent = total;
        document.getElementById('pendingOrders').textContent = pending;
        document.getElementById('paidOrders').textContent = paid;
        document.getElementById('processingOrders').textContent = processing;
        document.getElementById('shippedOrders').textContent = shipped;
        document.getElementById('completedOrders').textContent = completed;
    }

    function displayOrders() {
        const tbody = document.getElementById('ordersTableBody');

        if (orders.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">
                        Tidak ada pesanan ditemukan
                    </td>
                </tr>
            `;
            return;
        }

        let html = '';
        orders.forEach(order => {
            const statusColors = {
                'menunggu': 'warning',
                'menunggu_pembayaran': 'warning',
                'menunggu_verifikasi': 'warning',
                'dibayar': 'info',
                'diproses': 'purple',
                'dikirim': 'dark',
                'selesai': 'success',
                'dibatalkan': 'danger'
            };

            const statusTexts = {
                'menunggu': 'Menunggu Pembayaran',
                'menunggu_pembayaran': 'Menunggu Pembayaran',
                'menunggu_verifikasi': 'Menunggu Verifikasi',
                'dibayar': 'Dibayar',
                'diproses': 'Sedang Diproses',
                'dikirim': 'Dikirim',
                'selesai': 'Selesai',
                'dibatalkan': 'Dibatalkan'
            };

            const statusColor = statusColors[order.status] || 'secondary';
            const statusText = statusTexts[order.status] || order.status;

            // Get first product or item count
            const itemsText = order.items && order.items.length > 0
                ? (order.items.length === 1
                    ? order.items[0].nama_produk
                    : `${order.items[0].nama_produk} +${order.items.length - 1} lainnya`)
                : `${order.total_items || 1} item`;

            html += `
                <tr>
                    <td>
                        <div class="fw-bold">#${order.nomor_pesanan || order.id}</div>
                        <small class="text-muted">${order.metode_pembayaran || '-'}</small>
                    </td>
                    <td>
                        <div class="fw-bold">${order.user?.name || order.pembeli?.name || '-'}</div>
                        <small class="text-muted">${order.user?.email || order.pembeli?.email || '-'}</small>
                    </td>
                    <td>
                        <div class="fw-bold">${itemsText}</div>
                        <small class="text-muted">${order.total_items || 1} item</small>
                    </td>
                    <td class="fw-bold">Rp ${(order.total || order.total_amount || 0).toLocaleString('id-ID')}</td>
                    <td>
                        <span class="badge bg-${statusColor} order-status">${statusText}</span>
                    </td>
                    <td>
                        <div>${formatDate(order.created_at)}</div>
                        <small class="text-muted">${formatTime(order.created_at)}</small>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-primary btn-action"
                                    onclick="showOrderDetail(${order.id})" title="Detail">
                                <i class="fas fa-eye"></i>
                            </button>
                            ${order.payment_proof ? `
                                <button type="button" class="btn btn-outline-info btn-action"
                                        onclick="viewPaymentProof('${order.payment_proof}')" title="Lihat Bukti Pembayaran">
                                    <i class="fas fa-image"></i>
                                </button>
                            ` : ''}
                            ${order.payment_proof && (order.metode_pembayaran === 'manual' || ['menunggu', 'menunggu_verifikasi', 'menunggu_pembayaran'].includes(order.status_pembayaran)) ? `
                                <button type="button" class="btn btn-outline-warning btn-action"
                                        onclick="showPaymentVerificationModal(${order.id})" title="Verifikasi Pembayaran">
                                    <i class="fas fa-check-circle"></i>
                                </button>
                            ` : ''}
                            ${order.status !== 'selesai' && order.status !== 'dibatalkan' ? `
                                <button type="button" class="btn btn-outline-success btn-action"
                                        onclick="showUpdateStatusModal(${order.id})" title="Update Status">
                                    <i class="fas fa-edit"></i>
                                </button>
                            ` : ''}
                        </div>
                    </td>
                </tr>
            `;
        });

        tbody.innerHTML = html;
    }

    function updatePagination() {
        const pagination = document.getElementById('ordersPagination');
        let html = '';

        // Previous button
        html += `
            <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="changePage(${currentPage - 1})">
                    <i class="fas fa-chevron-left"></i>
                </a>
            </li>
        `;

        // Page numbers
        for (let i = 1; i <= totalPages; i++) {
            if (i === currentPage || i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
                html += `
                    <li class="page-item ${i === currentPage ? 'active' : ''}">
                        <a class="page-link" href="#" onclick="changePage(${i})">${i}</a>
                    </li>
                `;
            } else if (i === currentPage - 2 || i === currentPage + 2) {
                html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
        }

        // Next button
        html += `
            <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="changePage(${currentPage + 1})">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
        `;

        pagination.innerHTML = html;
    }

    function changePage(page) {
        if (page >= 1 && page <= totalPages) {
            currentPage = page;
            loadOrders();
        }
    }

    function clearFilters() {
        document.getElementById('searchOrders').value = '';
        document.getElementById('filterStatus').value = '';
        document.getElementById('filterDate').value = '';
        currentPage = 1;
        loadOrders();
    }

    // View payment proof function
    function viewPaymentProof(paymentProofPath) {
        if (!paymentProofPath) {
            showError('Bukti pembayaran tidak ditemukan');
            return;
        }

        // Open payment proof in new window
        const url = `/storage/${paymentProofPath}`;
        const newWindow = window.open(url, '_blank');

        if (!newWindow) {
            // If popup blocked, show alternative
            showSuccess('Bukti pembayaran akan dibuka di tab baru. Pastikan popup tidak diblokir.');
            setTimeout(() => {
                location.href = url;
            }, 1000);
        }
    }

    function showOrderDetail(orderId) {
        const order = orders.find(o => o.id === orderId);
        if (!order) return;

        const modal = document.getElementById('orderDetailModal');
        const title = document.getElementById('orderDetailTitle');
        const body = document.getElementById('orderDetailBody');
        const footer = document.getElementById('orderDetailFooter');

        title.textContent = `Detail Pesanan #${order.nomor_pesanan || order.id}`;

        // Build order detail content
        body.innerHTML = `
            <div class="order-header mb-4 p-3 bg-light rounded">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="mb-2">Informasi Pesanan</h6>
                        <div class="mb-1"><strong>No. Pesanan:</strong> #${order.nomor_pesanan || order.id}</div>
                        <div class="mb-1"><strong>Tanggal:</strong> ${formatDate(order.created_at)} ${formatTime(order.created_at)}</div>
                        <div class="mb-1"><strong>Status:</strong> <span class="badge bg-${getStatusColor(order.status)}">${getStatusText(order.status)}</span></div>
                        <div><strong>Metode Pembayaran:</strong> ${order.metode_pembayaran || '-'}</div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="mb-2">Informasi Pembeli</h6>
                        <div class="mb-1"><strong>Nama:</strong> ${order.user?.name || order.pembeli?.name || '-'}</div>
                        <div class="mb-1"><strong>Email:</strong> ${order.user?.email || order.pembeli?.email || '-'}</div>
                        <div class="mb-1"><strong>Phone:</strong> ${order.user?.phone || order.pembeli?.phone || '-'}</div>
                    </div>
                </div>
            </div>

            <h6 class="mb-3">Item Pesanan</h6>
            <div id="orderItems">
                ${order.items && order.items.length > 0 ?
                    order.items.map(item => `
                        <div class="order-item rounded">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="fw-bold">${item.nama_produk}</div>
                                    <div class="text-muted small">
                                        ${item.jumlah} x Rp ${(item.harga || 0).toLocaleString('id-ID')}
                                    </div>
                                </div>
                                <div class="col-md-4 text-end">
                                    <div class="fw-bold">Rp ${(item.subtotal || (item.harga * item.jumlah) || 0).toLocaleString('id-ID')}</div>
                                </div>
                            </div>
                        </div>
                    `).join('') :
                    '<div class="text-muted text-center py-3">Tidak ada item</div>'
                }
            </div>

            <div class="border-top pt-3 mt-3">
                <div class="row">
                    <div class="col-md-6 offset-md-6">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>Rp ${(order.subtotal || order.total || 0).toLocaleString('id-ID')}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Ongkir:</span>
                            <span>Rp ${(order.ongkir || 0).toLocaleString('id-ID')}</span>
                        </div>
                        <div class="d-flex justify-content-between fw-bold border-top pt-2">
                            <span>Total:</span>
                            <span>Rp ${(order.total || order.total_amount || 0).toLocaleString('id-ID')}</span>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Build footer with action buttons
        let footerHtml = '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>';

        if (order.status !== 'selesai' && order.status !== 'dibatalkan') {
            footerHtml += `
                <button type="button" class="btn btn-primary" onclick="showUpdateStatusModal(${order.id}); bootstrap.Modal.getInstance(document.getElementById('orderDetailModal')).hide();">
                    <i class="fas fa-edit me-1"></i>Update Status
                </button>
            `;
        }

        footer.innerHTML = footerHtml;

        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
    }

    function showUpdateStatusModal(orderId) {
        const order = orders.find(o => o.id === orderId);
        if (!order) return;

        document.getElementById('updateOrderId').value = orderId;
        document.getElementById('newStatus').value = '';
        document.getElementById('statusNote').value = '';

        // Filter available status options based on current status
        const statusSelect = document.getElementById('newStatus');
        const currentStatus = order.status;

        // Reset options
        statusSelect.innerHTML = '<option value="">Pilih Status</option>';

        const availableStatuses = getAvailableStatuses(currentStatus);
        availableStatuses.forEach(status => {
            const option = new Option(getStatusText(status.value), status.value);
            statusSelect.add(option);
        });

        const modal = new bootstrap.Modal(document.getElementById('updateStatusModal'));
        modal.show();
    }

    function getAvailableStatuses(currentStatus) {
        const statuses = {
            'menunggu': [
                { value: 'dibatalkan', text: 'Dibatalkan' }
            ],
            'menunggu_pembayaran': [
                { value: 'dibatalkan', text: 'Dibatalkan' }
            ],
            'menunggu_verifikasi': [
                { value: 'dibatalkan', text: 'Dibatalkan' }
            ],
            'dibayar': [
                { value: 'diproses', text: 'Sedang Diproses' },
                { value: 'dibatalkan', text: 'Dibatalkan' }
            ],
            'diproses': [
                { value: 'dikirim', text: 'Dikirim' },
                { value: 'dibatalkan', text: 'Dibatalkan' }
            ],
            'dikirim': [
                { value: 'selesai', text: 'Selesai' }
            ]
        };

        return statuses[currentStatus] || [];
    }

    function updateOrderStatus() {
        const form = document.getElementById('updateStatusForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const orderId = document.getElementById('updateOrderId').value;
        const newStatus = document.getElementById('newStatus').value;
        const note = document.getElementById('statusNote').value;

        authenticatedFetch(`/api/seller/orders/${orderId}/status`, {
            method: 'PUT',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                status: newStatus,
                note: note
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess('Status pesanan berhasil diperbarui');
                bootstrap.Modal.getInstance(document.getElementById('updateStatusModal')).hide();
                loadOrders();
            } else {
                showError(data.message || 'Gagal memperbarui status pesanan');
            }
        })
        .catch(error => {
            console.error('Error updating order status:', error);
            showError('Terjadi kesalahan saat memperbarui status pesanan');
        });
    }

    function getStatusColor(status) {
        const colors = {
            'menunggu': 'warning',
            'dibayar': 'info',
            'diproses': 'purple',
            'dikirim': 'dark',
            'selesai': 'success',
            'dibatalkan': 'danger'
        };
        return colors[status] || 'secondary';
    }

    function getStatusText(status) {
        const texts = {
            'menunggu': 'Menunggu Pembayaran',
            'menunggu_pembayaran': 'Menunggu Pembayaran',
            'menunggu_verifikasi': 'Menunggu Verifikasi',
            'dibayar': 'Dibayar',
            'diproses': 'Sedang Diproses',
            'dikirim': 'Dikirim',
            'selesai': 'Selesai',
            'dibatalkan': 'Dibatalkan'
        };
        return texts[status] || status;
    }

    function formatDate(dateString) {
        return new Date(dateString).toLocaleDateString('id-ID');
    }

    function formatTime(dateString) {
        return new Date(dateString).toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
        return '';
    }

    function showSuccess(message) {
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
</script>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Set current date in header
        document.addEventListener('DOMContentLoaded', function() {
        const currentDate = new Date().toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        const currentDateHeader = document.getElementById('currentDateHeader');
        if (currentDateHeader) {
            currentDateHeader.textContent = currentDate;
        }
    });    // Payment verification functions
    let currentVerifyOrderId = null;

    function showPaymentVerificationModal(orderId) {
        currentVerifyOrderId = orderId;
        const order = orders.find(o => o.id === orderId);
        if (!order || !order.payment_proof) return;

        // Fill order information
        document.getElementById('verifyOrderNumber').textContent = `#${order.nomor_pesanan || order.id}`;
        document.getElementById('verifyOrderTotal').textContent = `Rp ${(order.total || order.total_amount || 0).toLocaleString('id-ID')}`;
        document.getElementById('verifyPaymentStatus').textContent = getPaymentStatusText(order.status_pembayaran);

        // Format upload date
        let uploadDate = 'Tidak diketahui';
        if (order.payment_proof_uploaded_at) {
            const date = new Date(order.payment_proof_uploaded_at);
            uploadDate = date.toLocaleString('id-ID');
        }
        document.getElementById('verifyUploadDate').textContent = uploadDate;

        // Set payment proof image
        const imageUrl = `/storage/${order.payment_proof}`;
        document.getElementById('verifyPaymentImage').src = imageUrl;

        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('paymentVerificationModal'));
        modal.show();
    }

    function openPaymentProofFull() {
        const imageUrl = document.getElementById('verifyPaymentImage').src;
        window.open(imageUrl, '_blank');
    }

    function getPaymentStatusText(status) {
        const statusTexts = {
            'menunggu': 'Menunggu Pembayaran',
            'dibayar': 'Sudah Dibayar',
            'menunggu_verifikasi': 'Menunggu Verifikasi',
            'gagal': 'Gagal',
            'dibatalkan': 'Dibatalkan'
        };
        return statusTexts[status] || status;
    }

    // Handle payment verification confirmation
    document.getElementById('confirmVerifyPayment').addEventListener('click', async function() {
        if (!currentVerifyOrderId) return;

        const button = this;
        const originalText = button.innerHTML;

        try {
            // Show loading
            button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Memverifikasi...';
            button.disabled = true;

            const response = await authenticatedFetch(`/api/orders/${currentVerifyOrderId}/verify-payment`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                showSuccess('Pembayaran berhasil diverifikasi!');

                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('paymentVerificationModal'));
                modal.hide();

                // Refresh orders list
                loadOrders();
            } else {
                throw new Error(data.message || 'Gagal memverifikasi pembayaran');
            }

        } catch (error) {
            console.error('Error verifying payment:', error);
            showError(error.message || 'Terjadi kesalahan saat memverifikasi pembayaran');
        } finally {
            // Restore button
            button.innerHTML = originalText;
            button.disabled = false;
        }
    });
</script>

</body>
</html>
