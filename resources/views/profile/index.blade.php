@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0"><i class="fas fa-user text-primary me-2"></i>Profil Saya</h2>
                <div class="text-muted">
                    <i class="fas fa-calendar me-1"></i>
                    <span id="currentDate"></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading State -->
    <div id="loadingState" class="text-center py-5">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-3 text-muted">Memuat profil...</p>
    </div>

    <!-- Profile Content -->
    <div id="profileContent" style="display: none;">
        <!-- Profile Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-3 text-center">
                                <div class="position-relative d-inline-block">
                                    <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                                        <i class="fas fa-user text-primary fa-4x" id="avatarPlaceholder"></i>
                                        <img id="avatarImage" src="" alt="Avatar" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover; display: none;">
                                    </div>
                                    <button class="btn btn-sm btn-primary rounded-circle position-absolute bottom-0 end-0" onclick="changeAvatar()">
                                        <i class="fas fa-camera"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4 class="mb-2 text-primary" id="profileName">-</h4>
                                        <div class="mb-2">
                                            <i class="fas fa-envelope text-muted me-2"></i>
                                            <span id="profileEmail">-</span>
                                        </div>
                                        <div class="mb-2">
                                            <i class="fas fa-phone text-muted me-2"></i>
                                            <span id="profilePhone">-</span>
                                        </div>
                                        <div class="mb-3">
                                            <i class="fas fa-tag text-muted me-2"></i>
                                            <span class="badge bg-primary" id="profileRole">Member</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <i class="fas fa-calendar text-muted me-2"></i>
                                            <span class="text-muted">Bergabung: </span>
                                            <span id="joinDate">-</span>
                                        </div>
                                        <div class="mb-2">
                                            <i class="fas fa-check-circle text-muted me-2"></i>
                                            <span class="text-muted">Status: </span>
                                            <span class="badge bg-success" id="profileStatus">Aktif</span>
                                        </div>
                                        <div class="mb-2">
                                            <i class="fas fa-envelope-check text-muted me-2"></i>
                                            <span class="text-muted">Email: </span>
                                            <span class="badge bg-success" id="emailVerified">Terverifikasi</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button class="btn btn-primary me-2" onclick="showEditDialog()">
                                        <i class="fas fa-edit me-1"></i>Edit Profil
                                    </button>
                                    <button class="btn btn-outline-secondary" onclick="showChangePasswordDialog()">
                                        <i class="fas fa-key me-1"></i>Ubah Password
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!-- Order Statistics -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-chart-bar me-2"></i>Statistik Pesanan
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="card bg-primary bg-opacity-10 border-primary">
                                    <div class="card-body text-center">
                                        <i class="fas fa-shopping-bag text-primary fa-2x mb-2"></i>
                                        <h5 class="text-primary mb-1" id="totalOrders">0</h5>
                                        <small class="text-muted">Total Pesanan</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card bg-warning bg-opacity-10 border-warning">
                                    <div class="card-body text-center">
                                        <i class="fas fa-clock text-warning fa-2x mb-2"></i>
                                        <h5 class="text-warning mb-1" id="pendingOrders">0</h5>
                                        <small class="text-muted">Menunggu</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card bg-danger bg-opacity-10 border-danger">
                                    <div class="card-body text-center">
                                        <i class="fas fa-times-circle text-danger fa-2x mb-2"></i>
                                        <h5 class="text-danger mb-1" id="cancelledOrders">0</h5>
                                        <small class="text-muted">Dibatalkan</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menu Items -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-list me-2"></i>Menu Akun
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <a href="#" class="list-group-item list-group-item-action border-0 d-flex align-items-center" onclick="showOrderHistory()">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="fas fa-receipt text-primary"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold">Riwayat Pesanan</div>
                                    <small class="text-muted">Lihat semua pesanan Anda</small>
                                </div>
                                <i class="fas fa-chevron-right text-muted"></i>
                            </a>

                            <a href="/addresses" class="list-group-item list-group-item-action border-0 d-flex align-items-center">
                                <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="fas fa-map-marker-alt text-success"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold">Kelola Alamat</div>
                                    <small class="text-muted">Atur alamat pengiriman</small>
                                </div>
                                <i class="fas fa-chevron-right text-muted"></i>
                            </a>

                            <!-- Seller Dashboard Menu - Show for seller users -->
                            <div id="sellerDashboardMenuItem" style="display: none;">
                                <a href="/seller/dashboard" class="list-group-item list-group-item-action border-0 d-flex align-items-center">
                                    <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="fas fa-store text-warning seller-badge"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold text-warning">Dashboard Penjual</div>
                                        <small class="text-muted">Kelola toko dan produk Anda</small>
                                    </div>
                                    <i class="fas fa-chevron-right text-warning"></i>
                                </a>
                            </div>

                            <!-- Seller Dashboard Menu For Buyers - Show for non-seller users -->
                            <div id="sellerDashboardMenuItemForBuyers" style="display: none;">
                                <a href="#" class="list-group-item list-group-item-action border-0 d-flex align-items-center" onclick="handleBuyerDashboardAccess()">
                                    <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="fas fa-store text-warning"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold">Dashboard Penjual</div>
                                        <small class="text-muted">Kelola toko dan produk Anda</small>
                                    </div>
                                    <i class="fas fa-chevron-right text-muted"></i>
                                </a>
                            </div>

                            <!-- Mode Penjual Menu - Hidden by default, akan ditampilkan melalui JavaScript jika diperlukan -->
                            <div id="sellerMenuItem" style="display: none;">
                                <a href="#" class="list-group-item list-group-item-action border-0 d-flex align-items-center" onclick="handleSellerAccess()">
                                    <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="fas fa-store text-warning"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold">Mode Penjual</div>
                                        <small class="text-muted" id="sellerSubtitle">Mulai berjualan di IwakMart</small>
                                    </div>
                                    <i class="fas fa-chevron-right text-muted"></i>
                                </a>
                            </div>

                            <a href="#" class="list-group-item list-group-item-action border-0 d-flex align-items-center" onclick="showNotificationSettings()">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="fas fa-bell text-primary"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold">Pengaturan Notifikasi</div>
                                    <small class="text-muted">Atur notifikasi aplikasi</small>
                                </div>
                                <i class="fas fa-chevron-right text-muted"></i>
                            </a>

                            <a href="#" class="list-group-item list-group-item-action border-0 d-flex align-items-center" onclick="showSecuritySettings()">
                                <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="fas fa-shield-alt text-info"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold">Keamanan</div>
                                    <small class="text-muted">Ubah password dan keamanan</small>
                                </div>
                                <i class="fas fa-chevron-right text-muted"></i>
                            </a>

                            <!-- Seller Menu Item - Conditional -->
                            <div id="sellerMenuItem" style="display: none;">
                                <a href="#" class="list-group-item list-group-item-action border-0 d-flex align-items-center" onclick="handleSellerAccess()">
                                    <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="fas fa-store text-warning"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold">Mode Penjual</div>
                                        <small class="text-muted" id="sellerSubtitle">Mulai berjualan di IwakMart</small>
                                    </div>
                                    <i class="fas fa-chevron-right text-muted"></i>
                                </a>
                            </div>

                            <a href="#" class="list-group-item list-group-item-action border-0 d-flex align-items-center" onclick="showHelpCenter()">
                                <div class="bg-secondary bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="fas fa-question-circle text-secondary"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold">Pusat Bantuan</div>
                                    <small class="text-muted">FAQ dan panduan aplikasi</small>
                                </div>
                                <i class="fas fa-chevron-right text-muted"></i>
                            </a>

                            <a href="#" class="list-group-item list-group-item-action border-0 d-flex align-items-center" onclick="showAboutApp()">
                                <div class="bg-dark bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="fas fa-info-circle text-dark"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold">Tentang Aplikasi</div>
                                    <small class="text-muted">Informasi aplikasi IwakMart</small>
                                </div>
                                <i class="fas fa-chevron-right text-muted"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Logout Button -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm border-danger">
                    <div class="card-body text-center">
                        <button class="btn btn-danger" onclick="confirmLogout()">
                            <i class="fas fa-sign-out-alt me-1"></i>Keluar dari Akun
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="/js/auth.js"></script>
<script>
    let user = null;
    let orderStats = { total: 0, pending: 0, cancelled: 0 };

    // Load profile data on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Set current date
        document.getElementById('currentDate').textContent = new Date().toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        fetchProfile();
        fetchOrderStats();
    });

    // Fetch user profile
    async function fetchProfile() {
        try {
            // First check auth status
            const authResponse = await fetch('/auth-test');
            const authData = await authResponse.json();

            if (!authData.authenticated) {
                window.location.href = '/login';
                return;
            }

            user = authData.user;
            displayProfile();

        } catch (error) {
            console.error('Error fetching profile:', error);
            showError('Gagal memuat profil');
        }
    }

    // Display profile data
    function displayProfile() {
        if (!user) return;

        document.getElementById('profileName').textContent = user.name || '-';
        document.getElementById('profileEmail').textContent = user.email || '-';

        if (user.phone) {
            document.getElementById('profilePhone').textContent = user.phone;
            document.getElementById('profilePhone').style.display = 'block';
        }

        // Update role badge
        const roleElement = document.getElementById('profileRole');
        if (user.role === 'penjual_biasa') {
            roleElement.textContent = 'Penjual';
            roleElement.className = 'badge bg-warning';
        } else if (user.role === 'admin') {
            roleElement.textContent = 'Admin';
            roleElement.className = 'badge bg-danger';
        } else {
            roleElement.textContent = 'Member';
            roleElement.className = 'badge bg-primary';
        }

        // Update join date
        if (user.created_at) {
            const joinDate = new Date(user.created_at).toLocaleDateString('id-ID', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            document.getElementById('joinDate').textContent = joinDate;
        }

        // Update status
        document.getElementById('profileStatus').textContent = user.active ? 'Aktif' : 'Tidak Aktif';
        document.getElementById('profileStatus').className = user.active ? 'badge bg-success' : 'badge bg-danger';

        // Update email verification status
        const emailVerified = document.getElementById('emailVerified');
        if (user.email_verified_at) {
            emailVerified.textContent = 'Terverifikasi';
            emailVerified.className = 'badge bg-success';
        } else {
            emailVerified.textContent = 'Belum Terverifikasi';
            emailVerified.className = 'badge bg-warning';
        }

        // Check if user is seller and show appropriate sections
        updateSellerDisplay();

        // Hide loading and show content
        document.getElementById('loadingState').style.display = 'none';
        document.getElementById('profileContent').style.display = 'block';
    }

    // Update seller display based on user role
    function updateSellerDisplay() {
        if (!user) return;

        const isSellerUser = user.role === 'penjual_biasa' || user.role === 'seller' || hasSellerPermissions(user);

        if (isSellerUser) {
            // User is already a seller - show real dashboard menu, hide registration menus
            document.getElementById('sellerMenuItem').style.display = 'none';
            document.getElementById('sellerDashboardMenuItem').style.display = 'block';
            document.getElementById('sellerDashboardMenuItemForBuyers').style.display = 'none';
        } else {
            // User is not a seller - show dashboard menu for buyers (will prompt registration)
            document.getElementById('sellerMenuItem').style.display = 'none';
            document.getElementById('sellerDashboardMenuItem').style.display = 'none';
            document.getElementById('sellerDashboardMenuItemForBuyers').style.display = 'block';
        }
        
        // Semua menu dasar selalu ditampilkan untuk kedua role
        // (Riwayat Pesanan, Kelola Alamat, Pengaturan Notifikasi, Keamanan, Pusat Bantuan, Tentang Aplikasi)
    }

    // Check if user has seller permissions
    function hasSellerPermissions(user) {
        return user.role === 'seller' ||
               user.role === 'penjual_biasa' ||
               user.is_seller === true ||
               (user.roles && user.roles.includes('seller')) ||
               (user.permissions && user.permissions.includes('sell'));
    }

    // Fetch order statistics
    async function fetchOrderStats() {
        try {
            // Mock data for now - replace with real API call
            orderStats = {
                total: Math.floor(Math.random() * 20),
                pending: Math.floor(Math.random() * 5),
                cancelled: Math.floor(Math.random() * 3)
            };

            document.getElementById('totalOrders').textContent = orderStats.total;
            document.getElementById('pendingOrders').textContent = orderStats.pending;
            document.getElementById('cancelledOrders').textContent = orderStats.cancelled;
        } catch (error) {
            console.error('Error fetching order stats:', error);
        }
    }

    // Handle seller access
    function handleSellerAccess() {
        if (!user) {
            showError('Silakan login terlebih dahulu');
            return;
        }

        // Check if user is already a seller
        if (user.role === 'penjual_biasa' || user.role === 'seller') {
            // User is already a seller, redirect to dashboard
            window.location.href = '/seller/dashboard';
            return;
        }

        // User is not a seller, show registration modal or redirect to registration
        showSellerRegistrationModal();
    }

    // Handle buyer dashboard access - show registration modal
    function handleBuyerDashboardAccess() {
        if (!user) {
            showError('Silakan login terlebih dahulu');
            return;
        }

        // Check if user is already a seller
        if (user.role === 'penjual_biasa' || user.role === 'seller') {
            // User is already a seller, redirect to dashboard
            window.location.href = '/seller/dashboard';
            return;
        }

        // User is not a seller, show registration modal
        showSellerRegistrationModal();
    }

    // Show seller registration modal
    function showSellerRegistrationModal() {
        // Create beautiful registration modal
        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.innerHTML = `
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-store me-2"></i>Daftar Sebagai Penjual
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center mb-4">
                            <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <i class="fas fa-store text-warning fa-2x"></i>
                            </div>
                            <h4 class="mt-3 text-warning">Mulai Berjualan di IwakMart!</h4>
                            <p class="text-muted">Bergabunglah dengan ribuan penjual lainnya dan raih kesuksesan dalam bisnis ikan Anda.</p>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card border-0 bg-light h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-chart-line text-success fa-2x mb-3"></i>
                                        <h6>Tingkatkan Penjualan</h6>
                                        <small class="text-muted">Jangkauan lebih luas untuk produk ikan Anda</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 bg-light h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-users text-info fa-2x mb-3"></i>
                                        <h6>Komunitas Besar</h6>
                                        <small class="text-muted">Bergabung dengan ribuan pembeli aktif</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card border-0 bg-light h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-tools text-primary fa-2x mb-3"></i>
                                        <h6>Tools Lengkap</h6>
                                        <small class="text-muted">Dashboard dan tools untuk mengelola toko</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 bg-light h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-shield-alt text-success fa-2x mb-3"></i>
                                        <h6>Aman & Terpercaya</h6>
                                        <small class="text-muted">Platform yang aman untuk bertransaksi</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Gratis!</strong> Tidak ada biaya pendaftaran. Anda dapat langsung mulai berjualan setelah mendaftar.
                        </div>

                        <div class="text-muted small">
                            <p><strong>Dengan mendaftar sebagai penjual, Anda menyetujui:</strong></p>
                            <ul class="mb-0">
                                <li>Menjual produk ikan yang berkualitas dan segar</li>
                                <li>Memberikan informasi produk yang akurat</li>
                                <li>Melayani pembeli dengan baik dan responsif</li>
                                <li>Mengikuti kebijakan dan aturan platform IwakMart</li>
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Batal
                        </button>
                        <button type="button" class="btn btn-warning" onclick="registerAsSeller()" id="registerSellerBtn">
                            <i class="fas fa-store me-1"></i>Daftar Sebagai Penjual
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        const bootstrapModal = new bootstrap.Modal(modal);
        bootstrapModal.show();

        // Remove modal from DOM when hidden
        modal.addEventListener('hidden.bs.modal', function () {
            modal.remove();
        });
    }

    // Handle seller registration
    function registerAsSeller() {
        const registerBtn = document.getElementById('registerSellerBtn');
        const originalText = registerBtn.innerHTML;
        
        // Show loading state
        registerBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Mendaftar...';
        registerBtn.disabled = true;

        // Make API call to register as seller using authenticatedFetch
        authenticatedFetch('/api/register-seller', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.querySelector('.modal'));
                modal.hide();
                
                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Anda berhasil terdaftar sebagai penjual. Halaman akan dimuat ulang.',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    // Reload page to update UI
                    window.location.reload();
                });
            } else {
                throw new Error(data.message || 'Gagal mendaftar sebagai penjual');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: error.message || 'Terjadi kesalahan saat mendaftar sebagai penjual'
            });
        })
        .finally(() => {
            // Reset button state
            registerBtn.innerHTML = originalText;
            registerBtn.disabled = false;
        });
    }

    // Show edit profile dialog
    function showEditDialog() {
        // Create modal for edit profile
        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.innerHTML = `
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Profil</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control" id="editName" value="${user.name || ''}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" id="editEmail" value="${user.email || ''}" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" class="form-control" id="editPhone" value="${user.phone || ''}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary" onclick="updateProfile()">Simpan</button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
        const bootstrapModal = new bootstrap.Modal(modal);
        bootstrapModal.show();
    }

    // Update profile
    async function updateProfile() {
        const name = document.getElementById('editName').value;
        const phone = document.getElementById('editPhone').value;

        try {
            // Mock update - in real app, send to API
            user.name = name;
            user.phone = phone;

            displayProfile();

            // Close modal
            const modal = document.querySelector('.modal');
            if (modal) {
                const bootstrapModal = bootstrap.Modal.getInstance(modal);
                bootstrapModal.hide();
                modal.remove();
            }

            showSuccess('Profil berhasil diperbarui');
        } catch (error) {
            console.error('Error updating profile:', error);
            showError('Gagal memperbarui profil');
        }
    }

    // Menu functions
    function showOrderHistory() {
        alert('Fitur riwayat pesanan akan segera hadir');
    }

    function showNotificationSettings() {
        alert('Fitur pengaturan notifikasi akan segera hadir');
    }

    function showSecuritySettings() {
        alert('Fitur pengaturan keamanan akan segera hadir');
    }

    function showHelpCenter() {
        alert('Fitur pusat bantuan akan segera hadir');
    }

    function showAboutApp() {
        alert('IwakMart v1.0.0 - Marketplace Ikan Segar Terpercaya');
    }

    // Logout function
    function confirmLogout() {
        if (confirm('Apakah Anda yakin ingin keluar dari akun?')) {
            // Use the logout route defined in web.php
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/logout';
            form.style.display = 'none';

            // Add CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                const tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = '_token';
                tokenInput.value = csrfToken.getAttribute('content');
                form.appendChild(tokenInput);
            }

            // Add to page and submit
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Helper functions
    function showSuccess(message) {
        // Create a simple toast notification
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
        // Create a simple toast notification
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

    // Change avatar function
    function changeAvatar() {
        // Create file input
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = 'image/*';

        input.onchange = function(e) {
            const file = e.target.files[0];
            if (!file) return;

            // Validate file size (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                showError('Ukuran file maksimal 2MB');
                return;
            }

            // Validate file type
            if (!file.type.startsWith('image/')) {
                showError('File harus berupa gambar');
                return;
            }

            // Create FormData
            const formData = new FormData();
            formData.append('avatar', file);

            // Upload avatar
            authenticatedFetch('/api/user/avatar', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response || !response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showSuccess('Avatar berhasil diperbarui');
                    fetchProfile(); // Reload to show new avatar
                } else {
                    throw new Error(data.message || 'Gagal mengupload avatar');
                }
            })
            .catch(error => {
                console.error('Error uploading avatar:', error);
                showError('Gagal mengupload avatar: ' + error.message);
            });
        };

        input.click();
    }

    // Show change password dialog
    function showChangePasswordDialog() {
        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.innerHTML = `
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-key text-primary me-2"></i>Ubah Password
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="changePasswordForm">
                            <div class="mb-3">
                                <label for="currentPassword" class="form-label">Password Saat Ini</label>
                                <input type="password" class="form-control" id="currentPassword" required>
                            </div>
                            <div class="mb-3">
                                <label for="newPassword" class="form-label">Password Baru</label>
                                <input type="password" class="form-control" id="newPassword" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" class="form-control" id="confirmPassword" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary" onclick="changePassword()">
                            <i class="fas fa-save me-1"></i>Ubah Password
                        </button>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(modal);
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();

        modal.addEventListener('hidden.bs.modal', () => modal.remove());
    }

    // Change password function
    function changePassword() {
        const currentPassword = document.getElementById('currentPassword').value;
        const newPassword = document.getElementById('newPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;

        if (!currentPassword || !newPassword || !confirmPassword) {
            showError('Semua field password wajib diisi');
            return;
        }

        if (newPassword !== confirmPassword) {
            showError('Konfirmasi password tidak sesuai');
            return;
        }

        if (newPassword.length < 6) {
            showError('Password baru minimal 6 karakter');
            return;
        }

        authenticatedFetch('/api/user/change-password', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                current_password: currentPassword,
                new_password: newPassword,
                new_password_confirmation: confirmPassword
            })
        })
        .then(response => {
            if (!response || !response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showSuccess('Password berhasil diubah');
                bootstrap.Modal.getInstance(document.querySelector('.modal')).hide();
            } else {
                throw new Error(data.message || 'Gagal mengubah password');
            }
        })
        .catch(error => {
            console.error('Error changing password:', error);
            showError('Gagal mengubah password: ' + error.message);
        });
    }
</script>

<style>
    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
    }

    .list-group-item {
        transition: background-color 0.2s ease, transform 0.2s ease;
    }

    .list-group-item:hover {
        background-color: rgba(13, 110, 253, 0.05) !important;
        transform: translateX(5px);
    }

    .seller-badge {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.7; }
        100% { opacity: 1; }
    }
</style>
@endpush
