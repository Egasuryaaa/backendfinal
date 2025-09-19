<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Informasi Toko - IwakMart</title>

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
                <i class="fas fa-store"></i>
            </div>
            <div class="header-info">
                <h1>Informasi Toko</h1>
            </div>
        </div>
    </div>

<div class="container">
    <div style="padding: 20px;">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">

                    <button class="btn btn-primary" onclick="showEditStoreModal()">
                        <i class="fas fa-edit me-1"></i>Ubah Informasi
                    </button>
                </div>
            </div>
        </div>

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
                    <a class="nav-link" href="{{ route('seller.orders') }}">
                        <i class="fas fa-shopping-cart me-2"></i>Pesanan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('seller.locations') }}">
                        <i class="fas fa-store me-2"></i>Info Toko
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Info Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info d-flex align-items-center" role="alert">
                <i class="fas fa-info-circle me-3 fa-2x"></i>
                <div>
                    <h6 class="alert-heading mb-1">Informasi Alamat Toko</h6>
                    <p class="mb-0">Ini adalah informasi alamat toko Anda yang akan ditampilkan kepada pembeli. Alamat ini berbeda dengan alamat pengiriman pembeli - alamat ini digunakan untuk informasi lokasi toko dan kunjungan langsung.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Store Information Card -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-store me-2"></i>Informasi Toko Saya
                    </h5>
                </div>
                <div class="card-body">
                    <div id="loading" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3 text-muted">Memuat informasi toko...</p>
                    </div>

                    <div id="storeInfo" style="display: none;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-muted">Nama Toko</label>
                                    <p class="h6" id="storeName">-</p>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold text-muted">No. Telepon</label>
                                    <p id="storePhone">-</p>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold text-muted">Alamat</label>
                                    <p id="storeAddress">-</p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-muted">Kota/Kabupaten</label>
                                    <p id="storeCity">-</p>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold text-muted">Provinsi</label>
                                    <p id="storeProvince">-</p>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold text-muted">Status Toko</label>
                                    <p id="storeStatus">-</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-muted">Deskripsi Toko</label>
                                    <p id="storeDescription">-</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-muted">Jam Buka</label>
                                    <p id="storeHours">-</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-muted">Bergabung Sejak</label>
                                    <p id="storeJoinDate">-</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div id="emptyState" style="display: none;">
                        <div class="text-center py-5">
                            <i class="fas fa-store fa-5x text-muted mb-3"></i>
                            <h4 class="text-muted">Belum Ada Informasi Toko</h4>
                            <p class="text-muted mb-4">Lengkapi informasi toko Anda agar pembeli dapat mengenal toko Anda</p>
                            <button class="btn btn-primary" onclick="showEditStoreModal()">
                                <i class="fas fa-edit me-1"></i>Lengkapi Informasi Toko
                            </button>
                        </div>
                    </div>

                    <!-- Error State -->
                    <div id="errorState" style="display: none;">
                        <div class="alert alert-danger" role="alert">
                            <h4 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Terjadi Kesalahan</h4>
                            <p class="mb-0">Gagal memuat informasi toko. Silakan coba lagi.</p>
                            <hr>
                            <button class="btn btn-outline-danger" onclick="loadStoreInfo()">
                                <i class="fas fa-redo me-1"></i>Coba Lagi
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Store Information Modal -->
<div class="modal fade" id="storeModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Informasi Toko</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="storeForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="storeName" class="form-label">Nama Toko <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="storeNameInput" required placeholder="Contoh: Toko Ikan Segar Pak Budi">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="storePhone" class="form-label">No. Telepon <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="storePhoneInput" required placeholder="Contoh: 08123456789">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="storeAddress" class="form-label">Alamat Toko <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="storeAddressInput" rows="3" required placeholder="Masukkan alamat lengkap toko Anda..."></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="storeCity" class="form-label">Kota/Kabupaten <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="storeCityInput" required placeholder="Contoh: Surabaya">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="storeProvince" class="form-label">Provinsi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="storeProvinceInput" required placeholder="Contoh: Jawa Timur">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="storeDescription" class="form-label">Deskripsi Toko</label>
                        <textarea class="form-control" id="storeDescriptionInput" rows="4" placeholder="Ceritakan tentang toko Anda, jenis produk yang dijual, keunggulan toko, dll..."></textarea>
                        <div class="form-text">Deskripsi yang menarik akan membantu pembeli mengenal toko Anda</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="storeOpenTime" class="form-label">Jam Buka</label>
                            <input type="time" class="form-control" id="storeOpenTimeInput">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="storeCloseTime" class="form-label">Jam Tutup</label>
                            <input type="time" class="form-control" id="storeCloseTimeInput">
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="storeActiveInput" checked>
                            <label class="form-check-label" for="storeActiveInput">
                                Toko Aktif (dapat dilihat pembeli)
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="saveStoreInfo()">
                    <i class="fas fa-save me-1"></i>Simpan
                </button>
            </div>
        </div>
    </div>
    </div>
</div>

<!-- Inline Styles -->
<style>
    .card {
        transition: transform 0.2s, box-shadow 0.2s;
        border: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .alert {
        border: none;
        border-radius: 12px;
    }

    .badge {
        font-size: 0.75rem;
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
    }

    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        padding: 0.75rem;
    }

    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .btn {
        border-radius: 8px;
        padding: 0.6rem 1.2rem;
        font-weight: 500;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border: none;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #5a6fd8, #6a4190);
        transform: translateY(-1px);
<!-- Inline Styles -->
<style>
    .card {
        transition: transform 0.2s, box-shadow 0.2s;
        border: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .info-label {
        font-weight: 600;
        color: #495057;
        font-size: 0.9rem;
    }

    .info-value {
        color: #212529;
        font-size: 1rem;
    }

    .info-item {
        padding: 1rem;
        border-left: 4px solid #007bff;
        background: rgba(0, 123, 255, 0.02);
        border-radius: 0 8px 8px 0;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .info-item:hover {
        background: rgba(0, 123, 255, 0.05);
        border-left-color: #0056b3;
        transform: translateX(5px);
    }

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
    let storeInfo = null;

    document.addEventListener('DOMContentLoaded', function() {
        // Check if user is authenticated before loading store info
        checkAuthenticationAndLoadStore();
    });

    function checkAuthenticationAndLoadStore() {
        authenticatedFetch('/api/user', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (response && response.ok) {
                return response.json();
            }
            throw new Error('User not authenticated');
        })
        .then(user => {
            loadStoreInfo();
        })
        .catch(error => {
            console.error('Authentication error:', error);
            // Redirect to login if not authenticated
            window.location.href = '/login';
        });
    }

    function loadStoreInfo() {
        const loading = document.getElementById('loading');
        const storeInfoDiv = document.getElementById('storeInfo');
        const emptyState = document.getElementById('emptyState');
        const errorState = document.getElementById('errorState');

        loading.style.display = 'block';
        storeInfoDiv.style.display = 'none';
        emptyState.style.display = 'none';
        errorState.style.display = 'none';

        // Use dedicated seller info API for store information
        authenticatedFetch('/api/seller/store-info', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (!response || !response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                storeInfo = data.data || {};
                displayStoreInfo();
                loading.style.display = 'none';

                if (!storeInfo.nama_toko && !storeInfo.store_name) {
                    emptyState.style.display = 'block';
                } else {
                    storeInfoDiv.style.display = 'block';
                }
            } else {
                throw new Error(data.message || 'Unknown error');
            }
        })
        .catch(error => {
            console.error('Error loading store info:', error);

            // Fallback to user profile API if dedicated endpoint doesn't exist yet
            authenticatedFetch('/api/user/profile', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (!response || !response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    storeInfo = data.data || {};
                    displayStoreInfo();
                    loading.style.display = 'none';

                    if (!storeInfo.nama_toko && !storeInfo.store_name) {
                        emptyState.style.display = 'block';
                    } else {
                        storeInfoDiv.style.display = 'block';
                    }
                } else {
                    throw new Error(data.message || 'Unknown error');
                }
            })
            .catch(finalError => {
                console.error('Final error loading store info:', finalError);
                loading.style.display = 'none';
                errorState.style.display = 'block';
            });
        });
    }

    function displayStoreInfo() {
        if (!storeInfo) return;

        document.getElementById('storeName').textContent = storeInfo.nama_toko || storeInfo.store_name || storeInfo.name || '-';
        document.getElementById('storePhone').textContent = storeInfo.telepon || storeInfo.phone || '-';
        document.getElementById('storeAddress').textContent = storeInfo.alamat || storeInfo.address || '-';
        document.getElementById('storeCity').textContent = storeInfo.kota || storeInfo.city || '-';
        document.getElementById('storeProvince').textContent = storeInfo.provinsi || storeInfo.province || '-';
        document.getElementById('storeDescription').textContent = storeInfo.deskripsi || storeInfo.description || 'Belum ada deskripsi';

        const openTime = storeInfo.jam_buka || storeInfo.open_time || '';
        const closeTime = storeInfo.jam_tutup || storeInfo.close_time || '';
        const hours = (openTime && closeTime) ? `${openTime} - ${closeTime}` : 'Belum diatur';
        document.getElementById('storeHours').textContent = hours;

        const joinDate = storeInfo.created_at ? new Date(storeInfo.created_at).toLocaleDateString('id-ID') : '-';
        document.getElementById('storeJoinDate').textContent = joinDate;

        const isActive = storeInfo.status === 'aktif' || storeInfo.active === true;
        const statusHtml = isActive
            ? '<span class="badge bg-success">Aktif</span>'
            : '<span class="badge bg-secondary">Nonaktif</span>';
        document.getElementById('storeStatus').innerHTML = statusHtml;
    }

    function showEditStoreModal() {
        if (storeInfo) {
            document.getElementById('storeNameInput').value = storeInfo.nama_toko || storeInfo.store_name || storeInfo.name || '';
            document.getElementById('storePhoneInput').value = storeInfo.telepon || storeInfo.phone || '';
            document.getElementById('storeAddressInput').value = storeInfo.alamat || storeInfo.address || '';
            document.getElementById('storeCityInput').value = storeInfo.kota || storeInfo.city || '';
            document.getElementById('storeProvinceInput').value = storeInfo.provinsi || storeInfo.province || '';
            document.getElementById('storeDescriptionInput').value = storeInfo.deskripsi || storeInfo.description || '';
            document.getElementById('storeOpenTimeInput').value = storeInfo.jam_buka || storeInfo.open_time || '';
            document.getElementById('storeCloseTimeInput').value = storeInfo.jam_tutup || storeInfo.close_time || '';
            document.getElementById('storeActiveInput').checked = storeInfo.status === 'aktif' || storeInfo.active === true;
        }

        const modal = new bootstrap.Modal(document.getElementById('storeModal'));
        modal.show();
    }

    function saveStoreInfo() {
        const form = document.getElementById('storeForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const data = {
            nama_toko: document.getElementById('storeNameInput').value,
            telepon: document.getElementById('storePhoneInput').value,
            alamat: document.getElementById('storeAddressInput').value,
            kota: document.getElementById('storeCityInput').value,
            provinsi: document.getElementById('storeProvinceInput').value,
            deskripsi: document.getElementById('storeDescriptionInput').value,
            jam_buka: document.getElementById('storeOpenTimeInput').value,
            jam_tutup: document.getElementById('storeCloseTimeInput').value,
            active: document.getElementById('storeActiveInput').checked
        };

        authenticatedFetch('/api/user/store-info', {
            method: 'PUT',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response || !response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showSuccess('Informasi toko berhasil diperbarui');
                bootstrap.Modal.getInstance(document.getElementById('storeModal')).hide();
                loadStoreInfo();
            } else {
                showError(data.message || 'Gagal menyimpan informasi toko');
            }
        })
        .catch(error => {
            console.error('Error saving store info:', error);
            showError('Terjadi kesalahan saat menyimpan informasi toko');
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

        document.getElementById('currentDateHeader').textContent = currentDate;
    });
</script>

</body>
</html>
