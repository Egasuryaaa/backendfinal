<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>IwakMart - Manajemen Tambak Ikan</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            color: #667eea;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .back-button:hover {
            transform: scale(1.1);
            color: #667eea;
        }

        .loading {
            text-align: center;
            padding: 2rem;
            color: #666;
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

        .location-info {
            margin-bottom: 2rem;
        }

        .info-card {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .info-card h4 {
            margin-bottom: 0.5rem;
            font-size: 1.2rem;
        }

        .info-card p {
            margin-bottom: 0.25rem;
        }

        .distance-badge {
            background: rgba(0, 255, 0, 0.2);
            color: #00cc00;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
            display: inline-block;
            margin-top: 0.5rem;
        }

        .collector-card .distance-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.5rem;
            border-radius: 8px;
            margin-top: 1rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <a href="{{ route('fishmarket') }}" class="back-button" title="Kembali ke Beranda">
        <i class="fas fa-arrow-left"></i>
    </a>

    <div class="header">
        <h1><i class="fas fa-fish"></i> Manajemen Tambak Ikan</h1>
        <p>Kelola tambak dan temukan pengepul terbaik</p>
    </div>

    <div class="container">
        <!-- Tabs -->
        <div class="tabs">
            <button class="tab active" onclick="switchTab('fishfarms')">
                <i class="fas fa-swimmer"></i> Tambak Saya
            </button>
            <button class="tab" onclick="switchTab('collectors')">
                <i class="fas fa-truck"></i> Cari Pengepul
            </button>
            <button class="tab" onclick="switchTab('nearest-collectors')">
                <i class="fas fa-map-marker-alt"></i> Pengepul Terdekat
            </button>
        </div>

        <!-- Fish Farms Tab -->
        <div id="fishfarms-tab" class="tab-content active">
            <div class="action-bar">
                <div class="search-box">
                    <input type="text" class="search-input" placeholder="Cari tambak..." id="fishFarmSearch">
                    <button class="btn btn-secondary" onclick="searchFishFarms()">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
                <a href="{{ route('fish-farms.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Tambak
                </a>
            </div>

            <div id="fishFarmsContainer" class="grid">
                <div class="loading">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                    <p>Memuat data tambak...</p>
                </div>
            </div>
        </div>

        <!-- Collectors Tab -->
        <div id="collectors-tab" class="tab-content">
            <div class="action-bar">
                <div class="search-box">
                    <input type="text" class="search-input" placeholder="Cari pengepul..." id="collectorSearch">
                    <select class="search-input" id="fishTypeFilter">
                        <option value="">Semua Jenis Ikan</option>
                        <option value="Lele">Lele</option>
                        <option value="Nila">Nila</option>
                        <option value="Mujair">Mujair</option>
                        <option value="Gurame">Gurame</option>
                        <option value="Patin">Patin</option>
                        <option value="Bandeng">Bandeng</option>
                        <option value="Mas">Mas</option>
                        <option value="Bawal">Bawal</option>
                    </select>
                    <button class="btn btn-secondary" onclick="searchCollectors()">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
                <a href="{{ route('collectors.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Daftar Usaha
                </a>
            </div>

            <div id="collectorsContainer" class="grid">
                <div class="loading">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                    <p>Memuat data pengepul...</p>
                </div>
            </div>
        </div>

        <!-- Nearest Collectors Tab -->
        <div id="nearest-collectors-tab" class="tab-content">
            <div class="action-bar">
                <div class="search-box">
                    <input type="number" class="search-input" placeholder="Jarak maksimal (km)" id="maxDistanceFilter" value="50" min="1" max="500">
                    <select class="search-input" id="nearestFishTypeFilter">
                        <option value="">Semua Jenis Ikan</option>
                        <option value="Lele">Lele</option>
                        <option value="Nila">Nila</option>
                        <option value="Mujair">Mujair</option>
                        <option value="Gurame">Gurame</option>
                        <option value="Patin">Patin</option>
                        <option value="Bandeng">Bandeng</option>
                        <option value="Mas">Mas</option>
                        <option value="Bawal">Bawal</option>
                    </select>
                    <input type="number" class="search-input" placeholder="Rate min (Rp/kg)" id="minRateFilter" min="0">
                    <input type="number" class="search-input" placeholder="Rate max (Rp/kg)" id="maxRateFilter" min="0">
                    <button class="btn btn-secondary" onclick="loadNearestCollectors()">
                        <i class="fas fa-search"></i> Cari Terdekat
                    </button>
                </div>
                <button class="btn btn-info" onclick="getCurrentLocation()">
                    <i class="fas fa-location-arrow"></i> Update Lokasi Saya
                </button>
            </div>

            <div id="locationInfo" class="location-info" style="display: none;">
                <div class="info-card">
                    <h4><i class="fas fa-map-marker-alt"></i> Lokasi Anda</h4>
                    <p id="userLocationText">Memuat lokasi...</p>
                    <p><small>Pencarian berdasarkan lokasi Anda saat ini</small></p>
                </div>
            </div>

            <div id="nearestCollectorsContainer" class="grid">
                <div class="loading">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                    <p>Memuat pengepul terdekat...</p>
                </div>
            </div>
        </div>
    </div>

    <script src="/js/auth.js"></script>
    <script>
        let currentTab = 'fishfarms';
        let fishFarms = [];
        let collectors = [];
        let nearestCollectors = [];
        let currentUserId = null;
        let userLocation = null;

        document.addEventListener('DOMContentLoaded', function() {
            // Check if user is logged in
            if (!getToken()) {
                alert('Anda harus login terlebih dahulu untuk mengakses halaman ini');
                window.location.href = '/login';
                return;
            }
            
            loadFishFarms();
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
            if (tab === 'fishfarms' && fishFarms.length === 0) {
                loadFishFarms();
            } else if (tab === 'collectors' && collectors.length === 0) {
                loadCollectors();
            } else if (tab === 'nearest-collectors') {
                loadNearestCollectors();
            }
        }

        async function loadFishFarms() {
            try {
                const token = getToken();
                if (!token) {
                    alert('Anda harus login terlebih dahulu');
                    window.location.href = '/login';
                    return;
                }

                // Load all fish farms (not just user's own)
                const response = await fetch('/api/fish-farms', {
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    const result = await safeParseJSON(response);
                    fishFarms = result.data.data || [];
                    
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
                    
                    displayFishFarms(fishFarms);
                } else {
                    // Try to get error message safely
                    let errorMessage = 'Failed to load fish farms';
                    try {
                        const errorData = await safeParseJSON(response);
                        errorMessage = errorData.message || errorMessage;
                    } catch (e) {
                        console.warn('Could not parse error response:', e);
                    }
                    throw new Error(errorMessage);
                }
            } catch (error) {
                console.error('Error loading fish farms:', error);
                displayFishFarmsError();
            }
        }

        function displayFishFarms(farms) {
            const container = document.getElementById('fishFarmsContainer');
            
            if (farms.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-fish"></i>
                        <h3>Belum Ada Tambak</h3>
                        <p>Daftarkan tambak pertama Anda untuk mulai menerima penjemputan</p>
                        <a href="{{ route('fish-farms.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Daftarkan Tambak
                        </a>
                    </div>
                `;
                return;
            }

            container.innerHTML = farms.map(farm => `
                <div class="card">
                    <div class="card-header">
                        <div class="card-image">
                            ${farm.foto ? `<img src="/storage/${farm.foto}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 12px;">` : '<i class="fas fa-fish"></i>'}
                        </div>
                        <div class="card-info">
                            <h3>${farm.nama}</h3>
                            <p><i class="fas fa-fish"></i> ${farm.jenis_ikan}</p>
                            <span class="status-badge status-${farm.status}">${farm.status}</span>
                        </div>
                    </div>
                    
                    <div class="card-details">
                        <div class="detail-item">
                            <span class="detail-label">Bibit</span>
                            <span class="detail-value">${farm.banyak_bibit.toLocaleString()} ekor</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Luas</span>
                            <span class="detail-value">${farm.luas_tambak} m²</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Estimasi Produksi</span>
                            <span class="detail-value">${(farm.banyak_bibit * 0.8 * 0.5).toFixed(0)} kg</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Alamat</span>
                            <span class="detail-value">${farm.alamat.substring(0, 50)}...</span>
                        </div>
                    </div>
                    
                    <div class="card-actions">
                        <button class="btn btn-success" onclick="findCollectors(${farm.id})">
                            <i class="fas fa-search"></i> Cari Pengepul
                        </button>
                        ${farm.user_id === currentUserId ? `
                            <button class="btn btn-warning" onclick="editFishFarm(${farm.id})">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-danger" onclick="deleteFishFarm(${farm.id})">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        ` : `
                            <button class="btn btn-info" onclick="viewFishFarm(${farm.id})">
                                <i class="fas fa-eye"></i> Lihat Detail
                            </button>
                            <small class="text-muted">Milik: ${farm.user?.name || 'Pemilik lain'}</small>
                        `}
                    </div>
                </div>
            `).join('');
        }

        function displayFishFarmsError() {
            document.getElementById('fishFarmsContainer').innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h3>Gagal Memuat Data</h3>
                    <p>Terjadi kesalahan saat memuat data tambak</p>
                    <button class="btn btn-primary" onclick="loadFishFarms()">
                        <i class="fas fa-refresh"></i> Coba Lagi
                    </button>
                </div>
            `;
        }

        async function loadCollectors() {
            try {
                const token = getToken();
                if (!token) {
                    alert('Anda harus login terlebih dahulu');
                    window.location.href = '/login';
                    return;
                }

                const response = await fetch('/api/collectors?status=aktif', {
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    const result = await safeParseJSON(response);
                    collectors = result.data.data || [];
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
                        <h3>Belum Ada Pengepul</h3>
                        <p>Belum ada pengepul yang terdaftar di area ini</p>
                        <a href="{{ route('collectors.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Daftarkan Usaha Pengepul
                        </a>
                    </div>
                `;
                return;
            }

            container.innerHTML = collectorsList.map(collector => `
                <div class="card">
                    <div class="card-header">
                        <div class="card-image" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                            ${collector.foto ? `<img src="/storage/${collector.foto}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 12px;">` : '<i class="fas fa-truck"></i>'}
                        </div>
                        <div class="card-info">
                            <h3>${collector.nama}</h3>
                            <p><i class="fas fa-user"></i> ${collector.user?.name || 'Pengepul'}</p>
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
                            <span class="detail-value">${collector.jam_operasional_mulai || collector.jam_operasional || 'Tidak tersedia'}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Jenis Ikan</span>
                            <span class="detail-value">${Array.isArray(collector.jenis_ikan_diterima) ? collector.jenis_ikan_diterima.join(', ') : (collector.jenis_ikan_diterima || 'Semua jenis')}</span>
                        </div>
                    </div>
                    
                    <div class="card-actions">
                        <button class="btn btn-primary" onclick="createAppointment(${collector.id})">
                            <i class="fas fa-calendar-plus"></i> Buat Janji
                        </button>
                        <button class="btn btn-secondary" onclick="viewCollector(${collector.id})">
                            <i class="fas fa-eye"></i> Detail
                        </button>
                        <a href="https://wa.me/${collector.no_telepon?.replace(/[^0-9]/g, '')}" target="_blank" class="btn btn-success">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                        </a>
                    </div>
                </div>
            `).join('');
        }

        function displayCollectorsError() {
            document.getElementById('collectorsContainer').innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h3>Gagal Memuat Data</h3>
                    <p>Terjadi kesalahan saat memuat data pengepul</p>
                    <button class="btn btn-primary" onclick="loadCollectors()">
                        <i class="fas fa-refresh"></i> Coba Lagi
                    </button>
                </div>
            `;
        }

        async function findCollectors(fishFarmId) {
            try {
                const response = await fetch(`/api/fish-farms/${fishFarmId}/available-collectors`, {
                    headers: {
                        'Authorization': 'Bearer ' + getToken(),
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    const result = await safeParseJSON(response);
                    collectors = result.data || [];
                    switchTab('collectors');
                    displayCollectors(collectors);
                } else {
                    let errorMessage = 'Gagal mencari pengepul';
                    try {
                        const errorData = await safeParseJSON(response);
                        errorMessage = errorData.message || errorMessage;
                    } catch (e) {
                        console.warn('Could not parse error response:', e);
                    }
                    alert(errorMessage);
                }
            } catch (error) {
                console.error('Error finding collectors:', error);
                alert('Terjadi kesalahan saat mencari pengepul');
            }
        }

        function createAppointment(collectorId) {
            const collector = collectors.find(c => c.id === collectorId);
            if (!collector) {
                alert('Pengepul tidak ditemukan');
                return;
            }
            
            showAppointmentModal(collector);
        }

        function editFishFarm(farmId) {
            window.location.href = `/fish-farms/${farmId}/edit`;
        }

        function viewFishFarm(farmId) {
            const farm = fishFarms.find(f => f.id === farmId);
            if (farm) {
                alert(`Tambak: ${farm.nama}\nPemilik: ${farm.user?.name || 'Tidak diketahui'}\nJenis Ikan: ${farm.jenis_ikan}\nAlamat: ${farm.alamat}`);
            }
        }

        async function deleteFishFarm(farmId) {
            if (!confirm('Apakah Anda yakin ingin menghapus tambak ini?')) {
                return;
            }

            try {
                const response = await fetch(`/api/fish-farms/${farmId}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': 'Bearer ' + getToken(),
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    alert('Tambak berhasil dihapus');
                    loadFishFarms();
                } else {
                    alert('Gagal menghapus tambak');
                }
            } catch (error) {
                console.error('Error deleting fish farm:', error);
                alert('Terjadi kesalahan saat menghapus tambak');
            }
        }

        function viewCollector(collectorId) {
            window.location.href = `/collectors/${collectorId}`;
        }

        function searchFishFarms() {
            const query = document.getElementById('fishFarmSearch').value.toLowerCase();
            const filtered = fishFarms.filter(farm => 
                farm.nama.toLowerCase().includes(query) ||
                farm.jenis_ikan.toLowerCase().includes(query) ||
                farm.alamat.toLowerCase().includes(query)
            );
            displayFishFarms(filtered);
        }

        function searchCollectors() {
            const query = document.getElementById('collectorSearch').value.toLowerCase();
            const fishType = document.getElementById('fishTypeFilter').value;
            
            let filtered = collectors.filter(collector => 
                collector.nama.toLowerCase().includes(query) ||
                collector.alamat.toLowerCase().includes(query)
            );

            if (fishType) {
                filtered = filtered.filter(collector => 
                    collector.jenis_ikan_diterima && 
                    Array.isArray(collector.jenis_ikan_diterima) &&
                    collector.jenis_ikan_diterima.includes(fishType)
                );
            }

            displayCollectors(filtered);
        }

        // Appointment Modal Functions
        function showAppointmentModal(collector) {
            const modal = document.getElementById('appointmentModal');
            document.getElementById('modalCollectorName').textContent = collector.nama;
            document.getElementById('modalCollectorRate').textContent = `Rp ${parseInt(collector.rate_per_kg || collector.rate_harga_per_kg || 0).toLocaleString()}/kg`;
            document.getElementById('collectorId').value = collector.id;
            
            // Set minimum date to tomorrow
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            document.getElementById('tanggalPenjemputan').min = tomorrow.toISOString().split('T')[0];
            
            modal.style.display = 'block';
        }

        function closeAppointmentModal() {
            document.getElementById('appointmentModal').style.display = 'none';
            document.getElementById('appointmentForm').reset();
        }

        async function submitAppointment() {
            const form = document.getElementById('appointmentForm');
            const formData = new FormData(form);
            
            const data = {
                collector_id: formData.get('collector_id'),
                tanggal_penjemputan: formData.get('tanggal_penjemputan'),
                perkiraan_berat: parseFloat(formData.get('perkiraan_berat')),
                harga_per_kg: parseFloat(formData.get('harga_per_kg')),
                catatan: formData.get('catatan')
            };

            try {
                const token = getToken();
                if (!token) {
                    alert('Anda harus login terlebih dahulu');
                    return;
                }

                // Get the first fish farm (for now, allow selection later)
                if (fishFarms.length === 0) {
                    alert('Tidak ada tambak yang tersedia');
                    return;
                }

                const fishFarmId = fishFarms[0].id; // Use first fish farm for now
                
                const response = await fetch(`/api/fish-farms/${fishFarmId}/appointments`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                if (response.ok) {
                    const result = await safeParseJSON(response);
                    if (result.success) {
                        alert('Janji penjemputan berhasil dibuat!');
                        closeAppointmentModal();
                    } else {
                        throw new Error(result.message || 'Gagal membuat janji');
                    }
                } else {
                    // Try to get error message safely
                    let errorMessage = 'Gagal membuat janji';
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
                console.error('Error creating appointment:', error);
                alert('Terjadi kesalahan: ' + error.message);
            }
        }

        // Nearest Collectors Functions
        async function loadNearestCollectors() {
            try {
                const token = getToken();
                if (!token) {
                    alert('Anda harus login terlebih dahulu');
                    window.location.href = '/login';
                    return;
                }

                // First test debug endpoint to check auth and role
                console.log('Testing debug endpoint...');
                const debugResponse = await fetch('/api/collectors/debug', {
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json'
                    }
                });

                if (debugResponse.ok) {
                    const debugData = await safeParseJSON(debugResponse);
                    console.log('Debug data:', debugData);
                    
                    if (!debugData.authenticated) {
                        alert('Authentication failed. Please log in again.');
                        window.location.href = '/login';
                        return;
                    }
                    
                    if (!debugData.is_pemilik_tambak && !debugData.is_admin) {
                        document.getElementById('nearestCollectorsContainer').innerHTML = `
                            <div class="empty-state">
                                <i class="fas fa-lock"></i>
                                <h3>Akses Terbatas</h3>
                                <p>Fitur ini hanya tersedia untuk pemilik tambak. Role Anda: ${debugData.user_role || 'Unknown'}</p>
                            </div>
                        `;
                        return;
                    }
                    
                    if (!debugData.has_coordinates) {
                        document.getElementById('nearestCollectorsContainer').innerHTML = `
                            <div class="empty-state">
                                <i class="fas fa-map-marker-alt"></i>
                                <h3>Lokasi Diperlukan</h3>
                                <p>Untuk menggunakan fitur pencarian pengepul terdekat, silakan update lokasi Anda terlebih dahulu.</p>
                                <button class="btn btn-primary" onclick="getCurrentLocation()">
                                    <i class="fas fa-location-arrow"></i> Update Lokasi
                                </button>
                            </div>
                        `;
                        return;
                    }
                }

                // Build query parameters
                const params = new URLSearchParams();
                
                const maxDistance = document.getElementById('maxDistanceFilter').value;
                if (maxDistance) params.append('max_distance', maxDistance);
                
                const fishType = document.getElementById('nearestFishTypeFilter').value;
                if (fishType) params.append('fish_type', fishType);
                
                const minRate = document.getElementById('minRateFilter').value;
                if (minRate) params.append('min_rate', minRate);
                
                const maxRate = document.getElementById('maxRateFilter').value;
                if (maxRate) params.append('max_rate', maxRate);

                console.log('Calling nearest collectors endpoint...');
                const response = await fetch(`/api/collectors/nearest?${params}`, {
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json'
                    }
                });

                const container = document.getElementById('nearestCollectorsContainer');

                if (response.ok) {
                    const result = await safeParseJSON(response);
                    console.log('Nearest collectors response:', result);
                    nearestCollectors = result.data.data || [];
                    userLocation = result.data.user_location;
                    
                    displayNearestCollectors();
                    updateLocationInfo(userLocation, result.data.search_radius);
                } else {
                    const errorData = await safeParseJSON(response);
                    console.error('API Error:', response.status, errorData);
                    
                    if (response.status === 403) {
                        container.innerHTML = `
                            <div class="empty-state">
                                <i class="fas fa-lock"></i>
                                <h3>Akses Terbatas</h3>
                                <p>Fitur ini hanya tersedia untuk pemilik tambak. Silakan daftar sebagai pemilik tambak untuk menggunakan fitur pencarian pengepul terdekat.</p>
                            </div>
                        `;
                    } else if (response.status === 400) {
                        container.innerHTML = `
                            <div class="empty-state">
                                <i class="fas fa-map-marker-alt"></i>
                                <h3>Lokasi Diperlukan</h3>
                                <p>Untuk menggunakan fitur pencarian pengepul terdekat, silakan update lokasi Anda terlebih dahulu.</p>
                                <button class="btn btn-primary" onclick="getCurrentLocation()">
                                    <i class="fas fa-location-arrow"></i> Update Lokasi
                                </button>
                            </div>
                        `;
                    } else if (response.status === 404) {
                        container.innerHTML = `
                            <div class="empty-state">
                                <i class="fas fa-search"></i>
                                <h3>Endpoint Tidak Ditemukan</h3>
                                <p>Fitur pencarian pengepul terdekat belum tersedia. Silakan gunakan tab "Cari Pengepul" untuk melihat semua pengepul.</p>
                                <button class="btn btn-secondary" onclick="switchTab('collectors')">
                                    <i class="fas fa-truck"></i> Lihat Semua Pengepul
                                </button>
                            </div>
                        `;
                    } else {
                        container.innerHTML = `
                            <div class="empty-state">
                                <i class="fas fa-exclamation-triangle"></i>
                                <h3>Gagal Memuat Data</h3>
                                <p>Status: ${response.status}<br>
                                Error: ${errorData.message || 'Terjadi kesalahan saat memuat pengepul terdekat'}</p>
                                <button class="btn btn-secondary" onclick="loadNearestCollectors()">
                                    <i class="fas fa-refresh"></i> Coba Lagi
                                </button>
                                <button class="btn btn-info" onclick="console.log('Debug info:', {status: ${response.status}, error: errorData}); alert('Check browser console for details')">
                                    <i class="fas fa-bug"></i> Debug Info
                                </button>
                            </div>
                        `;
                    }
                }
            } catch (error) {
                console.error('Error loading nearest collectors:', error);
                document.getElementById('nearestCollectorsContainer').innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-exclamation-triangle"></i>
                        <h3>Terjadi Kesalahan</h3>
                        <p>Gagal memuat pengepul terdekat. Silakan coba lagi.</p>
                        <button class="btn btn-secondary" onclick="loadNearestCollectors()">
                            <i class="fas fa-refresh"></i> Coba Lagi
                        </button>
                    </div>
                `;
            }
        }

        function displayNearestCollectors() {
            const container = document.getElementById('nearestCollectorsContainer');

            if (nearestCollectors.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-search"></i>
                        <h3>Tidak Ada Pengepul Terdekat</h3>
                        <p>Tidak ada pengepul yang ditemukan dalam radius pencarian Anda. Coba perluas jarak pencarian atau ubah filter.</p>
                        <button class="btn btn-secondary" onclick="document.getElementById('maxDistanceFilter').value = Math.min(parseInt(document.getElementById('maxDistanceFilter').value) + 50, 500); loadNearestCollectors()">
                            <i class="fas fa-search-plus"></i> Perluas Pencarian
                        </button>
                    </div>
                `;
                return;
            }

            container.innerHTML = nearestCollectors.map(collector => `
                <div class="collector-card">
                    <div class="card-image">
                        <img src="${collector.foto ? '/storage/' + collector.foto : '/images/default-collector.jpg'}" 
                             alt="${collector.nama_usaha}"
                             onerror="this.src='/images/default-collector.jpg'">
                        <div class="status-badge ${collector.status}">
                            ${collector.status === 'aktif' ? 'Aktif' : 'Tidak Aktif'}
                        </div>
                    </div>
                    <div class="card-content">
                        <h3>${collector.nama_usaha}</h3>
                        <p><i class="fas fa-user"></i> ${collector.user?.name || 'Pengepul'}</p>
                        <p><i class="fas fa-phone"></i> ${collector.no_telepon}</p>
                        <p><i class="fas fa-map-marker-alt"></i> ${collector.alamat}</p>
                        <p><i class="fas fa-fish"></i> ${Array.isArray(collector.jenis_ikan_diterima) ? collector.jenis_ikan_diterima.join(', ') : (collector.jenis_ikan_diterima || 'Semua jenis ikan')}</p>
                        <p><i class="fas fa-money-bill-wave"></i> Rp ${parseInt(collector.rate_per_kg || 0).toLocaleString('id-ID')}/kg</p>
                        <p><i class="fas fa-weight"></i> Kapasitas: ${collector.kapasitas_maximum} kg</p>
                        <p><i class="fas fa-clock"></i> ${collector.jam_operasional}</p>
                        
                        <div class="distance-info">
                            <i class="fas fa-route"></i> 
                            <strong>${collector.distance_formatted || 'Jarak tidak diketahui'}</strong>
                        </div>
                        
                        <div class="card-actions">
                            <button class="btn btn-secondary" onclick="viewCollector(${collector.id})">
                                <i class="fas fa-eye"></i> Lihat Detail
                            </button>
                            <button class="btn btn-primary" onclick="openAppointmentModal(${collector.id}, '${collector.nama_usaha}')">
                                <i class="fas fa-calendar-plus"></i> Buat Janji
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function updateLocationInfo(coordinates, searchRadius) {
            const locationInfo = document.getElementById('locationInfo');
            const locationText = document.getElementById('userLocationText');
            
            if (coordinates && coordinates.latitude && coordinates.longitude) {
                locationText.innerHTML = `
                    <strong>Koordinat:</strong> ${coordinates.latitude.toFixed(6)}, ${coordinates.longitude.toFixed(6)}<br>
                    <strong>Radius Pencarian:</strong> ${searchRadius}
                `;
                locationInfo.style.display = 'block';
            } else {
                locationInfo.style.display = 'none';
            }
        }

        async function getCurrentLocation() {
            if (!navigator.geolocation) {
                alert('Geolocation tidak didukung oleh browser Anda');
                return;
            }

            const options = {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 300000 // 5 minutes
            };

            navigator.geolocation.getCurrentPosition(
                async (position) => {
                    const { latitude, longitude } = position.coords;
                    
                    try {
                        const token = getToken();
                        const response = await fetch('/api/user', {
                            method: 'PUT',
                            headers: {
                                'Authorization': 'Bearer ' + token,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                latitude: latitude,
                                longitude: longitude
                            })
                        });

                        if (response.ok) {
                            userLocation = { latitude, longitude };
                            alert('Lokasi berhasil diperbarui! Sekarang Anda dapat mencari pengepul terdekat.');
                            loadNearestCollectors();
                        } else {
                            const errorData = await safeParseJSON(response);
                            alert('Gagal memperbarui lokasi: ' + (errorData.message || 'Terjadi kesalahan'));
                        }
                    } catch (error) {
                        console.error('Error updating location:', error);
                        alert('Terjadi kesalahan saat memperbarui lokasi');
                    }
                },
                (error) => {
                    let errorMessage = 'Gagal mendapatkan lokasi: ';
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            errorMessage += 'Akses lokasi ditolak. Silakan izinkan akses lokasi di browser Anda.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMessage += 'Informasi lokasi tidak tersedia.';
                            break;
                        case error.TIMEOUT:
                            errorMessage += 'Waktu permintaan lokasi habis.';
                            break;
                        default:
                            errorMessage += 'Terjadi kesalahan yang tidak diketahui.';
                            break;
                    }
                    alert(errorMessage);
                },
                options
            );
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('appointmentModal');
            if (event.target === modal) {
                closeAppointmentModal();
            }
        }
    </script>

    <!-- Appointment Modal -->
    <div id="appointmentModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-calendar-plus"></i> Buat Janji Penjemputan</h3>
                <span class="close" onclick="closeAppointmentModal()">&times;</span>
            </div>
            
            <div class="modal-body">
                <div class="collector-info">
                    <div class="collector-details">
                        <h4 id="modalCollectorName">Nama Pengepul</h4>
                        <p id="modalCollectorRate">Harga per kg</p>
                    </div>
                </div>

                <form id="appointmentForm">
                    <input type="hidden" id="collectorId" name="collector_id">
                    
                    <div class="form-group">
                        <label for="tanggalPenjemputan">Tanggal Penjemputan</label>
                        <input type="date" id="tanggalPenjemputan" name="tanggal_penjemputan" required>
                    </div>

                    <div class="form-group">
                        <label for="perkiraanBerat">Perkiraan Berat (kg)</label>
                        <input type="number" id="perkiraanBerat" name="perkiraan_berat" min="1" step="0.1" required>
                    </div>

                    <div class="form-group">
                        <label for="hargaPerKg">Harga per Kg (Rp)</label>
                        <input type="number" id="hargaPerKg" name="harga_per_kg" min="1000" step="100" required>
                    </div>

                    <div class="form-group">
                        <label for="catatan">Catatan (Opsional)</label>
                        <textarea id="catatan" name="catatan" rows="3" placeholder="Catatan tambahan untuk pengepul..."></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="closeAppointmentModal()">Batal</button>
                        <button type="button" class="btn btn-primary" onclick="submitAppointment()">
                            <i class="fas fa-paper-plane"></i> Kirim Permintaan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background-color: white;
            margin: 5% auto;
            border-radius: 15px;
            max-width: 500px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            animation: modalSlideIn 0.3s ease-out;
        }

        @keyframes modalSlideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            color: #2c3e50;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .close {
            font-size: 1.5rem;
            font-weight: bold;
            cursor: pointer;
            color: #aaa;
            transition: color 0.3s;
        }

        .close:hover {
            color: #000;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .collector-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            color: white;
        }

        .collector-details h4 {
            margin: 0 0 0.5rem 0;
            font-size: 1.2rem;
        }

        .collector-details p {
            margin: 0;
            opacity: 0.9;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #2c3e50;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.8rem;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 1.5rem;
        }

        .form-actions .btn {
            padding: 0.8rem 1.5rem;
        }
    </style>
</body>
</html>