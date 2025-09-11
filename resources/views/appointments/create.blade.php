<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Buat Janji Temu - IwakMart</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #F0F8FF; /* Alice Blue background */
            min-height: 100vh;
            line-height: 1.6;
            color: #333;
        }
        
        .custom-app-bar {
            background: linear-gradient(135deg, #1565C0, #0D47A1, #002171);
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            position: relative;
            overflow: hidden;
        }

        .custom-app-bar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(255,255,255,0.1) 25%, transparent 25%, transparent 75%, rgba(255,255,255,0.1) 75%);
            background-size: 30px 30px;
        }

        .app-bar-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .app-bar-left {
            display: flex;
            align-items: center;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-icon {
            background: rgba(255, 255, 255, 0.2);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }

        .app-title {
            color: white;
            font-size: 24px;
            font-weight: 700;
        }

        .app-bar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .back-button {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .back-button:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }
        
        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin-bottom: 24px;
            border: none;
        }
        
        .card-header {
            background: linear-gradient(135deg, #1565C0, #0D47A1);
            color: white;
            padding: 16px 20px;
            font-weight: 600;
            border: none;
        }
        
        .card-header h5 {
            margin: 0;
            font-size: 18px;
        }
        
        .card-body {
            padding: 25px;
        }
        
        .form-group {
            margin-bottom: 1.8rem;
        }
        
        .form-label {
            font-weight: 500;
            margin-bottom: 8px;
            display: block;
            color: #333;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-family: 'Inter', sans-serif;
            background-color: #fff;
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            font-size: 16px;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #1565C0;
            box-shadow: 0 0 0 3px rgba(21, 101, 192, 0.15);
            transform: translateY(-1px);
        }
        
        .form-text {
            color: #666;
            font-size: 13px;
            margin-top: 5px;
            display: block;
        }
        
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }
        
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -15px;
        }
        
        .col-md-8 {
            width: 100%;
            padding: 0 15px;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .py-5 {
            padding-top: 3rem;
            padding-bottom: 3rem;
        }
        
        .btn {
            padding: 10px 16px;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
        }
        
        .btn {
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #1565C0, #0D47A1);
            color: white;
            border: none;
            box-shadow: 0 4px 8px rgba(0,105,217,0.2);
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #0D47A1, #002171);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,105,217,0.3);
        }
        
        .btn-outline-secondary {
            background: transparent;
            color: #666;
            border: 1px solid #ddd;
        }
        
        .btn-outline-secondary:hover {
            background: #f5f5f5;
            border-color: #bbb;
            color: #444;
        }
        
        .btn-success {
            background: #28a745;
            color: white;
        }
        
        .btn-success:hover {
            background: #218838;
        }
        
        .btn-outline-primary {
            background: transparent;
            color: #1565C0;
            border: 1px solid #1565C0;
        }
        
        .btn-outline-primary:hover {
            background: rgba(21, 101, 192, 0.1);
        }
        
        .seller-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .icon-wrapper {
            width: 20px;
            display: flex;
            justify-content: center;
        }
        
        .mb-3 {
            margin-bottom: 1rem;
        }
        
        .mb-4 {
            margin-bottom: 1.5rem;
        }
        
        .p-3 {
            padding: 1rem;
        }
        
        .rounded {
            border-radius: 8px;
        }
        
        .bg-light {
            background-color: #f8f9fa;
        }
        
        .rounded-circle {
            border-radius: 50%;
        }
        
        .p-2 {
            padding: 0.5rem;
        }
        
        .me-3 {
            margin-right: 1rem;
        }
        
        .me-2 {
            margin-right: 0.5rem;
        }
        
        .mb-0 {
            margin-bottom: 0;
        }
        
        .d-flex {
            display: flex;
        }
        
        .text-white {
            color: white;
        }
        
        .text-primary {
            color: #1565C0;
        }
        
        .text-muted {
            color: #6c757d;
        }
        
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        
        .align-items-center {
            align-items: center;
        }
        
        .justify-content-between {
            justify-content: space-between;
        }
        
        .fw-bold {
            font-weight: 700;
        }
        
        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 0.25rem;
            position: relative;
        }
        
        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
        
        .is-invalid {
            border-color: #dc3545 !important;
        }
        
        .invalid-feedback {
            display: block;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 80%;
            color: #dc3545;
        }
        
        .form-select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-family: 'Inter', sans-serif;
            background-color: white;
        }
        
        .form-select:focus {
            outline: none;
            border-color: #1565C0;
            box-shadow: 0 0 0 3px rgba(21, 101, 192, 0.1);
        }
        
        /* Contact Information Styles */
        .contact-info {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }
        
        .contact-info h6 {
            font-weight: 600;
            margin-bottom: 15px;
            color: #333;
        }
        
        .contact-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 12px;
            color: #555;
        }
        
        .contact-item i {
            margin-right: 10px;
            color: #0070ba;
            width: 20px;
            text-align: center;
            margin-top: 3px;
        }
        
        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
        }
        
        .btn-whatsapp {
            background-color: #25d366;
            color: white;
            border: none;
            padding: 8px 16px;
            flex: 1;
            text-align: center;
            border-radius: 6px;
        }
        
        .btn-whatsapp:hover {
            background-color: #1da851;
            color: white;
        }
        
        .btn-maps {
            background-color: #4285F4;
            color: white;
            border: none;
            padding: 8px 16px;
            flex: 1;
            text-align: center;
            border-radius: 6px;
        }
        
        .btn-maps:hover {
            background-color: #3367d6;
            color: white;
        }

        /* Page Title */
        .page-title {
            margin-top: 24px;
            margin-bottom: 30px;
            text-align: center;
            color: #0D47A1;
            position: relative;
        }

        .page-title h1 {
            font-size: 32px;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .page-title p {
            color: #666;
            font-size: 16px;
            margin-bottom: 15px;
        }
        
        .page-title-underline {
            height: 4px;
            width: 60px;
            background: linear-gradient(135deg, #1565C0, #0D47A1);
            margin: 0 auto;
            border-radius: 2px;
        }

        /* Main Content */
        .main-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 16px 32px;
            flex: 1;
        }
        
        .container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        @media (max-width: 768px) {
            .app-title {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- App Bar -->
        <div class="custom-app-bar">
            <div class="app-bar-content">
                <div class="app-bar-left">
                    <a href="/locations" class="back-button" title="Kembali ke Lokasi">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div class="logo-section" style="margin-left: 16px;">
                        <div class="logo-icon">
                            <i class="fas fa-fish"></i>
                        </div>
                        <h1 class="app-title">IwakMart</h1>
                    </div>
                </div>
                <div class="app-bar-right">
                    <a href="{{ route('appointments.history') }}" class="history-button" title="Lihat Riwayat Janji Temu">
                        <i class="fas fa-calendar-check"></i> Riwayat Janji Temu
                    </a>
                </div>
            </div>
        </div>

        <!-- Page Title -->
        <div class="page-title">
            <h1>Buat Janji Temu</h1>
            <p>Jadwalkan pertemuan dengan penjual ikan untuk pembelian atau konsultasi</p>
            <div class="page-title-underline"></div>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-calendar-plus me-2"></i> Form Janji Temu</h5>
                </div>
                    
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0" style="padding-left: 16px;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        </div>
                    @endif
                    
                    <p class="text-muted mb-4">Silahkan isi formulir di bawah untuk membuat janji temu dengan penjual. Pastikan informasi yang Anda berikan benar dan sesuai.</p>
                    
                    <!-- Contact Information -->
                    <div class="contact-info">
                        <h6><strong>Informasi Kontak Penjual</strong></h6>
                        
                        <div class="contact-item">
                            <i class="fas fa-store"></i>
                            <div>
                                <strong>{{ $sellerLocation->nama_usaha }}</strong>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <div>
                                <span>{{ $sellerLocation->alamat_lengkap }}, {{ $sellerLocation->kecamatan }}</span><br>
                                <span>{{ $sellerLocation->kota }}, {{ $sellerLocation->provinsi }}</span>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <span>{{ $sellerLocation->telepon }}</span>
                        </div>
                        
                        <div class="contact-item">
                            <i class="fas fa-clock"></i>
                            <div>
                                <span>Senin-Jumat: {{ $sellerLocation->jam_buka }} - {{ $sellerLocation->jam_tutup }}</span>
                            </div>
                        </div>
                        
                        <div class="action-buttons">
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $sellerLocation->telepon) }}" target="_blank" class="btn btn-whatsapp">
                                <i class="fab fa-whatsapp me-2"></i> Hubungi via WhatsApp
                            </a>
                            
                            <a href="https://www.google.com/maps?q={{ $sellerLocation->latitude }},{{ $sellerLocation->longitude }}" target="_blank" class="btn btn-maps">
                                <i class="fas fa-map-marked-alt me-2"></i> Lihat di Maps
                            </a>
                        </div>
                    </div>
                    
                    <form method="POST" action="{{ route('appointments.store') }}">
                        @csrf
                        <input type="hidden" name="location_id" value="{{ $sellerLocation->id }}">
                        <input type="hidden" name="seller_id" value="{{ $sellerLocation->user_id }}">
                        
                        <div class="form-group">
                            <label for="date" class="form-label">Tanggal Janji Temu*</label>
                            <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date" 
                                   value="{{ old('date') }}" min="{{ date('Y-m-d') }}" required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text">Pilih tanggal sesuai dengan jam operasional lokasi</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="time" class="form-label">Waktu Janji Temu*</label>
                            <input type="time" class="form-control @error('time') is-invalid @enderror" id="time" name="time" 
                                   value="{{ old('time') }}" required>
                            @error('time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text">Pilih waktu antara {{ $sellerLocation->jam_buka }} - {{ $sellerLocation->jam_tutup }}</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="purpose" class="form-label">Tujuan Janji Temu*</label>
                            <select class="form-control @error('purpose') is-invalid @enderror" id="purpose" name="purpose" required>
                                <option value="" {{ old('purpose') == "" ? 'selected' : '' }}>Pilih tujuan...</option>
                                <option value="konsultasi" {{ old('purpose') == "konsultasi" ? 'selected' : '' }}>Konsultasi</option>
                                <option value="pembelian" {{ old('purpose') == "pembelian" ? 'selected' : '' }}>Pembelian</option>
                                <option value="survei" {{ old('purpose') == "survei" ? 'selected' : '' }}>Survei</option>
                                <option value="lainnya" {{ old('purpose') == "lainnya" ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('purpose')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="notes" class="form-label">Catatan Tambahan</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" 
                                      rows="4" placeholder="Tambahkan catatan atau detail tambahan...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Google Maps Location Picker -->
                        <div class="form-group">
                            <label class="form-label">Lokasi Pertemuan (Opsional)</label>
                            <small class="form-text mb-2">Pilih lokasi khusus untuk pertemuan, atau kosongkan untuk menggunakan lokasi penjual</small>
                            
                            <!-- Search input -->
                            <div class="mb-3">
                                <input type="text" id="locationSearch" class="form-control" 
                                       placeholder="Cari lokasi pertemuan...">
                            </div>

                            <!-- Map container -->
                            <div id="meetingLocationMap" style="height: 300px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 10px;"></div>
                            
                            <!-- Selected location info -->
                            <div id="selectedLocationInfo" style="display: none;" class="alert alert-info">
                                <strong>Lokasi Terpilih:</strong>
                                <div id="selectedLocationAddress"></div>
                                <div id="selectedLocationCoords" style="font-size: 12px; color: #666;"></div>
                            </div>

                            <!-- Hidden inputs for coordinates -->
                            <input type="hidden" id="meetingLatitude" name="meeting_latitude">
                            <input type="hidden" id="meetingLongitude" name="meeting_longitude">
                            <input type="hidden" id="meetingAddress" name="meeting_address">
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; margin-top: 32px;">
                            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-calendar-check me-1"></i> Buat Janji Temu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="footer-content">
                <div class="footer-text">
                    &copy; 2025 IwakMart. All rights reserved.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let map = null;
let marker = null;

document.addEventListener('DOMContentLoaded', function() {
    // Set min date to today
    const dateInput = document.getElementById('date');
    if (dateInput) {
        const today = new Date().toISOString().split('T')[0];
        dateInput.setAttribute('min', today);
    }

    // Wait for Leaflet to load before initializing map
    if (typeof L !== 'undefined') {
        initMap();
    } else {
        // Wait for Leaflet to load
        const checkLeaflet = setInterval(() => {
            if (typeof L !== 'undefined') {
                clearInterval(checkLeaflet);
                initMap();
            }
        }, 100);
    }
});

function initMap() {
    // Check if map is already initialized
    if (map !== null) {
        return;
    }

    // Default location (Lamongan)
    const defaultLocation = [-7.1192, 112.4186];
    
    // Seller location (if available)
    @if($sellerLocation->latitude && $sellerLocation->longitude)
        const sellerLocation = [{{ $sellerLocation->latitude }}, {{ $sellerLocation->longitude }}];
    @else
        const sellerLocation = defaultLocation;
    @endif

    try {
        // Initialize map
        map = L.map('meetingLocationMap').setView(sellerLocation, 13);

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Add seller location marker
        const sellerMarker = L.marker(sellerLocation, {
            title: '{{ $sellerLocation->nama_usaha }}'
        }).addTo(map);

        sellerMarker.bindPopup(`
            <div>
                <strong>{{ $sellerLocation->nama_usaha }}</strong><br>
                Lokasi Penjual
            </div>
        `);

        // Add click event to map
        map.on('click', function(e) {
            selectLocation(e.latlng.lat, e.latlng.lng);
        });

    } catch (error) {
        console.error('Error initializing map:', error);
    }
}

function selectLocation(lat, lng) {
    // Remove existing marker
    if (marker) {
        map.removeLayer(marker);
    }

    // Add new marker
    marker = L.marker([lat, lng], {
        draggable: true,
        title: 'Lokasi Pertemuan'
    }).addTo(map);

    marker.bindPopup(`
        <div>
            <strong>Lokasi Pertemuan</strong><br>
            ${lat.toFixed(6)}, ${lng.toFixed(6)}
        </div>
    `);

    // Handle marker drag
    marker.on('dragend', function(e) {
        const latlng = e.target.getLatLng();
        reverseGeocode(latlng.lat, latlng.lng);
        updateLocationInfo(latlng.lat, latlng.lng, '');
    });

    // Update form with coordinates
    updateLocationInfo(lat, lng, '');
    
    // Reverse geocode to get address
    reverseGeocode(lat, lng);
}

async function reverseGeocode(lat, lng) {
    try {
        const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}&accept-language=id,en`);
        const data = await response.json();
        
        if (data && data.display_name) {
            updateLocationInfo(lat, lng, data.display_name);
        } else {
            updateLocationInfo(lat, lng, `${lat.toFixed(6)}, ${lng.toFixed(6)}`);
        }
    } catch (error) {
        console.error('Error getting address:', error);
        updateLocationInfo(lat, lng, `${lat.toFixed(6)}, ${lng.toFixed(6)}`);
    }
}

async function searchLocation() {
    const query = document.getElementById('locationSearch').value;
    if (!query) return;

    try {
        const response = await fetch(`https://nominatim.openstreetmap.org/search?format=jsonv2&q=${encodeURIComponent(query)}&countrycodes=id&limit=5&accept-language=id,en`);
        const results = await response.json();
        
        if (results && results.length > 0) {
            const result = results[0];
            const lat = parseFloat(result.lat);
            const lng = parseFloat(result.lon);
            
            map.setView([lat, lng], 15);
            selectLocation(lat, lng);
            updateLocationInfo(lat, lng, result.display_name);
        }
    } catch (error) {
        console.error('Error searching location:', error);
    }
}
// Add search functionality
document.getElementById('locationSearch').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        searchLocation();
    }
});

function updateLocationInfo(lat, lng, address) {
    // Update hidden inputs
    document.getElementById('meetingLatitude').value = lat;
    document.getElementById('meetingLongitude').value = lng;
    document.getElementById('meetingAddress').value = address;

    // Update info display
    document.getElementById('selectedLocationAddress').textContent = address;
    document.getElementById('selectedLocationCoords').textContent = `Koordinat: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
    document.getElementById('selectedLocationInfo').style.display = 'block';
}
</script>
</body>
</html>
