<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>IwakMart - Dashboard Pengepul</title>
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
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
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
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
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
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
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
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
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

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-confirmed {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-completed {
            background: #d4edda;
            color: #155724;
        }

        .status-cancelled {
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
            color: #3b82f6;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .back-button:hover {
            transform: scale(1.1);
            color: #3b82f6;
        }

        .loading {
            text-align: center;
            padding: 2rem;
            color: #666;
        }

        .appointment-details {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin: 1rem 0;
        }

        .whatsapp-summary {
            background: #e8f5e8;
            border: 1px solid #28a745;
            border-radius: 8px;
            padding: 1rem;
            margin: 1rem 0;
        }

        .whatsapp-summary h4 {
            color: #28a745;
            margin-bottom: 0.5rem;
        }

        .whatsapp-summary p {
            margin: 0.25rem 0;
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
        }
    </style>
</head>
<body>
    <a href="{{ route('fishmarket') }}" class="back-button" title="Kembali ke Beranda">
        <i class="fas fa-arrow-left"></i>
    </a>

    <div class="header">
        <h1><i class="fas fa-truck"></i> Dashboard Pengepul</h1>
        <p>Kelola usaha pengepul dan janji penjemputan ikan</p>
    </div>

    <div class="container">
        <!-- Tabs -->
        <div class="tabs">
            <button class="tab active" onclick="switchTab('collectors')">
                <i class="fas fa-store"></i> Usaha Saya
            </button>
            <button class="tab" onclick="switchTab('appointments')">
                <i class="fas fa-calendar-check"></i> Janji Penjemputan
            </button>
            <button class="tab" onclick="switchTab('statistics')">
                <i class="fas fa-chart-line"></i> Statistik
            </button>
        </div>

        <!-- Collectors Tab -->
        <div id="collectors-tab" class="tab-content active">
            <div class="action-bar">
                <div class="search-box">
                    <input type="text" class="search-input" placeholder="Cari usaha..." id="collectorSearch">
                    <button class="btn btn-secondary" onclick="searchCollectors()">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
                <a href="{{ route('collectors.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Daftar Usaha Baru
                </a>
            </div>

            <div id="collectorsContainer" class="grid">
                <div class="loading">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                    <p>Memuat data usaha...</p>
                </div>
            </div>
        </div>

        <!-- Appointments Tab -->
        <div id="appointments-tab" class="tab-content">
            <div class="action-bar">
                <div class="search-box">
                    <select class="search-input" id="statusFilter">
                        <option value="">Semua Status</option>
                        <option value="pending">Menunggu</option>
                        <option value="confirmed">Dikonfirmasi</option>
                        <option value="completed">Selesai</option>
                        <option value="cancelled">Dibatalkan</option>
                    </select>
                    <input type="date" class="search-input" id="dateFilter">
                    <button class="btn btn-secondary" onclick="filterAppointments()">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
                <button class="btn btn-primary" onclick="loadAppointments()">
                    <i class="fas fa-refresh"></i> Muat Ulang
                </button>
            </div>

            <div id="appointmentsContainer" class="grid">
                <div class="loading">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                    <p>Memuat data janji penjemputan...</p>
                </div>
            </div>
        </div>

        <!-- Statistics Tab -->
        <div id="statistics-tab" class="tab-content">
            <div class="grid">
                <div class="card">
                    <h3><i class="fas fa-chart-bar"></i> Ringkasan Bulanan</h3>
                    <div class="appointment-details">
                        <div class="detail-item">
                            <span class="detail-label">Janji Diterima</span>
                            <span class="detail-value" id="stat-accepted">0</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Janji Selesai</span>
                            <span class="detail-value" id="stat-completed">0</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Total Penjemputan</span>
                            <span class="detail-value" id="stat-total-kg">0 kg</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Estimasi Pendapatan</span>
                            <span class="detail-value" id="stat-revenue">Rp 0</span>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <h3><i class="fas fa-fish"></i> Jenis Ikan Populer</h3>
                    <div id="fishTypesStats" class="appointment-details">
                        <p>Memuat data...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Collector Modal -->
    <div id="editCollectorModal" class="modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); z-index:2000;">
      <div class="modal-content" style="max-width:720px; width:95%; margin:5% auto; background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 10px 30px rgba(0,0,0,.2);">
        <div class="modal-header" style="display:flex; align-items:center; justify-content:space-between; padding:1rem 1.25rem; border-bottom:1px solid #eee;">
          <h3 style="margin:0; font-size:1.25rem;">Edit Usaha Pengepul</h3>
          <button onclick="closeEditCollectorModal()" class="modal-close" style="background:none; border:none; font-size:1.5rem; cursor:pointer;">&times;</button>
        </div>
        <form id="editCollectorForm" style="padding:1.25rem;">
          <input type="hidden" id="edit_collector_id" name="id" />

          <div class="form-group" style="margin-bottom:1rem;">
            <label>Nama Usaha</label>
            <input type="text" id="edit_nama" class="form-control" style="width:100%; padding:.75rem; border:1px solid #e5e7eb; border-radius:8px;" required />
          </div>

          <div class="form-group" style="margin-bottom:1rem;">
            <label>Alamat</label>
            <input type="text" id="edit_alamat" class="form-control" style="width:100%; padding:.75rem; border:1px solid #e5e7eb; border-radius:8px;" required />
          </div>

          <div class="form-group" style="margin-bottom:1rem; display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
            <div>
              <label>Harga per Kg (Rp)</label>
              <input type="number" id="edit_rate_per_kg" class="form-control" min="0" step="100" style="width:100%; padding:.75rem; border:1px solid #e5e7eb; border-radius:8px;" />
            </div>
            <div>
              <label>Kapasitas Maksimal (kg/hari)</label>
              <input type="number" id="edit_kapasitas_maksimal" class="form-control" min="0" step="0.01" style="width:100%; padding:.75rem; border:1px solid #e5e7eb; border-radius:8px;" />
            </div>
          </div>

          <div class="form-group" style="margin-bottom:1rem; display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
            <div>
              <label>Jam Operasional Mulai</label>
              <input type="time" id="edit_jam_mulai" class="form-control" style="width:100%; padding:.75rem; border:1px solid #e5e7eb; border-radius:8px;" />
            </div>
            <div>
              <label>Jam Operasional Selesai</label>
              <input type="time" id="edit_jam_selesai" class="form-control" style="width:100%; padding:.75rem; border:1px solid #e5e7eb; border-radius:8px;" />
            </div>
          </div>

          <div class="form-group" style="margin-bottom:1rem;">
            <label>Status</label>
            <select id="edit_status" class="form-control" style="width:100%; padding:.75rem; border:1px solid #e5e7eb; border-radius:8px;">
              <option value="aktif">aktif</option>
              <option value="tidak_aktif">tidak_aktif</option>
            </select>
          </div>

          <div class="form-group" style="margin-bottom:1rem;">
            <label>Deskripsi</label>
            <textarea id="edit_deskripsi" rows="3" class="form-control" style="width:100%; padding:.75rem; border:1px solid #e5e7eb; border-radius:8px;"></textarea>
          </div>

          <div style="display:flex; justify-content:flex-end; gap:.5rem; padding-top:.5rem;">
            <button type="button" class="btn btn-secondary" onclick="closeEditCollectorModal()">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
        </form>
      </div>
    </div>

    <script src="/js/auth.js"></script>
    <script>
        let currentTab = 'collectors';
        let collectors = [];
        let appointments = [];
        let currentUserId = null;

        document.addEventListener('DOMContentLoaded', function() {
            if (!getToken()) {
                alert('Anda harus login terlebih dahulu');
                window.location.href = '/login';
                return;
            }
            loadCollectors();
        });

        function normalizeArrayResponse(result) {
            if (Array.isArray(result)) return result;
            if (result && Array.isArray(result.data)) return result.data;
            if (result && result.data && Array.isArray(result.data.data)) return result.data.data;
            if (result && result.data && result.data.items && Array.isArray(result.data.items)) return result.data.items;
            return [];
        }

        // Ensure switchTab exists for tab navigation
        function switchTab(tab) {
            // Toggle tab button active state
            document.querySelectorAll('.tab').forEach(btn => btn.classList.remove('active'));
            const btn = document.querySelector(`[onclick="switchTab('${tab}')"]`);
            if (btn) btn.classList.add('active');

            // Toggle tab content visibility
            document.querySelectorAll('.tab-content').forEach(sec => sec.classList.remove('active'));
            const section = document.getElementById(`${tab}-tab`);
            if (section) section.classList.add('active');

            // Lazy-load data when switching
            if (tab === 'collectors') {
                if (!Array.isArray(collectors) || collectors.length === 0) loadCollectors();
            } else if (tab === 'appointments') {
                if (!Array.isArray(appointments) || appointments.length === 0) {
                    if (typeof loadAppointments === 'function') loadAppointments();
                }
            } else if (tab === 'statistics') {
                if (typeof loadStatistics === 'function') loadStatistics();
            }
        }

        // Enhance user ID extraction to support wrapped responses
        function extractUserId(userData) {
            if (!userData) return null;
            // Direct
            if (typeof userData.id !== 'undefined') return userData.id;
            // Standard Laravel resource
            if (userData.data && typeof userData.data.id !== 'undefined') return userData.data.id;
            // Wrapped success: { success, data: { id } }
            if (userData.success && userData.data && typeof userData.data.id !== 'undefined') return userData.data.id;
            // Other wrappers
            if (userData.user && typeof userData.user.id !== 'undefined') return userData.user.id;
            if (userData.data && userData.data.user && typeof userData.data.user.id !== 'undefined') return userData.data.user.id;
            if (userData.data && userData.data.data && typeof userData.data.data.id !== 'undefined') return userData.data.data.id;
            return null;
        }

        async function loadCollectors() {
            try {
                const token = getToken();

                // fetch collectors
                const response = await fetch('/api/collectors', {
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    let msg = 'Failed to load collectors';
                    try { const err = await response.json(); msg = err.message || msg; } catch {}
                    throw new Error(msg);
                }

                const result = await response.json();
                let list = normalizeArrayResponse(result);

                // fetch user
                const userResp = await fetch('/api/user', {
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json'
                    }
                });

                if (userResp.ok) {
                    const userJson = await userResp.json();
                    currentUserId = extractUserId(userJson);
                }

                // filter by current user if we could get an id
                collectors = Array.isArray(list) ? list : [];
                if (currentUserId) {
                    collectors = collectors.filter(c => {
                        const ownerId = c.user_id ?? (c.user && c.user.id);
                        return String(ownerId) === String(currentUserId);
                    });
                }

                displayCollectors(collectors);
            } catch (error) {
                console.error('Error loading collectors:', error);
                displayCollectorsError();
            }
        }

        function displayCollectors(collectorsList) {
            const container = document.getElementById('collectorsContainer');
            if (!Array.isArray(collectorsList) || collectorsList.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-truck"></i>
                        <h3>Belum Ada Usaha</h3>
                        <p>Daftarkan usaha pengepul pertama Anda untuk mulai menerima penjemputan</p>
                        <a href="{{ route('collectors.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Daftarkan Usaha
                        </a>
                    </div>
                `;
                return;
            }

            container.innerHTML = collectorsList.map(collector => `
                <div class="card">
                    <div class="card-header">
                        <div class="card-image">
                            ${collector.foto ? `<img src="/storage/${collector.foto}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 12px;">` : '<i class="fas fa-truck"></i>'}
                        </div>
                        <div class="card-info">
                            <h3>${collector.nama}</h3>
                            <p><i class="fas fa-map-marker-alt"></i> ${(collector.alamat || '').substring(0, 30)}...</p>
                            <span class="status-badge status-${collector.status}">${collector.status}</span>
                        </div>
                    </div>
                    <div class="card-details">
                        <div class="detail-item">
                            <span class="detail-label">Harga/kg</span>
                            <span class="detail-value">Rp ${parseInt(collector.rate_per_kg || collector.rate_harga_per_kg || 0).toLocaleString()}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Kapasitas</span>
                            <span class="detail-value">${collector.kapasitas_maximum || collector.kapasitas_maksimal || 0} kg/hari</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Jam Operasional</span>
                            <span class="detail-value">${collector.jam_operasional || collector.jam_operasional_mulai || 'Tidak tersedia'}${collector.jam_operasional_selesai ? ' - ' + collector.jam_operasional_selesai : ''}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Total Janji</span>
                            <span class="detail-value">${collector.appointments_count || 0} janji</span>
                        </div>
                    </div>
                    <div class="card-actions">
                        <button class="btn btn-primary" onclick="viewCollectorAppointments(${collector.id})">
                            <i class="fas fa-calendar"></i> Lihat Janji
                        </button>
                        <button class="btn btn-warning" onclick="openEditCollectorModal(${collector.id})">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-danger" onclick="deleteCollector(${collector.id})">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>
            `).join('');
        }

        function displayCollectorsError() {
            document.getElementById('collectorsContainer').innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h3>Gagal memuat data</h3>
                    <p>Silakan muat ulang halaman atau coba lagi nanti</p>
                </div>
            `;
        }

        async function loadAppointments() {
            try {
                const token = getToken();
                if (!token) {
                    alert('Anda harus login terlebih dahulu');
                    window.location.href = '/login';
                    return;
                }

                // First get current collector info
                const collectorResponse = await fetch('/api/collectors?user_only=true', {
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json'
                    }
                });

                if (!collectorResponse.ok) {
                    throw new Error('Failed to get collector info');
                }

                const collectorResult = await safeParseJSON(collectorResponse);
                const currentCollector = collectorResult.data?.data?.[0]; // Get first collector from paginated result

                if (!currentCollector) {
                    throw new Error('No collector found for current user');
                }

                // Now get pending appointments for this collector
                const response = await fetch(`/api/collectors/${currentCollector.id}/pending-appointments`, {
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    const result = await safeParseJSON(response);
                    appointments = result.data || [];
                    displayAppointments(appointments);
                } else {
                    // Try to get error message safely
                    let errorMessage = 'Failed to load appointments';
                    try {
                        const errorData = await safeParseJSON(response);
                        errorMessage = errorData.message || errorMessage;
                    } catch (e) {
                        console.warn('Could not parse error response:', e);
                    }
                    throw new Error(errorMessage);
                }
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
                        <h3>Belum Ada Janji</h3>
                        <p>Belum ada permintaan penjemputan masuk</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = appointmentsList.map(appointment => `
                <div class="card">
                    <div class="card-header">
                        <div class="card-image" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="card-info">
                            <h3>${appointment.fish_farm?.nama || 'Tambak'}</h3>
                            <p><i class="fas fa-user"></i> ${appointment.fish_farm?.user?.name || 'Petani'}</p>
                            <span class="status-badge status-${appointment.status}">${getStatusText(appointment.status)}</span>
                        </div>
                    </div>
                    
                    <div class="card-details">
                        <div class="detail-item">
                            <span class="detail-label">Tanggal</span>
                            <span class="detail-value">${formatDate(appointment.tanggal)}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Estimasi Berat</span>
                            <span class="detail-value">${appointment.perkiraan_berat || '-'} kg</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Harga per Kg</span>
                            <span class="detail-value">Rp ${parseInt(appointment.harga_per_kg || 0).toLocaleString()}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Total Estimasi</span>
                            <span class="detail-value">Rp ${parseInt(appointment.total_estimasi || 0).toLocaleString()}</span>
                        </div>
                    </div>

                    ${appointment.status === 'pending' ? `
                        <div class="card-actions">
                            <button class="btn btn-success" onclick="acceptAppointment(${appointment.id})">
                                <i class="fas fa-check"></i> Terima
                            </button>
                            <button class="btn btn-danger" onclick="rejectAppointment(${appointment.id})">
                                <i class="fas fa-times"></i> Tolak
                            </button>
                        </div>
                    ` : appointment.status === 'diterima' ? `
                        <div class="card-actions">
                            <button class="btn btn-success" onclick="completeAppointment(${appointment.id})">
                                <i class="fas fa-check-circle"></i> Selesai
                            </button>
                        </div>
                    ` : appointment.status === 'selesai' ? `
                        <div class="card-actions">
                            <button class="btn btn-success" onclick="sendWhatsAppSummary(${appointment.id})" style="background-color: #25D366;">
                                <i class="fab fa-whatsapp"></i> Kirim Summary WA
                            </button>
                            <span class="status-text">Status: ${getStatusText(appointment.status)}</span>
                        </div>
                    ` : `
                        <div class="card-actions">
                            <span class="status-text">Status: ${getStatusText(appointment.status)}</span>
                        </div>
                    `}

                    ${appointment.whatsapp_summary ? `
                        <div class="whatsapp-summary">
                            <h4><i class="fab fa-whatsapp"></i> Summary WhatsApp</h4>
                            <p><strong>Tanggal:</strong> ${formatDate(appointment.whatsapp_summary.tanggal)}</p>
                            <p><strong>Berat Aktual:</strong> ${appointment.whatsapp_summary.berat_aktual} kg</p>
                            <p><strong>Total Harga:</strong> Rp ${parseInt(appointment.whatsapp_summary.total_harga).toLocaleString()}</p>
                            <p><strong>Status:</strong> ${appointment.whatsapp_summary.status}</p>
                        </div>
                    ` : ''}
                </div>
            `).join('');
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

        async function loadStatistics() {
            try {
                const response = await fetch('/api/collectors/statistics', {
                    headers: {
                        'Authorization': 'Bearer ' + getToken(),
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    const stats = await safeParseJSON(response);
                    displayStatistics(stats.data);
                } else {
                    let errorMessage = 'Failed to load statistics';
                    try {
                        const errorData = await safeParseJSON(response);
                        errorMessage = errorData.message || errorMessage;
                    } catch (e) {
                        console.warn('Could not parse error response:', e);
                    }
                    throw new Error(errorMessage);
                }
            } catch (error) {
                console.error('Error loading statistics:', error);
                // Show default stats
                displayStatistics({
                    accepted: 0,
                    completed: 0,
                    total_kg: 0,
                    revenue: 0,
                    fish_types: []
                });
            }
        }

        function displayStatistics(stats) {
            document.getElementById('stat-accepted').textContent = stats.accepted || 0;
            document.getElementById('stat-completed').textContent = stats.completed || 0;
            document.getElementById('stat-total-kg').textContent = `${stats.total_kg || 0} kg`;
            document.getElementById('stat-revenue').textContent = `Rp ${parseInt(stats.revenue || 0).toLocaleString()}`;

            const fishTypesContainer = document.getElementById('fishTypesStats');
            if (stats.fish_types && stats.fish_types.length > 0) {
                fishTypesContainer.innerHTML = stats.fish_types.map(fish => `
                    <div class="detail-item">
                        <span class="detail-label">${fish.type}</span>
                        <span class="detail-value">${fish.count} penjemputan</span>
                    </div>
                `).join('');
            } else {
                fishTypesContainer.innerHTML = '<p>Belum ada data penjemputan</p>';
            }
        }

        async function acceptAppointment(appointmentId) {
            const hargaFinal = prompt('Masukkan harga final per kg (Rp):');
            if (!hargaFinal || isNaN(hargaFinal)) {
                alert('Masukkan harga yang valid');
                return;
            }

            const catatan = prompt('Catatan tambahan (opsional):');

            try {
                // Get current collector info first
                const collectorResponse = await fetch('/api/collectors?user_only=true', {
                    headers: {
                        'Authorization': 'Bearer ' + getToken(),
                        'Accept': 'application/json'
                    }
                });

                if (!collectorResponse.ok) {
                    throw new Error('Failed to get collector info');
                }

                const collectorResult = await safeParseJSON(collectorResponse);
                const currentCollector = collectorResult.data?.data?.[0];

                if (!currentCollector) {
                    throw new Error('No collector found');
                }

                const response = await fetch(`/api/collectors/${currentCollector.id}/appointments/${appointmentId}`, {
                    method: 'PUT',
                    headers: {
                        'Authorization': 'Bearer ' + getToken(),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ 
                        action: 'accept',
                        harga_final: parseFloat(hargaFinal),
                        catatan_collector: catatan
                    })
                });

                if (response.ok) {
                    alert('Janji penjemputan berhasil diterima');
                    await loadAppointments(); // Reload appointments
                } else {
                    // Try to get error message safely
                    let errorMessage = 'Gagal menerima janji penjemputan';
                    try {
                        const errorData = await safeParseJSON(response);
                        errorMessage = errorData.message || errorMessage;
                    } catch (e) {
                        console.warn('Could not parse error response:', e);
                        errorMessage = `HTTP ${response.status}: ${response.statusText}`;
                    }
                    throw new Error(errorMessage);
                }
            } catch (error) {
                console.error('Error accepting appointment:', error);
                alert('Terjadi kesalahan saat menerima janji penjemputan: ' + error.message);
            }
        }

        async function rejectAppointment(appointmentId) {
            const reason = prompt('Masukkan alasan penolakan (opsional):');
            if (reason === null) return; // User cancelled

            try {
                // Get current collector info first
                const collectorResponse = await fetch('/api/collectors?user_only=true', {
                    headers: {
                        'Authorization': 'Bearer ' + getToken(),
                        'Accept': 'application/json'
                    }
                });

                if (!collectorResponse.ok) {
                    throw new Error('Failed to get collector info');
                }

                const collectorResult = await safeParseJSON(collectorResponse);
                const currentCollector = collectorResult.data?.data?.[0];

                if (!currentCollector) {
                    throw new Error('No collector found');
                }

                const response = await fetch(`/api/collectors/${currentCollector.id}/appointments/${appointmentId}`, {
                    method: 'PUT',
                    headers: {
                        'Authorization': 'Bearer ' + getToken(),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ 
                        action: 'reject',
                        catatan_collector: reason
                    })
                });

                if (response.ok) {
                    alert('Janji penjemputan berhasil ditolak');
                    await loadAppointments(); // Reload appointments
                } else {
                    // Try to get error message safely
                    let errorMessage = 'Gagal menolak janji penjemputan';
                    try {
                        const errorData = await safeParseJSON(response);
                        errorMessage = errorData.message || errorMessage;
                    } catch (e) {
                        console.warn('Could not parse error response:', e);
                        errorMessage = `HTTP ${response.status}: ${response.statusText}`;
                    }
                    throw new Error(errorMessage);
                }
            } catch (error) {
                console.error('Error rejecting appointment:', error);
                alert('Terjadi kesalahan saat menolak janji penjemputan: ' + error.message);
            }
        }

        async function completeAppointment(appointmentId) {
            const actualWeight = prompt('Masukkan berat aktual ikan (kg):');
            if (!actualWeight || isNaN(actualWeight)) {
                alert('Masukkan berat yang valid');
                return;
            }

            const kualitas = prompt('Masukkan kualitas ikan (contoh: Premium, Baik, Standar):');
            if (!kualitas) {
                alert('Masukkan kualitas ikan');
                return;
            }

            const catatan = prompt('Catatan penyelesaian (opsional):');

            try {
                // Get current collector info first
                const collectorResponse = await fetch('/api/collectors?user_only=true', {
                    headers: {
                        'Authorization': 'Bearer ' + getToken(),
                        'Accept': 'application/json'
                    }
                });

                if (!collectorResponse.ok) {
                    throw new Error('Failed to get collector info');
                }

                const collectorResult = await safeParseJSON(collectorResponse);
                const currentCollector = collectorResult.data?.data?.[0];

                if (!currentCollector) {
                    throw new Error('No collector found');
                }

                const response = await fetch(`/api/collectors/${currentCollector.id}/appointments/${appointmentId}/complete`, {
                    method: 'PUT',
                    headers: {
                        'Authorization': 'Bearer ' + getToken(),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ 
                        berat_aktual: parseFloat(actualWeight),
                        kualitas_ikan: kualitas,
                        catatan_completion: catatan
                    })
                });

                if (response.ok) {
                    alert('Penjemputan berhasil diselesaikan');
                    await loadAppointments(); // Reload appointments
                } else {
                    // Try to get error message safely
                    let errorMessage = 'Gagal menyelesaikan penjemputan';
                    try {
                        const errorData = await safeParseJSON(response);
                        errorMessage = errorData.message || errorMessage;
                    } catch (e) {
                        console.warn('Could not parse error response:', e);
                        errorMessage = `HTTP ${response.status}: ${response.statusText}`;
                    }
                    throw new Error(errorMessage);
                }
            } catch (error) {
                console.error('Error completing appointment:', error);
                alert('Terjadi kesalahan saat menyelesaikan penjemputan: ' + error.message);
            }
        }

        async function sendWhatsAppSummary(appointmentId) {
            try {
                const response = await fetch(`/api/appointments/${appointmentId}/whatsapp-summary`, {
                    method: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + getToken(),
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    const result = await safeParseJSON(response);
                    
                    // Show success message
                    alert('Summary WhatsApp berhasil digenerate!');
                    
                    // Open WhatsApp with the message if URL available
                    if (result.data?.whatsapp_url) {
                        const openWA = confirm('Buka WhatsApp untuk mengirim summary?');
                        if (openWA) {
                            window.open(result.data.whatsapp_url, '_blank');
                        }
                    } else if (result.data?.message) {
                        // Show message in alert if no URL
                        alert('Summary Message:\n\n' + result.data.message);
                    }
                } else {
                    let errorMessage = 'Gagal mengirim summary WhatsApp';
                    try {
                        const errorData = await safeParseJSON(response);
                        errorMessage = errorData.message || errorMessage;
                    } catch (e) {
                        console.warn('Could not parse error response:', e);
                    }
                    alert(errorMessage);
                }
            } catch (error) {
                console.error('Error sending WhatsApp summary:', error);
                alert('Terjadi kesalahan saat mengirim summary WhatsApp: ' + error.message);
            }
        }

        function viewCollectorAppointments(collectorId) {
            switchTab('appointments');
            // Filter appointments for this collector
            loadAppointments();
        }

        function openEditCollectorModal(id) {
            const c = collectors.find(x => String(x.id) === String(id));
            if (!c) return;
            document.getElementById('edit_collector_id').value = c.id;
            document.getElementById('edit_nama').value = c.nama || '';
            document.getElementById('edit_alamat').value = c.alamat || '';
            document.getElementById('edit_rate_per_kg').value = (c.rate_per_kg || c.rate_harga_per_kg || '').toString();
            document.getElementById('edit_kapasitas_maksimal').value = (c.kapasitas_maximum || c.kapasitas_maksimal || '').toString();
            document.getElementById('edit_jam_mulai').value = c.jam_operasional_mulai || c.jam_operasional || '';
            document.getElementById('edit_jam_selesai').value = c.jam_operasional_selesai || '';
            document.getElementById('edit_status').value = c.status || 'aktif';
            document.getElementById('edit_deskripsi').value = c.deskripsi || '';
            document.getElementById('editCollectorModal').style.display = 'block';
        }

        function closeEditCollectorModal() {
            document.getElementById('editCollectorModal').style.display = 'none';
        }

        document.getElementById('editCollectorForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const id = document.getElementById('edit_collector_id').value;
            const payload = {
                nama: document.getElementById('edit_nama').value,
                alamat: document.getElementById('edit_alamat').value,
                rate_harga_per_kg: document.getElementById('edit_rate_per_kg').value,
                kapasitas_maksimal: document.getElementById('edit_kapasitas_maksimal').value,
                jam_operasional_mulai: document.getElementById('edit_jam_mulai').value,
                jam_operasional_selesai: document.getElementById('edit_jam_selesai').value,
                status: document.getElementById('edit_status').value,
                deskripsi: document.getElementById('edit_deskripsi').value
            };

            try {
                const resp = await fetch(`/api/collectors/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Authorization': 'Bearer ' + getToken(),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                if (!resp.ok) {
                    let msg = 'Gagal memperbarui data';
                    try { const j = await resp.json(); msg = j.message || msg; } catch {}
                    alert(msg);
                    return;
                }

                // refresh list
                closeEditCollectorModal();
                await loadCollectors();
            } catch (err) {
                console.error('Update error:', err);
                alert('Terjadi kesalahan saat memperbarui data');
            }
        });

        // close modal on ESC and backdrop click
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeEditCollectorModal();
        });
        document.getElementById('editCollectorModal').addEventListener('click', (e) => {
            if (e.target.id === 'editCollectorModal') closeEditCollectorModal();
        });
    </script>
</body>
</html>
