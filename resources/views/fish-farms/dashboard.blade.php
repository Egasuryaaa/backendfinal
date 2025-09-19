<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>IwakMart - Dashboard Pemilik Tambak</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #F0F8FF;
            min-height: 100vh;
            line-height: 1.6;
        }

        .header {
            background: linear-gradient(135deg, #1565C0 0%, #1976D2 100%);
            color: white;
            padding: 2rem 0;
            text-align: center;
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            font-weight: 700;
        }

        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .tabs {
            display: flex;
            margin-bottom: 2rem;
            background: white;
            border-radius: 12px;
            padding: 0.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .tab {
            flex: 1;
            padding: 1rem 2rem;
            border: none;
            background: transparent;
            cursor: pointer;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .tab.active {
            background: linear-gradient(135deg, #1565C0 0%, #1976D2 100%);
            color: white;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .action-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .search-box {
            display: flex;
            gap: 1rem;
        }

        .search-input {
            padding: 0.75rem;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            width: 250px;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #1565C0 0%, #1976D2 100%);
            color: white;
        }

        .btn-secondary {
            background: #f8f9fa;
            color: #333;
            border: 2px solid #e9ecef;
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-warning {
            background: #ffc107;
            color: #333;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
        }

        .card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .card-image {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            background: linear-gradient(135deg, #1565C0 0%, #1976D2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            margin-right: 1rem;
            flex-shrink: 0;
        }

        .card-info h3 {
            color: #333;
            margin-bottom: 0.5rem;
            font-size: 1.25rem;
        }

        .card-info p {
            color: #666;
            margin: 0;
        }

        .card-details {
            margin-bottom: 1.5rem;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .detail-label {
            color: #666;
            font-weight: 500;
        }

        .detail-value {
            color: #333;
            font-weight: 600;
        }

        .card-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-menunggu {
            background: #fff3cd;
            color: #856404;
        }

        .status-dikonfirmasi {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-selesai {
            background: #d4edda;
            color: #155724;
        }

        .status-dibatalkan {
            background: #f8d7da;
            color: #721c24;
        }

        .status-aktif {
            background: #d4edda;
            color: #155724;
        }

        .status-tidak-aktif {
            background: #f8d7da;
            color: #721c24;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #666;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .back-button {
            position: fixed;
            top: 2rem;
            left: 2rem;
            width: 50px;
            height: 50px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #1565C0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .back-button:hover {
            transform: scale(1.1);
            color: #1565C0;
        }

        .loading {
            text-align: center;
            padding: 2rem;
            color: #666;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: white;
            font-size: 1.5rem;
        }

        .stat-icon.farms {
            background: linear-gradient(135deg, #1565C0 0%, #1976D2 100%);
        }

        .stat-icon.appointments {
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
        }

        .stat-icon.revenue {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .stat-icon.pending {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .header h1 {
                font-size: 2rem;
            }
            
            .container {
                padding: 1rem;
            }
            
            .grid {
                grid-template-columns: 1fr;
            }
            
            .action-bar {
                flex-direction: column;
                gap: 1rem;
            }
            
            .search-box {
                width: 100%;
            }
            
            .search-input {
                width: 100%;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: 2000;
        }

        .modal-content {
            max-width: 900px;
            width: 95%;
            margin: 3% auto;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #eee;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 600;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .coordinate-input {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <a href="{{ route('fishmarket') }}" class="back-button" title="Kembali ke Beranda">
        <i class="fas fa-arrow-left"></i>
    </a>

    <div class="header">
        <h1><i class="fas fa-fish"></i> Dashboard Pemilik Tambak</h1>
        <p>Kelola tambak ikan dan pantau janji penjemputan Anda</p>
    </div>

    <div class="container">
        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon farms">
                    <i class="fas fa-fish"></i>
                </div>
                <div class="stat-number" id="totalFarms">0</div>
                <div class="stat-label">Total Tambak</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon appointments">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-number" id="totalAppointments">0</div>
                <div class="stat-label">Total Janji</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon pending">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-number" id="pendingAppointments">0</div>
                <div class="stat-label">Menunggu Konfirmasi</div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="tabs">
            <button class="tab active" onclick="switchTab('farms')">
                <i class="fas fa-fish"></i> Tambak Saya
            </button>
            <button class="tab" onclick="switchTab('appointments')">
                <i class="fas fa-calendar-check"></i> Janji Penjemputan
            </button>
        </div>

        <!-- Farms Tab -->
        <div id="farms-tab" class="tab-content active">
            <div class="action-bar">
                <div class="search-box">
                    <input type="text" class="search-input" placeholder="Cari tambak..." id="farmSearch">
                </div>
                <a href="{{ route('fish-farms.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Tambak
                </a>
            </div>

            <div id="farmsContainer" class="grid">
                <div class="loading">
                    <i class="fas fa-spinner fa-spin"></i>
                    <p>Memuat data tambak...</p>
                </div>
            </div>
        </div>

        <!-- Appointments Tab -->
        <div id="appointments-tab" class="tab-content">
            <div class="action-bar">
                <div class="search-box">
                    <select class="search-input" id="statusFilter">
                        <option value="">Semua Status</option>
                        <option value="menunggu">Menunggu</option>
                        <option value="dikonfirmasi">Dikonfirmasi</option>
                        <option value="selesai">Selesai</option>
                        <option value="dibatalkan">Dibatalkan</option>
                    </select>
                    <input type="date" class="search-input" id="dateFilter">
                </div>
                <button class="btn btn-primary" onclick="loadAppointments()">
                    <i class="fas fa-refresh"></i> Refresh
                </button>
            </div>

            <div id="appointmentsContainer" class="grid">
                <div class="loading">
                    <i class="fas fa-spinner fa-spin"></i>
                    <p>Memuat data janji penjemputan...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Farm Modal -->
    <div id="editFarmModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Tambak Ikan</h3>
                <button onclick="closeEditFarmModal()" class="modal-close">&times;</button>
            </div>
            <form id="editFarmForm" style="padding: 1.25rem;">
                <input type="hidden" id="edit_farm_id" name="id" />

                <div class="form-group">
                    <label>Nama Tambak</label>
                    <input type="text" id="edit_nama" class="form-control" required />
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Jenis Ikan</label>
                        <select id="edit_jenis_ikan" class="form-control" required>
                            <option value="">Pilih Jenis Ikan</option>
                            <option value="Lele">Lele</option>
                            <option value="Nila">Nila</option>
                            <option value="Mujair">Mujair</option>
                            <option value="Gurame">Gurame</option>
                            <option value="Patin">Patin</option>
                            <option value="Bawal">Bawal</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Jumlah Bibit</label>
                        <input type="number" id="edit_banyak_bibit" class="form-control" min="1" required />
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Luas Tambak (m²)</label>
                        <input type="number" id="edit_luas_tambak" class="form-control" min="1" step="0.01" required />
                    </div>
                    <div class="form-group">
                        <label>No. Telepon</label>
                        <input type="tel" id="edit_no_telepon" class="form-control" required />
                    </div>
                </div>

                <div class="form-group">
                    <label>Alamat Lengkap</label>
                    <textarea id="edit_alamat" class="form-control" rows="3" required></textarea>
                </div>

                <div class="form-group">
                    <label>Lokasi Koordinat</label>
                    <div class="coordinate-input">
                        <input type="number" id="edit_latitude" class="form-control" step="any" placeholder="Latitude" />
                        <input type="number" id="edit_longitude" class="form-control" step="any" placeholder="Longitude" />
                    </div>
                    <button type="button" class="btn btn-success" onclick="getEditCurrentLocation()" style="margin-bottom: 1rem;">
                        <i class="fas fa-map-marker-alt"></i> Gunakan Lokasi Saat Ini
                    </button>
                    <small class="text-muted">Masukkan koordinat latitude dan longitude untuk lokasi tambak</small>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select id="edit_status" class="form-control">
                        <option value="aktif">Aktif</option>
                        <option value="tidak_aktif">Tidak Aktif</option>
                    </select>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 0.5rem; padding-top: 0.5rem;">
                    <button type="button" class="btn btn-secondary" onclick="closeEditFarmModal()">Batal</button>
                    <button type="submit" class="btn btn-primary" id="editSubmitBtn">
                        <span class="edit-loading" style="display: none;"><i class="fas fa-spinner fa-spin"></i></span>
                        <span class="edit-btn-text"><i class="fas fa-save"></i> Simpan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Farm Modal -->
    <div id="addFarmModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Tambah Tambak Baru</h3>
                <button onclick="closeAddFarmModal()" class="modal-close">&times;</button>
            </div>
            <form id="addFarmForm" style="padding: 1.25rem;">
                <div class="form-group">
                    <label>Nama Tambak</label>
                    <input type="text" id="add_nama" name="nama" class="form-control" required />
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Jenis Ikan</label>
                        <select id="add_jenis_ikan" name="jenis_ikan" class="form-control" required>
                            <option value="">Pilih Jenis Ikan</option>
                            <option value="Lele">Lele</option>
                            <option value="Nila">Nila</option>
                            <option value="Mujair">Mujair</option>
                            <option value="Gurame">Gurame</option>
                            <option value="Patin">Patin</option>
                            <option value="Bawal">Bawal</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Jumlah Bibit</label>
                        <input type="number" id="add_banyak_bibit" name="banyak_bibit" class="form-control" min="1" required />
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Luas Tambak (m²)</label>
                        <input type="number" id="add_luas_tambak" name="luas_tambak" class="form-control" min="1" step="0.01" required />
                    </div>
                    <div class="form-group">
                        <label>No. Telepon</label>
                        <input type="tel" id="add_no_telepon" name="no_telepon" class="form-control" required />
                    </div>
                </div>

                <div class="form-group">
                    <label>Alamat Lengkap</label>
                    <textarea id="add_alamat" name="alamat" class="form-control" rows="3" required></textarea>
                </div>

                <div class="form-group">
                    <label>Lokasi Koordinat</label>
                    <div class="coordinate-input">
                        <input type="number" id="add_latitude" name="latitude" class="form-control" step="any" placeholder="Latitude" />
                        <input type="number" id="add_longitude" name="longitude" class="form-control" step="any" placeholder="Longitude" />
                    </div>
                    <button type="button" class="btn btn-success" onclick="getAddCurrentLocation()" style="margin-bottom: 1rem;">
                        <i class="fas fa-map-marker-alt"></i> Gunakan Lokasi Saat Ini
                    </button>
                    <small class="text-muted">Masukkan koordinat latitude dan longitude untuk lokasi tambak</small>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select id="add_status" name="status" class="form-control">
                        <option value="aktif">Aktif</option>
                        <option value="tidak_aktif">Tidak Aktif</option>
                    </select>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 0.5rem; padding-top: 0.5rem;">
                    <button type="button" class="btn btn-secondary" onclick="closeAddFarmModal()">Batal</button>
                    <button type="submit" class="btn btn-primary" id="addSubmitBtn">
                        <span class="add-loading" style="display: none;"><i class="fas fa-spinner fa-spin"></i></span>
                        <span class="add-btn-text"><i class="fas fa-save"></i> Simpan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="/js/auth.js"></script>
    <script>
        let currentTab = 'farms';
        let farms = [];
        let appointments = [];
        let currentUserId = null;

        document.addEventListener('DOMContentLoaded', function() {
            if (!getToken()) {
                alert('Anda harus login terlebih dahulu');
                window.location.href = '/login';
                return;
            }
            loadDashboardData();
        });

        async function loadDashboardData() {
            try {
                await Promise.all([
                    loadFarms(),
                    loadAppointments(),
                    loadStatistics()
                ]);
                
                // Update dashboard stats after all data is loaded
                updateDashboardStats(farms);
            } catch (error) {
                console.error('Error loading dashboard data:', error);
            }
        }

        async function loadStatistics() {
            try {
                // Get user info
                const userResp = await fetch('/api/user', {
                    headers: {
                        'Authorization': 'Bearer ' + getToken(),
                        'Accept': 'application/json'
                    }
                });

                if (userResp.ok) {
                    const userData = await userResp.json();
                    currentUserId = extractUserId(userData);
                }

                // Get farm statistics
                const farmsResp = await fetch('/api/fish-farms', {
                    headers: {
                        'Authorization': 'Bearer ' + getToken(),
                        'Accept': 'application/json'
                    }
                });

                if (farmsResp.ok) {
                    const farmsData = await farmsResp.json();
                    console.log('Farms data for statistics:', farmsData);
                    
                    // Handle different response structures safely
                    let farmsArray = [];
                    if (farmsData.data) {
                        // If farmsData.data is an array
                        if (Array.isArray(farmsData.data)) {
                            farmsArray = farmsData.data;
                        }
                        // If farmsData.data has a data property (paginated response)
                        else if (farmsData.data.data && Array.isArray(farmsData.data.data)) {
                            farmsArray = farmsData.data.data;
                        }
                        // If farmsData.data is an object but not an array
                        else {
                            farmsArray = [];
                        }
                    } else if (Array.isArray(farmsData)) {
                        // If farmsData itself is an array
                        farmsArray = farmsData;
                    } else {
                        farmsArray = [];
                    }
                    
                    const userFarms = farmsArray.filter(farm => farm.user_id == currentUserId) || [];
                    
                    const totalFarmsElement = document.getElementById('totalFarms');
                    if (totalFarmsElement) {
                        totalFarmsElement.textContent = userFarms.length;
                    }
                }

                // Get appointment statistics
                const appointmentsResp = await fetch('/api/appointments', {
                    headers: {
                        'Authorization': 'Bearer ' + getToken(),
                        'Accept': 'application/json'
                    }
                });

                if (appointmentsResp.ok) {
                    const appointmentsData = await appointmentsResp.json();
                    console.log('Appointments data for statistics:', appointmentsData);
                    
                    // Handle different response structures safely
                    let appointmentsArray = [];
                    if (appointmentsData.data) {
                        // If appointmentsData.data is an array
                        if (Array.isArray(appointmentsData.data)) {
                            appointmentsArray = appointmentsData.data;
                        }
                        // If appointmentsData.data has a data property (paginated response)
                        else if (appointmentsData.data.data && Array.isArray(appointmentsData.data.data)) {
                            appointmentsArray = appointmentsData.data.data;
                        }
                        // If appointmentsData.data is an object but not an array
                        else {
                            appointmentsArray = [];
                        }
                    } else if (Array.isArray(appointmentsData)) {
                        // If appointmentsData itself is an array
                        appointmentsArray = appointmentsData;
                    } else {
                        appointmentsArray = [];
                    }
                    
                    const userAppointments = appointmentsArray.filter(appointment => 
                        appointment.user_id == currentUserId || 
                        appointment.fish_farm?.user_id == currentUserId
                    ) || [];
                    
                    const totalAppointmentsElement = document.getElementById('totalAppointments');
                    if (totalAppointmentsElement) {
                        totalAppointmentsElement.textContent = userAppointments.length;
                    }
                    
                    const pendingCount = userAppointments.filter(app => app && app.status === 'menunggu').length;
                    const pendingAppointmentsElement = document.getElementById('pendingAppointments');
                    if (pendingAppointmentsElement) {
                        pendingAppointmentsElement.textContent = pendingCount;
                    }
                }

            } catch (error) {
                console.error('Error loading statistics:', error);
            }
        }

        function extractUserId(userData) {
            if (!userData) return null;
            if (typeof userData.id !== 'undefined') return userData.id;
            if (userData.data && typeof userData.data.id !== 'undefined') return userData.data.id;
            if (userData.success && userData.data && typeof userData.data.id !== 'undefined') return userData.data.id;
            if (userData.user && typeof userData.user.id !== 'undefined') return userData.user.id;
            return null;
        }

        function switchTab(tab) {
            document.querySelectorAll('.tab').forEach(btn => btn.classList.remove('active'));
            const btn = document.querySelector(`[onclick="switchTab('${tab}')"]`);
            if (btn) btn.classList.add('active');

            document.querySelectorAll('.tab-content').forEach(sec => sec.classList.remove('active'));
            const section = document.getElementById(`${tab}-tab`);
            if (section) section.classList.add('active');

            if (tab === 'farms' && farms.length === 0) loadFarms();
            else if (tab === 'appointments') {
                // Make sure farms are loaded first before loading appointments
                if (farms.length === 0) {
                    loadFarms().then(() => {
                        if (appointments.length === 0) loadAppointments();
                    });
                } else if (appointments.length === 0) {
                    loadAppointments();
                }
            }
        }

        async function loadFarms() {
            try {
                console.log('Loading user farms...');
                
                const response = await fetch('/api/fish-farms?user_only=true', {
                    headers: {
                        'Authorization': 'Bearer ' + getToken(),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to fetch farms');
                }

                const result = await response.json();
                console.log('Fish farms API response:', result);
                
                // Handle different response structures
                farms = result.data?.data || result.data || [];
                console.log('My farms:', farms);
                
                displayFarms(farms);
                // Don't update dashboard stats here, let it be updated after all data is loaded
            } catch (error) {
                console.error('Error loading farms:', error);
                displayFarmsError();
            }
        }

        function displayFarms(farmsList) {
            const container = document.getElementById('farmsContainer');
            if (!Array.isArray(farmsList) || farmsList.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-fish"></i>
                        <h3>Belum Ada Tambak</h3>
                        <p>Daftarkan tambak ikan pertama Anda untuk mulai menjual</p>
                        <button class="btn btn-primary" onclick="openAddFarmModal()">
                            <i class="fas fa-plus"></i> Tambah Tambak
                        </button>
                    </div>
                `;
                return;
            }

            container.innerHTML = farmsList.map(farm => `
                <div class="card">
                    <div class="card-header">
                        <div class="card-image">
                            ${farm.foto ? `<img src="/storage/${farm.foto}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 12px;">` : '<i class="fas fa-fish"></i>'}
                        </div>
                        <div class="card-info">
                            <h3>${farm.nama}</h3>
                            <p><i class="fas fa-map-marker-alt"></i> ${(farm.alamat || '').substring(0, 30)}...</p>
                            <span class="status-badge status-${farm.status}">${farm.status}</span>
                        </div>
                    </div>
                    <div class="card-details">
                        <div class="detail-item">
                            <span class="detail-label">Jenis Ikan</span>
                            <span class="detail-value">${farm.jenis_ikan}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Jumlah Bibit</span>
                            <span class="detail-value">${farm.banyak_bibit?.toLocaleString() || 0} ekor</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Luas Tambak</span>
                            <span class="detail-value">${farm.luas_tambak || 0} m²</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Telepon</span>
                            <span class="detail-value">${farm.no_telepon || '-'}</span>
                        </div>
                    </div>
                    <div class="card-actions">
                        <button class="btn btn-primary" onclick="viewFarmAppointments(${farm.id})">
                            <i class="fas fa-calendar"></i> Lihat Janji
                        </button>
                        <button class="btn btn-warning" onclick="openEditFarmModal(${farm.id})">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-danger" onclick="deleteFarm(${farm.id})">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>
            `).join('');
        }

        // Update dashboard statistics based on farms data
        function updateDashboardStats(farmsList) {
            // Ensure farmsList is an array
            if (!Array.isArray(farmsList)) {
                farmsList = [];
            }
            
            // Ensure appointments is an array
            if (!Array.isArray(appointments)) {
                appointments = [];
            }
            
            // Update total farms
            const totalFarmsElement = document.getElementById('totalFarms');
            if (totalFarmsElement) {
                totalFarmsElement.textContent = farmsList.length;
            }
            
            // Update total appointments (we have this element)
            const totalAppointmentsElement = document.getElementById('totalAppointments');
            if (totalAppointmentsElement) {
                totalAppointmentsElement.textContent = appointments.length;
            }
            
            // Update pending appointments
            const pendingAppointmentsElement = document.getElementById('pendingAppointments');
            if (pendingAppointmentsElement) {
                const pendingCount = appointments.filter(a => a && a.status === 'menunggu').length;
                pendingAppointmentsElement.textContent = pendingCount;
            }
        }

        function displayFarmsError() {
            document.getElementById('farmsContainer').innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h3>Gagal Memuat Data</h3>
                    <p>Terjadi kesalahan saat memuat data tambak</p>
                    <button class="btn btn-primary" onclick="loadFarms()">
                        <i class="fas fa-refresh"></i> Coba Lagi
                    </button>
                </div>
            `;
        }

        async function loadAppointments() {
            try {
                console.log('Loading appointments for fish farm owner...');
                const token = getToken();
                console.log('Using token:', token ? 'Token found' : 'No token');
                
                // Get appointments using the general endpoint (for fish farm owners)
                const response = await fetch('/api/appointments', {
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                if (!response.ok) {
                    const errorText = await response.text();
                    console.error('API Error:', response.status, errorText);
                    throw new Error(`Failed to fetch appointments: ${response.status} ${response.statusText}`);
                }

                const result = await response.json();
                console.log('Appointments API response:', result);
                
                // Handle different response structures safely
                let appointmentsData = [];
                if (result.data) {
                    // If result.data is an array
                    if (Array.isArray(result.data)) {
                        appointmentsData = result.data;
                    }
                    // If result.data has a data property (paginated response)
                    else if (result.data.data && Array.isArray(result.data.data)) {
                        appointmentsData = result.data.data;
                    }
                    // If result.data is an object but not an array
                    else {
                        appointmentsData = [];
                    }
                } else if (Array.isArray(result)) {
                    // If result itself is an array
                    appointmentsData = result;
                } else {
                    appointmentsData = [];
                }
                
                // Filter appointments to only show those for fish farms owned by current user
                const userOwnedFarmIds = farms.map(farm => farm.id);
                const userAppointments = appointmentsData.filter(appointment => {
                    // Check if appointment is for user's fish farm
                    return appointment.fish_farm_id && userOwnedFarmIds.includes(appointment.fish_farm_id);
                });
                
                appointments = userAppointments || [];
                console.log('Loaded user appointments:', appointments);
                displayAppointments(appointments);
            } catch (error) {
                console.error('Error loading appointments:', error);
                displayAppointmentsError();
            }
        }

        function displayAppointments(appointmentsList) {
            const container = document.getElementById('appointmentsContainer');
            
            if (appointmentsList.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-calendar-times"></i>
                        <h3>Belum Ada Janji Penjemputan</h3>
                        <p>Janji penjemputan dari pengepul akan muncul di sini</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = appointmentsList.map(appointment => {
                // Get fish farm name from appointment
                let fishFarmName = 'Tambak Tidak Diketahui';
                if (appointment.fish_farm && appointment.fish_farm.nama) {
                    fishFarmName = appointment.fish_farm.nama;
                } else if (appointment.fish_farm_id) {
                    const fishFarm = farms.find(farm => farm.id == appointment.fish_farm_id);
                    fishFarmName = fishFarm ? fishFarm.nama : fishFarmName;
                }
                
                // Get collector name
                let collectorName = 'Pengepul Tidak Diketahui';
                if (appointment.collector && appointment.collector.nama) {
                    collectorName = appointment.collector.nama;
                }
                
                // Format date
                let appointmentDate = 'Tanggal belum ditentukan';
                if (appointment.tanggal_janji) {
                    try {
                        appointmentDate = new Date(appointment.tanggal_janji).toLocaleDateString('id-ID', {
                            weekday: 'long',
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        });
                        if (appointment.waktu_janji) {
                            appointmentDate += ' pukul ' + appointment.waktu_janji;
                        }
                    } catch (e) {
                        appointmentDate = appointment.tanggal_janji;
                    }
                }
                
                // Get status text
                function getStatusText(status) {
                    switch(status) {
                        case 'menunggu': return 'Menunggu Konfirmasi';
                        case 'dikonfirmasi': return 'Dikonfirmasi';
                        case 'selesai': return 'Selesai';
                        case 'dibatalkan': return 'Dibatalkan';
                        default: return 'Menunggu';
                    }
                }
                
                const statusClass = appointment.status ? `status-${appointment.status}` : 'status-menunggu';
                const statusText = getStatusText(appointment.status);
                
                return `
                    <div class="card">
                        <div class="card-header">
                            <div class="card-image" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="card-info">
                                <h3>${fishFarmName}</h3>
                                <p><i class="fas fa-truck"></i> Pengepul: ${collectorName}</p>
                                <p><i class="fas fa-clock"></i> ${appointmentDate}</p>
                                <span class="status-badge ${statusClass}">${statusText}</span>
                            </div>
                        </div>
                        <div class="card-details">
                            <div class="detail-item">
                                <span class="detail-label">Estimasi Berat</span>
                                <span class="detail-value">${appointment.estimated_weight || 0} kg</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Harga per Kg</span>
                                <span class="detail-value">Rp ${parseInt(appointment.price_per_kg || 0).toLocaleString()}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Total Estimasi</span>
                                <span class="detail-value">Rp ${parseInt((appointment.estimated_weight || 0) * (appointment.price_per_kg || 0)).toLocaleString()}</span>
                            </div>
                            ${appointment.pesan_pemilik ? `
                                <div class="detail-item">
                                    <span class="detail-label">Pesan Anda</span>
                                    <span class="detail-value">${appointment.pesan_pemilik}</span>
                                </div>
                            ` : ''}
                            ${appointment.catatan ? `
                                <div class="detail-item">
                                    <span class="detail-label">Catatan Pengepul</span>
                                    <span class="detail-value">${appointment.catatan}</span>
                                </div>
                            ` : ''}
                        </div>
                        
                        ${appointment.whatsapp_summary ? `
                            <div class="whatsapp-summary" style="background: #e8f5e8; border: 1px solid #28a745; border-radius: 8px; padding: 1rem; margin: 1rem 0;">
                                <h4 style="color: #28a745; margin-bottom: 0.5rem;">
                                    <i class="fab fa-whatsapp"></i> Summary WhatsApp
                                </h4>
                                ${(() => {
                                    try {
                                        const summary = typeof appointment.whatsapp_summary === 'string' 
                                            ? JSON.parse(appointment.whatsapp_summary) 
                                            : appointment.whatsapp_summary;
                                        return `
                                            <p style="margin: 0.25rem 0; font-size: 0.9rem;"><strong>Tanggal:</strong> ${summary.tanggal || '-'}</p>
                                            <p style="margin: 0.25rem 0; font-size: 0.9rem;"><strong>Berat Aktual:</strong> ${summary.berat_aktual || 0} kg</p>
                                            <p style="margin: 0.25rem 0; font-size: 0.9rem;"><strong>Total Harga:</strong> Rp ${parseInt(summary.total_harga || 0).toLocaleString()}</p>
                                            <p style="margin: 0.25rem 0; font-size: 0.9rem;"><strong>Status:</strong> ${summary.status || 'Selesai'}</p>
                                        `;
                                    } catch (e) {
                                        return '<p style="margin: 0; font-size: 0.9rem;">Detail transaksi tersedia</p>';
                                    }
                                })()}
                            </div>
                        ` : ''}
                        
                        <div class="card-actions">
                            ${appointment.status === 'menunggu' ? `
                                <button class="btn btn-danger" onclick="cancelAppointment(${appointment.id})">
                                    <i class="fas fa-times"></i> Batalkan
                                </button>
                            ` : ''}
                            ${appointment.collector && appointment.collector.no_telepon ? `
                                <button class="btn btn-success" onclick="contactCollector('${appointment.collector.no_telepon}', '${collectorName}')">
                                    <i class="fab fa-whatsapp"></i> Hubungi Pengepul
                                </button>
                            ` : ''}
                            <span class="status-text" style="color: #666; font-size: 0.9rem;">
                                Status: ${statusText}
                            </span>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function displayAppointmentsError() {
            document.getElementById('appointmentsContainer').innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h3>Gagal Memuat Data</h3>
                    <p>Terjadi kesalahan saat memuat data janji penjemputan</p>
                    <button class="btn btn-primary" onclick="loadAppointments()">
                        <i class="fas fa-refresh"></i> Coba Lagi
                    </button>
                </div>
            `;
        }

        function viewFarmAppointments(farmId) {
            switchTab('appointments');
            // Filter appointments for this farm
            const farmAppointments = appointments.filter(app => app.fish_farm_id == farmId);
            displayAppointments(farmAppointments);
        }

        // Cancel appointment function
        async function cancelAppointment(appointmentId) {
            if (!confirm('Apakah Anda yakin ingin membatalkan janji penjemputan ini?')) {
                return;
            }

            try {
                const response = await fetch(`/api/appointments/${appointmentId}/status`, {
                    method: 'PUT',
                    headers: {
                        'Authorization': 'Bearer ' + getToken(),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        status: 'dibatalkan'
                    })
                });

                if (!response.ok) {
                    throw new Error('Failed to cancel appointment');
                }

                const result = await response.json();
                
                if (result.success) {
                    alert('Janji penjemputan berhasil dibatalkan');
                    loadAppointments(); // Reload appointments
                } else {
                    alert('Gagal membatalkan janji penjemputan: ' + (result.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error cancelling appointment:', error);
                alert('Terjadi kesalahan saat membatalkan janji penjemputan');
            }
        }

        // Contact collector function
        function contactCollector(phoneNumber, collectorName) {
            if (!phoneNumber) {
                alert('Nomor telepon pengepul tidak tersedia');
                return;
            }

            // Format phone number for WhatsApp
            let formattedPhone = phoneNumber.replace(/[^0-9]/g, '');
            if (formattedPhone.startsWith('0')) {
                formattedPhone = '62' + formattedPhone.substring(1);
            } else if (!formattedPhone.startsWith('62')) {
                formattedPhone = '62' + formattedPhone;
            }

            const message = encodeURIComponent(`Halo ${collectorName}, saya ingin menanyakan tentang janji penjemputan ikan. Terima kasih.`);
            const whatsappUrl = `https://wa.me/${formattedPhone}?text=${message}`;
            
            window.open(whatsappUrl, '_blank');
        }

        function openEditFarmModal(id) {
            const farm = farms.find(f => String(f.id) === String(id));
            if (!farm) return;
            
            document.getElementById('edit_farm_id').value = farm.id;
            document.getElementById('edit_nama').value = farm.nama || '';
            document.getElementById('edit_jenis_ikan').value = farm.jenis_ikan || '';
            document.getElementById('edit_banyak_bibit').value = farm.banyak_bibit || '';
            document.getElementById('edit_luas_tambak').value = farm.luas_tambak || '';
            document.getElementById('edit_no_telepon').value = farm.no_telepon || '';
            document.getElementById('edit_alamat').value = farm.alamat || '';
            document.getElementById('edit_status').value = farm.status || 'aktif';
            
            // Fill coordinates
            let lat = null, lng = null;
            if (farm.lokasi_koordinat) {
                if (typeof farm.lokasi_koordinat === 'string') {
                    const coords = farm.lokasi_koordinat.split(',');
                    if (coords.length === 2) {
                        lat = parseFloat(coords[0]);
                        lng = parseFloat(coords[1]);
                    }
                } else if (farm.lokasi_koordinat.lat && farm.lokasi_koordinat.lng) {
                    lat = parseFloat(farm.lokasi_koordinat.lat);
                    lng = parseFloat(farm.lokasi_koordinat.lng);
                }
            }
            
            document.getElementById('edit_latitude').value = lat || '';
            document.getElementById('edit_longitude').value = lng || '';
            
            document.getElementById('editFarmModal').style.display = 'block';
        }

        function openAddFarmModal() {
            // Clear form
            const form = document.getElementById('addFarmForm');
            if (form) {
                form.reset();
            }
            
            document.getElementById('addFarmModal').style.display = 'block';
        }

        function initEditMap(lat = -6.2088, lng = 106.8456) {
            try {
                if (typeof google === 'undefined' || !google.maps) {
                    document.getElementById('editMapContainer').innerHTML = `
                        <div style="text-align: center; padding: 2rem;">
                            <i class="fas fa-map fa-2x" style="color: #666; margin-bottom: 1rem;"></i>
                            <p>Peta tidak tersedia. Masukkan koordinat secara manual atau gunakan tombol lokasi.</p>
                        </div>
                    `;
                    return;
                }
                
                const mapContainer = document.getElementById('editMapContainer');
                if (!mapContainer) return;
                
                const location = { lat: lat || -6.2088, lng: lng || 106.8456 };
                
                editMap = new google.maps.Map(mapContainer, {
                    zoom: 13,
                    center: location,
                    mapTypeId: 'roadmap'
                });

                editMarker = new google.maps.Marker({
                    position: location,
                    map: editMap,
                    draggable: true,
                    title: 'Lokasi Tambak'
                });

                editMarker.addListener('dragend', function() {
                    const position = editMarker.getPosition();
                    document.getElementById('edit_latitude').value = position.lat();
                    document.getElementById('edit_longitude').value = position.lng();
                });

                document.getElementById('edit_latitude').addEventListener('input', updateEditMarkerFromCoordinates);
                document.getElementById('edit_longitude').addEventListener('input', updateEditMarkerFromCoordinates);
                
            } catch (error) {
                console.log('Error initializing edit map:', error);
                document.getElementById('editMapContainer').innerHTML = `
                    <div style="text-align: center; padding: 2rem;">
                        <i class="fas fa-map fa-2x" style="color: #666; margin-bottom: 1rem;"></i>
                        <p>Masukkan koordinat secara manual atau gunakan tombol lokasi</p>
                    </div>
                `;
            }
        }

        function updateEditMarkerFromCoordinates() {
            if (!editMap || !editMarker) return;
            
            const lat = parseFloat(document.getElementById('edit_latitude').value);
            const lng = parseFloat(document.getElementById('edit_longitude').value);

            if (!isNaN(lat) && !isNaN(lng)) {
                const position = { lat: lat, lng: lng };
                editMarker.setPosition(position);
                editMap.setCenter(position);
            }
        }

        function getEditCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    
                    document.getElementById('edit_latitude').value = lat;
                    document.getElementById('edit_longitude').value = lng;
                    
                    if (editMarker && editMap) {
                        const newPosition = { lat: lat, lng: lng };
                        editMarker.setPosition(newPosition);
                        editMap.setCenter(newPosition);
                        editMap.setZoom(15);
                    }
                    
                    alert('Lokasi berhasil diperoleh!');
                }, function(error) {
                    alert('Gagal mendapatkan lokasi: ' + error.message);
                });
            } else {
                alert('Geolocation tidak didukung oleh browser ini.');
            }
        }

        function closeEditFarmModal() {
            document.getElementById('editFarmModal').style.display = 'none';
            if (editMap) {
                google.maps.event.clearInstanceListeners(editMap);
            }
            if (editMarker) {
                google.maps.event.clearInstanceListeners(editMarker);
            }
        }

        document.getElementById('editFarmForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const editLoading = document.querySelector('.edit-loading');
            const editBtnText = document.querySelector('.edit-btn-text');
            const editSubmitBtn = document.getElementById('editSubmitBtn');
            
            editLoading.style.display = 'inline-block';
            editBtnText.style.display = 'none';
            editSubmitBtn.disabled = true;
            
            try {
                const id = document.getElementById('edit_farm_id').value;
                
                const latitude = document.getElementById('edit_latitude').value;
                const longitude = document.getElementById('edit_longitude').value;
                let lokasi_koordinat = null;
                
                if (latitude && longitude) {
                    const lat = parseFloat(latitude);
                    const lng = parseFloat(longitude);
                    
                    if (isNaN(lat) || isNaN(lng) || lat < -90 || lat > 90 || lng < -180 || lng > 180) {
                        throw new Error('Koordinat tidak valid');
                    }
                    
                    lokasi_koordinat = {
                        lat: lat,
                        lng: lng
                    };
                }
                
                const payload = {
                    nama: document.getElementById('edit_nama').value,
                    jenis_ikan: document.getElementById('edit_jenis_ikan').value,
                    banyak_bibit: parseInt(document.getElementById('edit_banyak_bibit').value),
                    luas_tambak: parseFloat(document.getElementById('edit_luas_tambak').value),
                    no_telepon: document.getElementById('edit_no_telepon').value,
                    alamat: document.getElementById('edit_alamat').value,
                    status: document.getElementById('edit_status').value
                };

                if (lokasi_koordinat) {
                    payload.lokasi_koordinat = lokasi_koordinat;
                }

                const resp = await fetch(`/api/fish-farms/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Authorization': 'Bearer ' + getToken(),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(payload)
                });

                if (!resp.ok) {
                    let msg = 'Gagal memperbarui data tambak';
                    try { 
                        const j = await resp.json(); 
                        if (j.errors) {
                            const errorMessages = Object.values(j.errors).flat().join(', ');
                            msg = `Validation failed: ${errorMessages}`;
                        } else {
                            msg = j.message || msg; 
                        }
                    } catch {}
                    throw new Error(msg);
                }

                alert('Data tambak berhasil diperbarui!');
                closeEditFarmModal();
                await loadFarms();
                await loadStatistics();
            } catch (err) {
                console.error('Update error:', err);
                alert('Terjadi kesalahan: ' + err.message);
            } finally {
                editLoading.style.display = 'none';
                editBtnText.style.display = 'inline-flex';
                editSubmitBtn.disabled = false;
            }
        });

        async function deleteFarm(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus tambak ini? Tindakan ini tidak dapat dibatalkan.')) {
                return;
            }

            try {
                const response = await fetch(`/api/fish-farms/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': 'Bearer ' + getToken(),
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (response.ok) {
                    alert('Tambak berhasil dihapus!');
                    await loadFarms();
                    await loadStatistics();
                } else {
                    let errorMessage = 'Gagal menghapus tambak';
                    try {
                        const errorData = await response.json();
                        errorMessage = errorData.message || errorMessage;
                    } catch (e) {
                        console.warn('Could not parse error response:', e);
                    }
                    alert(errorMessage);
                }
            } catch (error) {
                console.error('Error deleting farm:', error);
                alert('Terjadi kesalahan saat menghapus tambak: ' + error.message);
            }
        }

        // Add Farm Modal Functions
        function closeAddFarmModal() {
            document.getElementById('addFarmModal').style.display = 'none';
        }

        async function getAddCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    
                    document.getElementById('add_latitude').value = lat;
                    document.getElementById('add_longitude').value = lng;
                    
                    alert(`Lokasi berhasil didapatkan: ${lat}, ${lng}`);
                }, function(error) {
                    alert('Gagal mendapatkan lokasi: ' + error.message);
                });
            } else {
                alert('Geolocation tidak didukung oleh browser ini.');
            }
        }

        function getEditCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    
                    document.getElementById('edit_latitude').value = lat;
                    document.getElementById('edit_longitude').value = lng;
                    
                    alert(`Lokasi berhasil didapatkan: ${lat}, ${lng}`);
                }, function(error) {
                    alert('Gagal mendapatkan lokasi: ' + error.message);
                });
            } else {
                alert('Geolocation tidak didukung oleh browser ini.');
            }
        }

        // Add Farm Form Submission
        document.getElementById('addFarmForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('addSubmitBtn');
            const loadingSpan = submitBtn.querySelector('.add-loading');
            const btnText = submitBtn.querySelector('.add-btn-text');
            
            loadingSpan.style.display = 'inline';
            btnText.style.display = 'none';
            submitBtn.disabled = true;
            
            try {
                const formData = new FormData();
                formData.append('nama', document.getElementById('add_nama').value);
                formData.append('jenis_ikan', document.getElementById('add_jenis_ikan').value);
                formData.append('banyak_bibit', document.getElementById('add_banyak_bibit').value);
                formData.append('luas_tambak', document.getElementById('add_luas_tambak').value);
                formData.append('no_telepon', document.getElementById('add_no_telepon').value);
                formData.append('alamat', document.getElementById('add_alamat').value);
                formData.append('status', document.getElementById('add_status').value);
                
                const lat = document.getElementById('add_latitude').value;
                const lng = document.getElementById('add_longitude').value;
                if (lat && lng) {
                    formData.append('lokasi_koordinat', `${lat},${lng}`);
                }
                
                const response = await fetch('/api/fish-farms', {
                    method: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + getToken(),
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                });
                
                if (response.ok) {
                    const result = await response.json();
                    alert('Tambak berhasil ditambahkan!');
                    closeAddFarmModal();
                    loadFarms(); // Reload farms
                } else {
                    const errorData = await response.json();
                    alert('Gagal menambahkan tambak: ' + (errorData.message || 'Terjadi kesalahan'));
                }
            } catch (error) {
                console.error('Error adding farm:', error);
                alert('Terjadi kesalahan saat menambahkan tambak: ' + error.message);
            } finally {
                loadingSpan.style.display = 'none';
                btnText.style.display = 'inline';
                submitBtn.disabled = false;
            }
        });

        // Close modal on ESC and backdrop click
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeEditFarmModal();
                closeAddFarmModal();
            }
        });
        
        document.getElementById('editFarmModal').addEventListener('click', (e) => {
            if (e.target.id === 'editFarmModal') closeEditFarmModal();
        });
        
        document.getElementById('addFarmModal').addEventListener('click', (e) => {
            if (e.target.id === 'addFarmModal') closeAddFarmModal();
        });
    </script>
</body>
</html>