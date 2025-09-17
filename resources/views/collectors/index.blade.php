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
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
            color: #f5576c;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .back-button:hover {
            transform: scale(1.1);
            color: #f5576c;
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

    <script src="/js/auth.js"></script>
    <script>
        let currentTab = 'collectors';
        let collectors = [];
        let appointments = [];
        let currentUserId = null;

        document.addEventListener('DOMContentLoaded', function() {
            // Check if user is logged in
            if (!getToken()) {
                alert('Anda harus login terlebih dahulu untuk mengakses halaman ini');
                window.location.href = '/login';
                return;
            }
            
            loadCollectors();
        });

        function switchTab(tab) {
            // Update tab buttons
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            document.querySelector(`[onclick="switchTab('${tab}')"]`).classList.add('active');

            // Update tab content
            document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
            document.getElementById(`${tab}-tab`).classList.add('active');

            currentTab = tab;

            // Load data if not already loaded
            if (tab === 'collectors' && collectors.length === 0) {
                loadCollectors();
            } else if (tab === 'appointments' && appointments.length === 0) {
                loadAppointments();
            } else if (tab === 'statistics') {
                loadStatistics();
            }
        }

        async function loadCollectors() {
            try {
                const token = getToken();
                if (!token) {
                    alert('Anda harus login terlebih dahulu');
                    window.location.href = '/login';
                    return;
                }

                // Load all collectors (not just user's own)
                const response = await fetch('/api/collectors', {
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    const result = await safeParseJSON(response);
                    collectors = result.data.data || [];
                    
                    // Get current user info to determine ownership
                    const userResponse = await fetch('/api/user', {
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Accept': 'application/json'
                        }
                    });
                    
                    if (userResponse.ok) {
                        const userData = await safeParseJSON(userResponse);
                        currentUserId = userData.data.id;
                    }
                    
                    displayCollectors(collectors);
                } else {
                    // Try to get error message safely
                    let errorMessage = 'Failed to load collectors';
                    try {
                        const errorData = await safeParseJSON(response);
                        errorMessage = errorData.message || errorMessage;
                    } catch (e) {
                        console.warn('Could not parse error response:', e);
                    }
                    throw new Error(errorMessage);
                }
            } catch (error) {
                console.error('Error loading collectors:', error);
                displayCollectorsError();
            }
        }

        function displayCollectors(collectorsList) {
            const container = document.getElementById('collectorsContainer');
            
            if (collectorsList.length === 0) {
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
                            <p><i class="fas fa-map-marker-alt"></i> ${collector.alamat.substring(0, 30)}...</p>
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
                            <span class="detail-value">${collector.jam_operasional || collector.jam_operasional_mulai || 'Tidak tersedia'}</span>
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
                        ${collector.user_id === currentUserId ? `
                            <button class="btn btn-warning" onclick="editCollector(${collector.id})">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-danger" onclick="deleteCollector(${collector.id})">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        ` : `
                            <button class="btn btn-info" onclick="viewCollector(${collector.id})">
                                <i class="fas fa-eye"></i> Lihat Detail
                            </button>
                            <small class="text-muted">Milik: ${collector.user?.name || 'Pemilik lain'}</small>
                        `}
                    </div>
                </div>
            `).join('');
        }

        function displayCollectorsError() {
            document.getElementById('collectorsContainer').innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h3>Gagal Memuat Data</h3>
                    <p>Terjadi kesalahan saat memuat data usaha</p>
                    <button class="btn btn-primary" onclick="loadCollectors()">
                        <i class="fas fa-refresh"></i> Coba Lagi
                    </button>
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
                const collectorResponse = await fetch('/api/collectors', {
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json'
                    }
                });

                if (!collectorResponse.ok) {
                    throw new Error('Failed to get collector info');
                }

                const collectorResult = await safeParseJSON(collectorResponse);
                const currentCollector = collectorResult.data?.[0]; // Assuming first collector is current user's

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
                const collectorResponse = await fetch('/api/collectors', {
                    headers: {
                        'Authorization': 'Bearer ' + getToken(),
                        'Accept': 'application/json'
                    }
                });

                if (!collectorResponse.ok) {
                    throw new Error('Failed to get collector info');
                }

                const collectorResult = await safeParseJSON(collectorResponse);
                const currentCollector = collectorResult.data?.[0];

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
                const collectorResponse = await fetch('/api/collectors', {
                    headers: {
                        'Authorization': 'Bearer ' + getToken(),
                        'Accept': 'application/json'
                    }
                });

                if (!collectorResponse.ok) {
                    throw new Error('Failed to get collector info');
                }

                const collectorResult = await safeParseJSON(collectorResponse);
                const currentCollector = collectorResult.data?.[0];

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

            } catch (error) {
                console.error('Error rejecting appointment:', error);
                alert('Terjadi kesalahan saat menolak janji penjemputan: ' + error.message);
            }
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
                const collectorResponse = await fetch('/api/collectors', {
                    headers: {
                        'Authorization': 'Bearer ' + getToken(),
                        'Accept': 'application/json'
                    }
                });

                if (!collectorResponse.ok) {
                    throw new Error('Failed to get collector info');
                }

                const collectorResult = await safeParseJSON(collectorResponse);
                const currentCollector = collectorResult.data?.[0];

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
                    alert('Summary berhasil dikirim via WhatsApp');
                    // Optionally open WhatsApp with the message
                    if (result.whatsapp_url) {
                        window.open(result.whatsapp_url, '_blank');
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
                alert('Terjadi kesalahan saat mengirim summary WhatsApp');
            }
        }

        function viewCollectorAppointments(collectorId) {
            switchTab('appointments');
            // Filter appointments for this collector
            loadAppointments();
        }

        function editCollector(collectorId) {
            window.location.href = `/collectors/${collectorId}/edit`;
        }

        function viewCollector(collectorId) {
            const collector = collectors.find(c => c.id === collectorId);
            if (collector) {
                alert(`Pengepul: ${collector.nama}\nPemilik: ${collector.user?.name || 'Tidak diketahui'}\nAlamat: ${collector.alamat}\nHarga: Rp ${parseInt(collector.rate_per_kg || collector.rate_harga_per_kg || 0).toLocaleString()}/kg`);
            }
        }

        async function deleteCollector(collectorId) {
            if (!confirm('Apakah Anda yakin ingin menghapus usaha ini?')) {
                return;
            }

            try {
                const response = await fetch(`/api/collectors/${collectorId}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': 'Bearer ' + getToken(),
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    alert('Usaha berhasil dihapus');
                    loadCollectors();
                } else {
                    alert('Gagal menghapus usaha');
                }
            } catch (error) {
                console.error('Error deleting collector:', error);
                alert('Terjadi kesalahan saat menghapus usaha');
            }
        }

        function viewAppointmentDetails(appointmentId) {
            window.location.href = `/appointments/${appointmentId}`;
        }

        function editAppointment(appointmentId) {
            window.location.href = `/appointments/${appointmentId}/edit`;
        }

        function searchCollectors() {
            const query = document.getElementById('collectorSearch').value.toLowerCase();
            const filtered = collectors.filter(collector => 
                collector.nama.toLowerCase().includes(query) ||
                collector.alamat.toLowerCase().includes(query)
            );
            displayCollectors(filtered);
        }

        function filterAppointments() {
            const status = document.getElementById('statusFilter').value;
            const date = document.getElementById('dateFilter').value;
            
            let filtered = appointments;

            if (status) {
                filtered = filtered.filter(apt => apt.status === status);
            }

            if (date) {
                filtered = filtered.filter(apt => {
                    const aptDate = new Date(apt.tanggal_janji || apt.tanggal);
                    return aptDate.toISOString().split('T')[0] === date;
                });
            }

            displayAppointments(filtered);
        }

        function getStatusText(status) {
            const statusMap = {
                'pending': 'Menunggu',
                'diterima': 'Diterima',
                'ditolak': 'Ditolak',
                'selesai': 'Selesai',
                'dibatalkan': 'Dibatalkan'
            };
            return statusMap[status] || status;
        }

        function formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
        }
    </script>
</body>
</html>
