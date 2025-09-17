@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1"><i class="fas fa-map-marker-alt text-primary me-2"></i>Lokasi Toko & Tambak</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="/">Beranda</a></li>
                            <li class="breadcrumb-item active">Lokasi</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info d-flex align-items-center" role="alert">
                <i class="fas fa-info-circle me-3 fa-2x"></i>
                <div>
                    <h6 class="alert-heading mb-1">Temukan Lokasi Penjual & Tambak</h6>
                    <p class="mb-0">Jelajahi peta untuk menemukan toko penjual ikan, tambak ikan, dan pengepul terdekat di sekitar Anda.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Cari Lokasi</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" id="searchLocation" placeholder="Masukkan nama kota, alamat, atau nama toko...">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Jenis Lokasi</label>
                            <select class="form-select" id="locationType">
                                <option value="">Semua Jenis</option>
                                <option value="toko">Toko Penjual</option>
                                <option value="tambak">Tambak Ikan</option>
                                <option value="pengepul">Pengepul</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Radius</label>
                            <select class="form-select" id="radiusFilter">
                                <option value="5">5 km</option>
                                <option value="10" selected>10 km</option>
                                <option value="25">25 km</option>
                                <option value="50">50 km</option>
                                <option value="">Semua Area</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button class="btn btn-primary me-2" onclick="searchLocations()">
                                <i class="fas fa-search me-1"></i>Cari
                            </button>
                            <button class="btn btn-outline-info" onclick="getCurrentLocation()">
                                <i class="fas fa-map-marker-alt me-1"></i>Gunakan Lokasi Saya
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-map me-2"></i>Peta Lokasi
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div id="map" style="height: 400px; width: 100%;">
                        <!-- Map placeholder -->
                        <div class="d-flex align-items-center justify-content-center h-100 bg-light">
                            <div class="text-center">
                                <i class="fas fa-map fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Peta akan dimuat di sini</h5>
                                <p class="text-muted">Fitur peta akan tersedia segera</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Locations List -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list me-2"></i>Daftar Lokasi
                        </h5>
                        <span class="badge bg-primary" id="locationCount">0 Lokasi</span>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Loading State -->
                    <div id="loading" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3 text-muted">Memuat lokasi...</p>
                    </div>

                    <!-- Locations Grid -->
                    <div id="locationsContainer" style="display: none;">
                        <div class="row" id="locationsGrid">
                            <!-- Location cards will be loaded here -->
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div id="emptyState" style="display: none;">
                        <div class="text-center py-5">
                            <i class="fas fa-map-marker-alt fa-5x text-muted mb-3"></i>
                            <h4 class="text-muted">Tidak Ada Lokasi</h4>
                            <p class="text-muted mb-4">Tidak ditemukan lokasi sesuai dengan kriteria pencarian Anda</p>
                            <button class="btn btn-outline-primary" onclick="clearFilters()">
                                <i class="fas fa-redo me-1"></i>Reset Filter
                            </button>
                        </div>
                    </div>

                    <!-- Error State -->
                    <div id="errorState" style="display: none;">
                        <div class="alert alert-danger" role="alert">
                            <h4 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Terjadi Kesalahan</h4>
                            <p class="mb-0">Gagal memuat data lokasi. Silakan coba lagi.</p>
                            <hr>
                            <button class="btn btn-outline-danger" onclick="loadLocations()">
                                <i class="fas fa-redo me-1"></i>Coba Lagi
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .location-card {
        transition: transform 0.2s, box-shadow 0.2s;
        border: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .location-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .location-header {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border-radius: 12px 12px 0 0;
    }

    .location-type-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }

    .location-info {
        padding: 1.5rem;
    }

    .location-meta {
        font-size: 0.875rem;
        color: #6c757d;
    }

    .location-actions {
        border-top: 1px solid #dee2e6;
        padding: 1rem 1.5rem;
        background: #f8f9fa;
        border-radius: 0 0 12px 12px;
    }

    .btn-action {
        width: 36px;
        height: 36px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
    }

    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #e0e0e0;
    }

    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .btn {
        border-radius: 8px;
        font-weight: 500;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border: none;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #5a6fd8, #6a4190);
        transform: translateY(-1px);
    }
</style>
@endpush

@push('scripts')
<script>
    let locations = [];
    let userLocation = null;

    document.addEventListener('DOMContentLoaded', function() {
        loadLocations();
    });

    function loadLocations() {
        const loading = document.getElementById('loading');
        const container = document.getElementById('locationsContainer');
        const emptyState = document.getElementById('emptyState');
        const errorState = document.getElementById('errorState');

        loading.style.display = 'block';
        container.style.display = 'none';
        emptyState.style.display = 'none';
        errorState.style.display = 'none';

        // For now, we'll simulate loading locations
        // Later this can be connected to actual API endpoints for public locations
        setTimeout(() => {
            locations = [
                {
                    id: 1,
                    name: 'Toko Ikan Segar Pak Budi',
                    type: 'toko',
                    address: 'Jl. Pasar Ikan No. 15, Surabaya',
                    city: 'Surabaya',
                    province: 'Jawa Timur',
                    phone: '08123456789',
                    description: 'Menyediakan ikan segar hasil tangkapan harian langsung dari nelayan',
                    open_time: '06:00',
                    close_time: '18:00',
                    rating: 4.5,
                    distance: 2.3
                },
                {
                    id: 2,
                    name: 'Tambak Lele Modern',
                    type: 'tambak',
                    address: 'Desa Tambak Rejo, Sidoarjo',
                    city: 'Sidoarjo',
                    province: 'Jawa Timur',
                    phone: '08567891234',
                    description: 'Tambak lele dengan sistem bioflok modern dan ramah lingkungan',
                    open_time: '07:00',
                    close_time: '17:00',
                    rating: 4.8,
                    distance: 8.7
                },
                {
                    id: 3,
                    name: 'Pengepul Ikan Berkah',
                    type: 'pengepul',
                    address: 'Jl. Pelabuhan No. 42, Gresik',
                    city: 'Gresik',
                    province: 'Jawa Timur',
                    phone: '08765432109',
                    description: 'Pengepul ikan dengan jaringan distribusi ke seluruh Jawa Timur',
                    open_time: '05:00',
                    close_time: '20:00',
                    rating: 4.2,
                    distance: 15.2
                }
            ];

            displayLocations();
            updateLocationCount();
            loading.style.display = 'none';

            if (locations.length === 0) {
                emptyState.style.display = 'block';
            } else {
                container.style.display = 'block';
            }
        }, 1000);
    }

    function displayLocations() {
        const grid = document.getElementById('locationsGrid');

        if (locations.length === 0) {
            grid.innerHTML = '';
            return;
        }

        let html = '';
        locations.forEach(location => {
            const typeColor = getTypeColor(location.type);
            const typeText = getTypeText(location.type);

            html += `
                <div class="col-lg-6 col-xl-4 mb-4">
                    <div class="card location-card">
                        <div class="location-header p-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1 fw-bold">${location.name}</h6>
                                    <div class="small opacity-75">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        ${location.city}, ${location.province}
                                    </div>
                                </div>
                                <span class="badge bg-${typeColor} location-type-badge">${typeText}</span>
                            </div>
                        </div>

                        <div class="location-info">
                            <div class="mb-2">
                                <div class="location-meta">
                                    <i class="fas fa-map-marked-alt me-2 text-primary"></i>
                                    ${location.address}
                                </div>
                            </div>

                            <div class="mb-2">
                                <div class="location-meta">
                                    <i class="fas fa-phone me-2 text-success"></i>
                                    ${location.phone}
                                </div>
                            </div>

                            <div class="mb-2">
                                <div class="location-meta">
                                    <i class="fas fa-clock me-2 text-warning"></i>
                                    ${location.open_time} - ${location.close_time}
                                </div>
                            </div>

                            ${location.description ? `
                                <div class="mb-2">
                                    <div class="location-meta">
                                        <i class="fas fa-info-circle me-2 text-info"></i>
                                        ${location.description.length > 80 ? location.description.substring(0, 80) + '...' : location.description}
                                    </div>
                                </div>
                            ` : ''}

                            <div class="d-flex justify-content-between">
                                <div class="location-meta">
                                    <i class="fas fa-star me-1 text-warning"></i>
                                    ${location.rating}/5
                                </div>
                                <div class="location-meta">
                                    <i class="fas fa-route me-1 text-primary"></i>
                                    ${location.distance} km
                                </div>
                            </div>
                        </div>

                        <div class="location-actions">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-outline-info btn-action"
                                            onclick="viewDetails(${location.id})" title="Detail">
                                        <i class="fas fa-info"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-success btn-action"
                                            onclick="contactLocation(${location.id})" title="Hubungi">
                                        <i class="fas fa-phone"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-primary btn-action"
                                            onclick="getDirections(${location.id})" title="Rute">
                                        <i class="fas fa-directions"></i>
                                    </button>
                                </div>
                                <small class="text-muted">
                                    ${location.type === 'toko' ? 'Toko' : location.type === 'tambak' ? 'Tambak' : 'Pengepul'}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        grid.innerHTML = html;
    }

    function getTypeColor(type) {
        switch(type) {
            case 'toko': return 'primary';
            case 'tambak': return 'success';
            case 'pengepul': return 'warning';
            default: return 'secondary';
        }
    }

    function getTypeText(type) {
        switch(type) {
            case 'toko': return 'Toko';
            case 'tambak': return 'Tambak';
            case 'pengepul': return 'Pengepul';
            default: return 'Lainnya';
        }
    }

    function updateLocationCount() {
        document.getElementById('locationCount').textContent = `${locations.length} Lokasi`;
    }

    function searchLocations() {
        const searchTerm = document.getElementById('searchLocation').value.toLowerCase();
        const locationType = document.getElementById('locationType').value;
        const radius = document.getElementById('radiusFilter').value;

        // This would normally filter the locations based on search criteria
        // For now, just reload the locations
        loadLocations();
    }

    function clearFilters() {
        document.getElementById('searchLocation').value = '';
        document.getElementById('locationType').value = '';
        document.getElementById('radiusFilter').value = '10';
        loadLocations();
    }

    function getCurrentLocation() {
        if (!navigator.geolocation) {
            showError('Geolocation tidak didukung oleh browser ini');
            return;
        }

        navigator.geolocation.getCurrentPosition(
            function(position) {
                userLocation = {
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude
                };
                showSuccess('Lokasi berhasil didapat, memuat lokasi terdekat...');
                loadLocations();
            },
            function(error) {
                showError('Gagal mendapatkan lokasi: ' + error.message);
            }
        );
    }

    function viewDetails(locationId) {
        const location = locations.find(l => l.id === locationId);
        if (location) {
            showInfo(`Detail ${location.name} akan segera tersedia`);
        }
    }

    function contactLocation(locationId) {
        const location = locations.find(l => l.id === locationId);
        if (location) {
            window.open(`tel:${location.phone}`);
        }
    }

    function getDirections(locationId) {
        const location = locations.find(l => l.id === locationId);
        if (location) {
            const address = encodeURIComponent(location.address);
            window.open(`https://www.google.com/maps/search/${address}`, '_blank');
        }
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

    function showInfo(message) {
        const toast = document.createElement('div');
        toast.className = 'toast align-items-center text-white bg-info border-0 position-fixed';
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999;';
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-info-circle me-2"></i>${message}
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
@endpush
