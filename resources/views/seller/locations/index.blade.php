@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1"><i class="fas fa-map-marker-alt text-primary me-2"></i>Lokasi Usaha</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="/profile">Profil</a></li>
                            <li class="breadcrumb-item"><a href="/seller/dashboard">Dashboard Seller</a></li>
                            <li class="breadcrumb-item active">Lokasi Usaha</li>
                        </ol>
                    </nav>
                </div>
                <button class="btn btn-primary" onclick="showAddLocationModal()">
                    <i class="fas fa-plus me-1"></i>Tambah Lokasi
                </button>
            </div>
        </div>
    </div>

    <!-- Info Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info d-flex align-items-center" role="alert">
                <i class="fas fa-info-circle me-3 fa-2x"></i>
                <div>
                    <h6 class="alert-heading mb-1">Informasi Lokasi Usaha</h6>
                    <p class="mb-0">Tambahkan lokasi tempat usaha Anda agar pembeli dapat dengan mudah menemukan dan mengunjungi toko Anda. Anda dapat menambahkan beberapa lokasi jika memiliki cabang usaha.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-store text-primary fa-2x"></i>
                        </div>
                    </div>
                    <h6 class="text-muted mb-1">Total Lokasi</h6>
                    <h4 class="text-primary mb-0" id="totalLocations">0</h4>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-check-circle text-success fa-2x"></i>
                        </div>
                    </div>
                    <h6 class="text-muted mb-1">Aktif</h6>
                    <h4 class="text-success mb-0" id="activeLocations">0</h4>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-eye text-warning fa-2x"></i>
                        </div>
                    </div>
                    <h6 class="text-muted mb-1">Total Kunjungan</h6>
                    <h4 class="text-warning mb-0" id="totalVisits">0</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading State -->
    <div id="loading" class="text-center py-5">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-3 text-muted">Memuat lokasi...</p>
    </div>

    <!-- Locations List -->
    <div id="locationsContainer" style="display: none;">
        <div class="row" id="locationsGrid">
            <!-- Location cards will be loaded here -->
        </div>
    </div>

    <!-- Empty State -->
    <div id="emptyState" style="display: none;">
        <div class="text-center py-5">
            <i class="fas fa-map-marker-alt fa-5x text-muted mb-3"></i>
            <h4 class="text-muted">Belum Ada Lokasi</h4>
            <p class="text-muted mb-4">Tambahkan lokasi usaha Anda agar pembeli dapat menemukan toko Anda</p>
            <button class="btn btn-primary" onclick="showAddLocationModal()">
                <i class="fas fa-plus me-1"></i>Tambah Lokasi Pertama
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

<!-- Add/Edit Location Modal -->
<div class="modal fade" id="locationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="locationModalTitle">Tambah Lokasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="locationForm">
                    <input type="hidden" id="locationId">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="locationName" class="form-label">Nama Usaha <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="locationName" required placeholder="Contoh: Toko Ikan Segar Pak Budi">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="locationPhone" class="form-label">No. Telepon</label>
                            <input type="text" class="form-control" id="locationPhone" placeholder="Contoh: 08123456789">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="locationAddress" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="locationAddress" rows="3" required placeholder="Masukkan alamat lengkap lokasi usaha Anda..."></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="locationCity" class="form-label">Kota/Kabupaten <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="locationCity" required placeholder="Contoh: Surabaya">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="locationProvince" class="form-label">Provinsi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="locationProvince" required placeholder="Contoh: Jawa Timur">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="locationLatitude" class="form-label">Latitude</label>
                            <input type="number" step="any" class="form-control" id="locationLatitude" placeholder="Contoh: -7.250445">
                            <div class="form-text">Opsional, untuk penanda lokasi yang akurat di peta</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="locationLongitude" class="form-label">Longitude</label>
                            <input type="number" step="any" class="form-control" id="locationLongitude" placeholder="Contoh: 112.768845">
                            <div class="form-text">Opsional, untuk penanda lokasi yang akurat di peta</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <button type="button" class="btn btn-outline-info btn-sm" onclick="getCurrentLocation()">
                            <i class="fas fa-map-marker-alt me-1"></i>Gunakan Lokasi Saat Ini
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm ms-2" onclick="openLocationPicker()">
                            <i class="fas fa-map me-1"></i>Pilih di Peta
                        </button>
                    </div>

                    <div class="mb-3">
                        <label for="locationDescription" class="form-label">Deskripsi Usaha</label>
                        <textarea class="form-control" id="locationDescription" rows="4" placeholder="Ceritakan tentang usaha Anda, jenis ikan yang dijual, jam buka, dll..."></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="locationOpenTime" class="form-label">Jam Buka</label>
                            <input type="time" class="form-control" id="locationOpenTime">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="locationCloseTime" class="form-label">Jam Tutup</label>
                            <input type="time" class="form-control" id="locationCloseTime">
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="locationActive" checked>
                            <label class="form-check-label" for="locationActive">
                                Lokasi Aktif (dapat dilihat pembeli)
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="saveLocation()">
                    <i class="fas fa-save me-1"></i>Simpan
                </button>
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

    .location-status {
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
</style>
@endpush

@push('scripts')
<script>
    let locations = [];

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

        fetch('/api/seller/locations', {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + getCookie('auth_token'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                locations = data.data || [];
                updateLocationStats();
                displayLocations();
                loading.style.display = 'none';

                if (locations.length === 0) {
                    emptyState.style.display = 'block';
                } else {
                    container.style.display = 'block';
                }
            } else {
                throw new Error(data.message || 'Unknown error');
            }
        })
        .catch(error => {
            console.error('Error loading locations:', error);
            loading.style.display = 'none';
            errorState.style.display = 'block';
        });
    }

    function updateLocationStats() {
        const total = locations.length;
        const active = locations.filter(l => l.status === 'aktif' || l.aktif === true).length;
        const totalVisits = locations.reduce((sum, l) => sum + (l.total_kunjungan || 0), 0);

        document.getElementById('totalLocations').textContent = total;
        document.getElementById('activeLocations').textContent = active;
        document.getElementById('totalVisits').textContent = totalVisits;
    }

    function displayLocations() {
        const grid = document.getElementById('locationsGrid');

        if (locations.length === 0) {
            grid.innerHTML = '';
            return;
        }

        let html = '';
        locations.forEach(location => {
            const isActive = location.status === 'aktif' || location.aktif === true;
            const statusColor = isActive ? 'success' : 'secondary';
            const statusText = isActive ? 'Aktif' : 'Nonaktif';

            html += `
                <div class="col-lg-6 col-xl-4 mb-4">
                    <div class="card location-card">
                        <div class="location-header p-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1 fw-bold">${location.nama_usaha}</h6>
                                    <div class="small opacity-75">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        ${location.kota || location.city || '-'}
                                    </div>
                                </div>
                                <span class="badge bg-${statusColor} location-status">${statusText}</span>
                            </div>
                        </div>

                        <div class="location-info">
                            <div class="mb-2">
                                <div class="location-meta">
                                    <i class="fas fa-map-marked-alt me-2 text-primary"></i>
                                    ${location.alamat || 'Alamat tidak tersedia'}
                                </div>
                            </div>

                            ${location.telepon ? `
                                <div class="mb-2">
                                    <div class="location-meta">
                                        <i class="fas fa-phone me-2 text-success"></i>
                                        ${location.telepon}
                                    </div>
                                </div>
                            ` : ''}

                            ${location.jam_buka && location.jam_tutup ? `
                                <div class="mb-2">
                                    <div class="location-meta">
                                        <i class="fas fa-clock me-2 text-warning"></i>
                                        ${location.jam_buka} - ${location.jam_tutup}
                                    </div>
                                </div>
                            ` : ''}

                            ${location.deskripsi ? `
                                <div class="mb-2">
                                    <div class="location-meta">
                                        <i class="fas fa-info-circle me-2 text-info"></i>
                                        ${location.deskripsi.length > 80 ? location.deskripsi.substring(0, 80) + '...' : location.deskripsi}
                                    </div>
                                </div>
                            ` : ''}

                            <div class="location-meta">
                                <i class="fas fa-eye me-2 text-muted"></i>
                                ${location.total_kunjungan || 0} kunjungan
                            </div>
                        </div>

                        <div class="location-actions">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="small text-muted">
                                    Dibuat: ${formatDate(location.created_at)}
                                </div>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-outline-primary btn-action"
                                            onclick="editLocation(${location.id})" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-success btn-action"
                                            onclick="toggleLocationStatus(${location.id})" title="${isActive ? 'Nonaktifkan' : 'Aktifkan'}">
                                        <i class="fas fa-${isActive ? 'pause' : 'play'}"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-info btn-action"
                                            onclick="viewOnMap(${location.latitude || 0}, ${location.longitude || 0})" title="Lihat di Peta">
                                        <i class="fas fa-map"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-action"
                                            onclick="deleteLocation(${location.id})" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        grid.innerHTML = html;
    }

    function showAddLocationModal() {
        document.getElementById('locationModalTitle').textContent = 'Tambah Lokasi';
        document.getElementById('locationForm').reset();
        document.getElementById('locationId').value = '';
        document.getElementById('locationActive').checked = true;

        const modal = new bootstrap.Modal(document.getElementById('locationModal'));
        modal.show();
    }

    function editLocation(locationId) {
        const location = locations.find(l => l.id === locationId);
        if (!location) return;

        document.getElementById('locationModalTitle').textContent = 'Edit Lokasi';
        document.getElementById('locationId').value = location.id;
        document.getElementById('locationName').value = location.nama_usaha;
        document.getElementById('locationPhone').value = location.telepon || '';
        document.getElementById('locationAddress').value = location.alamat || '';
        document.getElementById('locationCity').value = location.kota || location.city || '';
        document.getElementById('locationProvince').value = location.provinsi || location.province || '';
        document.getElementById('locationLatitude').value = location.latitude || '';
        document.getElementById('locationLongitude').value = location.longitude || '';
        document.getElementById('locationDescription').value = location.deskripsi || '';
        document.getElementById('locationOpenTime').value = location.jam_buka || '';
        document.getElementById('locationCloseTime').value = location.jam_tutup || '';
        document.getElementById('locationActive').checked = location.status === 'aktif' || location.aktif === true;

        const modal = new bootstrap.Modal(document.getElementById('locationModal'));
        modal.show();
    }

    function saveLocation() {
        const form = document.getElementById('locationForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const locationId = document.getElementById('locationId').value;
        const isEdit = locationId !== '';

        const data = {
            nama_usaha: document.getElementById('locationName').value,
            telepon: document.getElementById('locationPhone').value,
            alamat: document.getElementById('locationAddress').value,
            kota: document.getElementById('locationCity').value,
            provinsi: document.getElementById('locationProvince').value,
            latitude: document.getElementById('locationLatitude').value || null,
            longitude: document.getElementById('locationLongitude').value || null,
            deskripsi: document.getElementById('locationDescription').value,
            jam_buka: document.getElementById('locationOpenTime').value,
            jam_tutup: document.getElementById('locationCloseTime').value,
            aktif: document.getElementById('locationActive').checked
        };

        const url = isEdit ? `/api/seller/locations/${locationId}` : '/api/seller/locations';
        const method = isEdit ? 'PUT' : 'POST';

        fetch(url, {
            method: method,
            headers: {
                'Authorization': 'Bearer ' + getCookie('auth_token'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess(isEdit ? 'Lokasi berhasil diperbarui' : 'Lokasi berhasil ditambahkan');
                bootstrap.Modal.getInstance(document.getElementById('locationModal')).hide();
                loadLocations();
            } else {
                showError(data.message || 'Gagal menyimpan lokasi');
            }
        })
        .catch(error => {
            console.error('Error saving location:', error);
            showError('Terjadi kesalahan saat menyimpan lokasi');
        });
    }

    function toggleLocationStatus(locationId) {
        const location = locations.find(l => l.id === locationId);
        if (!location) return;

        const currentStatus = location.status === 'aktif' || location.aktif === true;
        const newStatus = !currentStatus;

        fetch(`/api/seller/locations/${locationId}`, {
            method: 'PUT',
            headers: {
                'Authorization': 'Bearer ' + getCookie('auth_token'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ aktif: newStatus })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess(`Lokasi berhasil ${newStatus ? 'diaktifkan' : 'dinonaktifkan'}`);
                loadLocations();
            } else {
                showError(data.message || 'Gagal mengubah status lokasi');
            }
        })
        .catch(error => {
            console.error('Error toggling location status:', error);
            showError('Terjadi kesalahan saat mengubah status lokasi');
        });
    }

    function deleteLocation(locationId) {
        if (!confirm('Apakah Anda yakin ingin menghapus lokasi ini?')) {
            return;
        }

        fetch(`/api/seller/locations/${locationId}`, {
            method: 'DELETE',
            headers: {
                'Authorization': 'Bearer ' + getCookie('auth_token'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess('Lokasi berhasil dihapus');
                loadLocations();
            } else {
                showError(data.message || 'Gagal menghapus lokasi');
            }
        })
        .catch(error => {
            console.error('Error deleting location:', error);
            showError('Terjadi kesalahan saat menghapus lokasi');
        });
    }

    function getCurrentLocation() {
        if (!navigator.geolocation) {
            showError('Geolocation tidak didukung oleh browser ini');
            return;
        }

        navigator.geolocation.getCurrentPosition(
            function(position) {
                document.getElementById('locationLatitude').value = position.coords.latitude;
                document.getElementById('locationLongitude').value = position.coords.longitude;
                showSuccess('Lokasi berhasil diambil');
            },
            function(error) {
                showError('Gagal mendapatkan lokasi: ' + error.message);
            }
        );
    }

    function openLocationPicker() {
        // This would open a map picker - for now just show info
        showInfo('Fitur pemilih lokasi di peta akan segera tersedia');
    }

    function viewOnMap(lat, lng) {
        if (lat && lng) {
            const url = `https://www.google.com/maps?q=${lat},${lng}`;
            window.open(url, '_blank');
        } else {
            showError('Koordinat lokasi tidak tersedia');
        }
    }

    function formatDate(dateString) {
        return new Date(dateString).toLocaleDateString('id-ID');
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
