@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1"><i class="fas fa-fish text-primary me-2"></i>Kelola Produk</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="/profile">Profil</a></li>
                            <li class="breadcrumb-item"><a href="/seller/dashboard">Dashboard Seller</a></li>
                            <li class="breadcrumb-item active">Produk</li>
                        </ol>
                    </nav>
                </div>
                <button class="btn btn-primary" onclick="showAddProductModal()">
                    <i class="fas fa-plus me-1"></i>Tambah Produk
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
                    <a class="nav-link active" href="{{ route('seller.products') }}">
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

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-box text-primary fa-2x"></i>
                        </div>
                    </div>
                    <h6 class="text-muted mb-1">Total Produk</h6>
                    <h4 class="text-primary mb-0" id="totalProducts">0</h4>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-check-circle text-success fa-2x"></i>
                        </div>
                    </div>
                    <h6 class="text-muted mb-1">Aktif</h6>
                    <h4 class="text-success mb-0" id="activeProducts">0</h4>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-exclamation-triangle text-warning fa-2x"></i>
                        </div>
                    </div>
                    <h6 class="text-muted mb-1">Stok Habis</h6>
                    <h4 class="text-warning mb-0" id="outOfStockProducts">0</h4>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="bg-danger bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-pause-circle text-danger fa-2x"></i>
                        </div>
                    </div>
                    <h6 class="text-muted mb-1">Nonaktif</h6>
                    <h4 class="text-danger mb-0" id="inactiveProducts">0</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter and Search -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" id="searchProducts" placeholder="Cari produk...">
                    </div>
                </div>
                <div class="col-lg-3 mb-3">
                    <select class="form-select" id="filterCategory">
                        <option value="">Semua Kategori</option>
                    </select>
                </div>
                <div class="col-lg-3 mb-3">
                    <select class="form-select" id="filterStatus">
                        <option value="">Semua Status</option>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                        <option value="habis">Stok Habis</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading State -->
    <div id="loading" class="text-center py-5">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-3 text-muted">Memuat produk...</p>
    </div>

    <!-- Products List -->
    <div id="productsContainer" style="display: none;">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Daftar Produk</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Gambar</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Stok & Berat</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="productsTableBody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <nav aria-label="Products pagination" class="mt-4">
            <ul class="pagination justify-content-center" id="productsPagination">
            </ul>
        </nav>
    </div>

    <!-- Empty State -->
    <div id="emptyState" style="display: none;">
        <div class="text-center py-5">
            <i class="fas fa-fish fa-5x text-muted mb-3"></i>
            <h4 class="text-muted">Belum Ada Produk</h4>
            <p class="text-muted mb-4">Mulai tambahkan produk ikan segar Anda untuk dijual</p>
            <button class="btn btn-primary" onclick="showAddProductModal()">
                <i class="fas fa-plus me-1"></i>Tambah Produk Pertama
            </button>
        </div>
    </div>

    <!-- Error State -->
    <div id="errorState" style="display: none;">
        <div class="alert alert-danger" role="alert">
            <h4 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Terjadi Kesalahan</h4>
            <p class="mb-0">Gagal memuat data produk. Silakan coba lagi.</p>
            <hr>
            <button class="btn btn-outline-danger" onclick="loadProducts()">
                <i class="fas fa-redo me-1"></i>Coba Lagi
            </button>
        </div>
    </div>
</div>

<!-- Add/Edit Product Modal -->
<div class="modal fade" id="productModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalTitle">Tambah Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="productForm">
                    <input type="hidden" id="productId">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="productName" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="productName" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="productCategory" class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select" id="productCategory" required>
                                <option value="">Pilih Kategori</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="productType" class="form-label">Jenis Ikan <span class="text-danger">*</span></label>
                            <select class="form-select" id="productType" required>
                                <option value="">Pilih Jenis</option>
                                <option value="segar">Segar</option>
                                <option value="beku">Beku</option>
                                <option value="olahan">Olahan</option>
                                <option value="hidup">Hidup</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="productSpecies" class="form-label">Spesies Ikan</label>
                            <input type="text" class="form-control" id="productSpecies" placeholder="Contoh: Nila, Lele, Gurame">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="productPrice" class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="productPrice" min="0" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="productStock" class="form-label">Stok <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="productStock" min="0" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="productWeight" class="form-label">Berat (gram) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="productWeight" min="0" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" id="productActive" checked>
                                <label class="form-check-label" for="productActive">
                                    Produk Aktif
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="productDescription" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="productDescription" rows="4" placeholder="Deskripsikan produk Anda..." required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="productImage" class="form-label">Gambar Produk</label>
                        <input type="file" class="form-control" id="productImage" accept="image/*" multiple>
                        <div class="form-text">Format: JPG, PNG, GIF. Maksimal 2MB per file. Bisa upload multiple gambar.</div>
                        <div id="imagePreview" class="mt-2" style="display: none;">
                            <div class="row" id="imagePreviewContainer">
                                <!-- Images will be populated here -->
                            </div>
                        </div>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="productFeatured">
                        <label class="form-check-label" for="productFeatured">
                            Produk Unggulan
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="saveProduct()">
                    <i class="fas fa-save me-1"></i>Simpan
                </button>
            </div>
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

    .product-image {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #e9ecef;
        transition: transform 0.2s ease;
    }

    .product-image:hover {
        transform: scale(1.1);
        border-color: #007bff;
    }

    .product-status {
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

    #imagePreviewContainer .img-thumbnail {
        transition: transform 0.2s ease;
    }

    #imagePreviewContainer .img-thumbnail:hover {
        transform: scale(1.05);
    }

    .badge-existing {
        font-size: 0.65rem;
    }
</style>
@endpush

@push('scripts')
<script src="/js/auth.js"></script>
<script>
    let products = [];
    let categories = [];
    let currentPage = 1;
    let perPage = 10;
    let totalPages = 1;

    document.addEventListener('DOMContentLoaded', function() {
        loadCategories();
        loadProducts();

        // Search functionality
        document.getElementById('searchProducts').addEventListener('input', function() {
            currentPage = 1;
            loadProducts();
        });

        // Filter functionality
        document.getElementById('filterCategory').addEventListener('change', function() {
            currentPage = 1;
            loadProducts();
        });

        document.getElementById('filterStatus').addEventListener('change', function() {
            currentPage = 1;
            loadProducts();
        });

        // Image preview
        document.getElementById('productImage').addEventListener('change', function() {
            previewImage(this);
        });
    });

    function loadCategories() {
        fetch('/api/categories')
            .then(response => response.json())
            .then(data => {
                categories = data.data || data;
                populateCategorySelects();
            })
            .catch(error => {
                console.error('Error loading categories:', error);
            });
    }

    function populateCategorySelects() {
        const filterSelect = document.getElementById('filterCategory');
        const modalSelect = document.getElementById('productCategory');

        categories.forEach(category => {
            const option1 = new Option(category.nama, category.id);
            const option2 = new Option(category.nama, category.id);
            filterSelect.add(option1);
            modalSelect.add(option2);
        });
    }

    function loadProducts() {
        const loading = document.getElementById('loading');
        const container = document.getElementById('productsContainer');
        const emptyState = document.getElementById('emptyState');
        const errorState = document.getElementById('errorState');

        loading.style.display = 'block';
        container.style.display = 'none';
        emptyState.style.display = 'none';
        errorState.style.display = 'none';

        const search = document.getElementById('searchProducts').value;
        const category = document.getElementById('filterCategory').value;
        const status = document.getElementById('filterStatus').value;

        let url = `/api/seller/products?page=${currentPage}&per_page=${perPage}`;
        if (search) url += `&search=${encodeURIComponent(search)}`;
        if (category) url += `&category=${category}`;
        if (status) url += `&status=${status}`;

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
            if (data.success) {
                // Ensure products is always an array
                products = Array.isArray(data.data) ? data.data : [];
                totalPages = data.meta?.last_page || 1;

                updateProductStats();
                displayProducts();
                loading.style.display = 'none';

                if (products.length === 0) {
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
            console.error('Error loading products:', error);
            loading.style.display = 'none';
            errorState.style.display = 'block';
        });
    }

    function updateProductStats() {
        const total = products.length;
        const active = products.filter(p => p.aktif === 1 || p.aktif === true).length;
        const outOfStock = products.filter(p => p.stok === 0).length;
        const inactive = products.filter(p => p.aktif === 0 || p.aktif === false).length;

        document.getElementById('totalProducts').textContent = total;
        document.getElementById('activeProducts').textContent = active;
        document.getElementById('outOfStockProducts').textContent = outOfStock;
        document.getElementById('inactiveProducts').textContent = inactive;
    }

    function displayProducts() {
        const tbody = document.getElementById('productsTableBody');

        if (products.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">
                        Tidak ada produk ditemukan
                    </td>
                </tr>
            `;
            return;
        }

        let html = '';
        products.forEach(product => {
            const isActive = product.aktif === 1 || product.aktif === true;
            const isOutOfStock = product.stok === 0;

            let status, statusColor, statusText;
            if (isOutOfStock) {
                status = 'habis';
                statusColor = 'warning';
                statusText = 'Stok Habis';
            } else if (isActive) {
                status = 'aktif';
                statusColor = 'success';
                statusText = 'Aktif';
            } else {
                status = 'nonaktif';
                statusColor = 'danger';
                statusText = 'Nonaktif';
            }

            // Get first image or default - multiple fallback options
            let imageSrc = 'https://via.placeholder.com/60x60/BBDEFB/1976D2?text=No+Image';

            // Priority order: main_image_url > image_urls[0] > gambar array > gambar string
            if (product.main_image_url) {
                imageSrc = product.main_image_url;
            } else if (product.image_urls && Array.isArray(product.image_urls) && product.image_urls.length > 0) {
                imageSrc = product.image_urls[0];
            } else if (product.gambar && Array.isArray(product.gambar) && product.gambar.length > 0) {
                // Use stored path directly (should include products/ prefix)
                imageSrc = '/storage/' + product.gambar[0];
            } else if (product.gambar && typeof product.gambar === 'string') {
                imageSrc = '/storage/' + product.gambar;
            }

            // Debug: temporary logging to console
            if (product.nama === 'Ikan Bebek') {
                console.log('Debug Ikan Bebek image:');
                console.log('- main_image_url:', product.main_image_url);
                console.log('- image_urls:', product.image_urls);
                console.log('- gambar:', product.gambar);
                console.log('- final imageSrc:', imageSrc);
            }

            // Format jenis ikan display
            const jenisIkan = product.jenis_ikan || 'segar';
            const spesiesIkan = product.spesies_ikan ? ` - ${product.spesies_ikan}` : '';
            const jenisIkanDisplay = jenisIkan.charAt(0).toUpperCase() + jenisIkan.slice(1);

            html += `
                <tr>
                    <td>
                        <img src="${imageSrc}"
                             alt="${product.nama}"
                             class="product-image"
                             onerror="this.src='https://via.placeholder.com/60x60/BBDEFB/1976D2?text=No+Image'">
                    </td>
                    <td>
                        <div class="fw-bold">${product.nama}</div>
                        <small class="text-muted">${product.deskripsi ? product.deskripsi.substring(0, 50) + '...' : ''}</small>
                        <br><small class="badge bg-info text-white">${jenisIkanDisplay}${spesiesIkan}</small>
                    </td>
                    <td>${product.category?.nama || product.kategori?.nama || '-'}</td>
                    <td class="fw-bold">Rp ${(product.harga || 0).toLocaleString('id-ID')}</td>
                    <td>
                        <span class="${product.stok === 0 ? 'text-danger' : 'text-success'} fw-bold">
                            ${product.stok} ${product.berat ? `(${product.berat}g)` : 'pcs'}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-${statusColor} product-status">${statusText}</span>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-primary btn-action"
                                    onclick="editProduct(${product.id})" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-outline-success btn-action"
                                    onclick="toggleProductStatus(${product.id})" title="${isActive ? 'Nonaktifkan' : 'Aktifkan'}">
                                <i class="fas fa-${isActive ? 'pause' : 'play'}"></i>
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-action"
                                    onclick="deleteProduct(${product.id})" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });

        tbody.innerHTML = html;
    }

    function updatePagination() {
        const pagination = document.getElementById('productsPagination');
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
            loadProducts();
        }
    }

    function showAddProductModal() {
        document.getElementById('productModalTitle').textContent = 'Tambah Produk';
        document.getElementById('productForm').reset();
        document.getElementById('productId').value = '';

        // Clear image preview
        document.getElementById('imagePreview').style.display = 'none';
        document.getElementById('imagePreviewContainer').innerHTML = '';

        const modal = new bootstrap.Modal(document.getElementById('productModal'));
        modal.show();
    }

    function editProduct(productId) {
        const product = products.find(p => p.id === productId);
        if (!product) return;

        document.getElementById('productModalTitle').textContent = 'Edit Produk';
        document.getElementById('productId').value = product.id;
        document.getElementById('productName').value = product.nama;
        document.getElementById('productCategory').value = product.kategori_id;
        document.getElementById('productDescription').value = product.deskripsi || '';
        document.getElementById('productPrice').value = product.harga;
        document.getElementById('productStock').value = product.stok;
        document.getElementById('productWeight').value = product.berat || '';
        document.getElementById('productType').value = product.jenis_ikan || 'segar';
        document.getElementById('productSpecies').value = product.spesies_ikan || '';
        document.getElementById('productActive').checked = product.aktif === 1 || product.aktif === true;
        document.getElementById('productFeatured').checked = product.unggulan === 1 || product.unggulan === true;

        // Show existing images if available
        const preview = document.getElementById('imagePreview');
        const container = document.getElementById('imagePreviewContainer');

        console.log('Edit product - image data:');
        console.log('- main_image_url:', product.main_image_url);
        console.log('- image_urls:', product.image_urls);
        console.log('- gambar:', product.gambar);

        if (product.image_urls && Array.isArray(product.image_urls) && product.image_urls.length > 0) {
            preview.style.display = 'block';
            container.innerHTML = '';

            product.image_urls.forEach((imageUrl, index) => {
                const colDiv = document.createElement('div');
                colDiv.className = 'col-md-3 mb-2';
                colDiv.innerHTML = `
                    <div class="position-relative">
                        <img src="${imageUrl}"
                             alt="Gambar ${index + 1}"
                             class="img-thumbnail w-100"
                             style="height: 120px; object-fit: cover;"
                             onerror="this.src='https://via.placeholder.com/120x120/BBDEFB/1976D2?text=No+Image'">
                        <div class="position-absolute top-0 end-0 m-1">
                            <span class="badge bg-primary">Existing</span>
                        </div>
                    </div>
                `;
                container.appendChild(colDiv);
            });
        } else if (product.main_image_url) {
            preview.style.display = 'block';
            container.innerHTML = `
                <div class="col-md-3 mb-2">
                    <div class="position-relative">
                        <img src="${product.main_image_url}"
                             alt="Gambar produk"
                             class="img-thumbnail w-100"
                             style="height: 120px; object-fit: cover;"
                             onerror="this.src='https://via.placeholder.com/120x120/BBDEFB/1976D2?text=No+Image'">
                        <div class="position-absolute top-0 end-0 m-1">
                            <span class="badge bg-primary">Existing</span>
                        </div>
                    </div>
                </div>
            `;
        } else if (product.gambar && Array.isArray(product.gambar) && product.gambar.length > 0) {
            // Fallback to manual URL construction
            preview.style.display = 'block';
            container.innerHTML = '';

            product.gambar.forEach((imagePath, index) => {
                // Use stored path directly (should include products/ prefix)
                const imageUrl = '/storage/' + imagePath;

                const colDiv = document.createElement('div');
                colDiv.className = 'col-md-3 mb-2';
                colDiv.innerHTML = `
                    <div class="position-relative">
                        <img src="${imageUrl}"
                             alt="Gambar ${index + 1}"
                             class="img-thumbnail w-100"
                             style="height: 120px; object-fit: cover;"
                             onerror="this.src='https://via.placeholder.com/120x120/BBDEFB/1976D2?text=No+Image'">
                        <div class="position-absolute top-0 end-0 m-1">
                            <span class="badge bg-primary">Existing</span>
                        </div>
                    </div>
                `;
                container.appendChild(colDiv);
            });
        } else {
            preview.style.display = 'none';
        }

        const modal = new bootstrap.Modal(document.getElementById('productModal'));
        modal.show();
    }

    function saveProduct() {
        const form = document.getElementById('productForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const productId = document.getElementById('productId').value;
        const isEdit = productId !== '';

        const formData = new FormData();
        formData.append('nama', document.getElementById('productName').value);
        formData.append('kategori_id', document.getElementById('productCategory').value);
        formData.append('deskripsi', document.getElementById('productDescription').value);
        formData.append('harga', document.getElementById('productPrice').value);
        formData.append('stok', document.getElementById('productStock').value);
        formData.append('berat', document.getElementById('productWeight').value);
        formData.append('jenis_ikan', document.getElementById('productType').value);
        formData.append('spesies_ikan', document.getElementById('productSpecies').value || '');
        formData.append('aktif', document.getElementById('productActive').checked ? 1 : 0);
        formData.append('unggulan', document.getElementById('productFeatured').checked ? 1 : 0);

        // Handle multiple image files
        const imageFiles = document.getElementById('productImage').files;
        if (imageFiles.length > 0) {
            if (isEdit) {
                // For edit, use gambar_baru parameter
                for (let i = 0; i < imageFiles.length; i++) {
                    formData.append('gambar_baru[]', imageFiles[i]);
                }
            } else {
                // For create, use gambar parameter
                for (let i = 0; i < imageFiles.length; i++) {
                    formData.append('gambar[]', imageFiles[i]);
                }
            }
        }

        if (isEdit) {
            formData.append('_method', 'PUT');
        }

        const url = isEdit ? `/api/seller/products/${productId}` : '/api/seller/products';

        // Use authenticatedFetch for proper CSRF handling with multipart/form-data
        authenticatedFetch(url, {
            method: 'POST',
            headers: {
                'Accept': 'application/json'
                // Don't set Content-Type for FormData, browser will set it with boundary
            },
            body: formData
        })
        .then(response => {
            if (!response) {
                throw new Error('No response received');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showSuccess(isEdit ? 'Produk berhasil diperbarui' : 'Produk berhasil ditambahkan');
                bootstrap.Modal.getInstance(document.getElementById('productModal')).hide();
                loadProducts();
            } else {
                showError(data.message || 'Gagal menyimpan produk');
                if (data.errors) {
                    console.error('Validation errors:', data.errors);
                }
            }
        })
        .catch(error => {
            console.error('Error saving product:', error);
            showError('Terjadi kesalahan saat menyimpan produk');
        });
    }

    function toggleProductStatus(productId) {
        const product = products.find(p => p.id === productId);
        if (!product) return;

        const newStatus = (product.aktif === 1 || product.aktif === true) ? false : true;

        authenticatedFetch(`/api/seller/products/${productId}`, {
            method: 'PUT',
            body: JSON.stringify({ aktif: newStatus })
        })
        .then(response => {
            if (!response) {
                throw new Error('No response received');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showSuccess(`Produk berhasil ${newStatus ? 'diaktifkan' : 'dinonaktifkan'}`);
                loadProducts();
            } else {
                showError(data.message || 'Gagal mengubah status produk');
            }
        })
        .catch(error => {
            console.error('Error toggling product status:', error);
            showError('Terjadi kesalahan saat mengubah status produk');
        });
    }

    function deleteProduct(productId) {
        if (!confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
            return;
        }

        authenticatedFetch(`/api/seller/products/${productId}`, {
            method: 'DELETE'
        })
        .then(response => {
            if (!response) {
                throw new Error('No response received');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showSuccess('Produk berhasil dihapus');
                loadProducts();
            } else {
                showError(data.message || 'Gagal menghapus produk');
            }
        })
        .catch(error => {
            console.error('Error deleting product:', error);
            showError('Terjadi kesalahan saat menghapus produk');
        });
    }

    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        const container = document.getElementById('imagePreviewContainer');

        if (input.files && input.files.length > 0) {
            preview.style.display = 'block';
            container.innerHTML = '';

            Array.from(input.files).forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const colDiv = document.createElement('div');
                        colDiv.className = 'col-md-3 mb-2';
                        colDiv.innerHTML = `
                            <div class="position-relative">
                                <img src="${e.target.result}" alt="Preview ${index + 1}"
                                     class="img-thumbnail w-100" style="height: 120px; object-fit: cover;">
                                <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1"
                                        onclick="removePreviewImage(this)" style="padding: 2px 6px;">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        `;
                        container.appendChild(colDiv);
                    };
                    reader.readAsDataURL(file);
                }
            });
        } else {
            preview.style.display = 'none';
        }
    }

    function removePreviewImage(button) {
        const colDiv = button.closest('.col-md-3');
        colDiv.remove();
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

<style>
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
@endpush
