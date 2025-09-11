<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lokasi Penjual - IwakMart</title>
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
        }
        
        /* Modal styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            display: none;
            justify-content: center;
            align-items: center;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
        }
        
        .modal-overlay.show {
            opacity: 1;
            visibility: visible;
        }
        
        .modal-container {
            background: white;
            border-radius: 16px;
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            padding: 0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            animation: modal-appear 0.3s ease-out;
        }
        
        @keyframes modal-appear {
            from { 
                opacity: 0; 
                transform: scale(0.9);
            }
            to { 
                opacity: 1;
                transform: scale(1);
            }
        }
        
        .modal-overlay.show .modal-container {
            transform: scale(1);
        }
        
        .modal-header {
            padding: 20px;
            background: linear-gradient(135deg, #1565C0, #0D47A1);
            color: white;
            border-radius: 16px 16px 0 0;
            position: relative;
        }
        
        .modal-close {
            position: absolute;
            top: 20px;
            right: 20px;
            color: white;
            background: rgba(255, 255, 255, 0.2);
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .modal-close:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.1);
        }
        
        .modal-body {
            padding: 20px;
        }
        
        .modal-footer {
            padding: 20px;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        
        /* Form styles */
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }
        
        .form-label::after {
            content: "*";
            color: #F44336;
            margin-left: 4px;
            font-weight: bold;
        }
        
        .form-label:not([for="appointmentDate"]):not([for="appointmentTime"]):not([for="appointmentPurpose"])::after {
            content: "";
        }
        
        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #1976D2;
            box-shadow: 0 0 0 2px rgba(25, 118, 210, 0.2);
            outline: none;
        }
        
        .form-control.error {
            border-color: #F44336;
            background-color: rgba(244, 67, 54, 0.05);
        }
        
        .form-error-message {
            color: #F44336;
            font-size: 12px;
            margin-top: 4px;
            display: none;
        }
        
        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }
        
        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #1976D2, #0D47A1);
            color: white;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #1565C0, #0A3D91);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(25, 118, 210, 0.3);
        }
        
        .btn-primary:disabled {
            background: #9E9E9E;
            transform: none;
            cursor: not-allowed;
            box-shadow: none;
        }
        
        .appointment-btn {
            position: relative;
            overflow: hidden;
        }
        
        .appointment-btn::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 5px;
            height: 5px;
            background: rgba(255, 255, 255, 0.5);
            opacity: 0;
            border-radius: 100%;
            transform: scale(1, 1) translate(-50%);
            transform-origin: 50% 50%;
        }
        
        @keyframes ripple {
            0% {
                transform: scale(0, 0);
                opacity: 0.5;
            }
            100% {
                transform: scale(20, 20);
                opacity: 0;
            }
        }
        
        .appointment-btn:focus:not(:active)::after {
            animation: ripple 1s ease-out;
        }
        
        .btn-secondary {
            background: #f5f5f5;
            color: #333;
        }
        
        .btn-secondary:hover {
            background: #e5e5e5;
        }
        
        /* Snackbar for notifications */
        .snackbar {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%) translateY(100px);
            background: #333;
            color: white;
            padding: 16px 24px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 1100;
            transition: all 0.3s ease;
            opacity: 0;
            font-weight: 500;
            display: flex;
            align-items: center;
            min-width: 300px;
            max-width: 90%;
        }
        
        .snackbar::before {
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            margin-right: 12px;
            font-size: 18px;
        }
        
        .snackbar.show {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
        }
        
        .snackbar.success {
            background: #4CAF50;
            border-left: 5px solid #2E7D32;
        }
        
        .snackbar.success::before {
            content: '\f00c';
        }
        
        .snackbar.error {
            background: #F44336;
            border-left: 5px solid #C62828;
        }
        
        .snackbar.error::before {
            content: '\f071';
        }
        
        .snackbar.info {
            background: #2196F3;
            border-left: 5px solid #0D47A1;
        }
        
        .snackbar.info::before {
            content: '\f05a';
        }

        .container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Custom App Bar with Gradient */
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
        
        .history-button {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
        }
        
        .history-button:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
            color: white;
        }

        /* Page Title */
        .page-title {
            margin-top: 24px;
            margin-bottom: 24px;
            text-align: center;
            color: #0D47A1;
        }

        .page-title h1 {
            font-size: 32px;
            margin-bottom: 8px;
        }

        .page-title p {
            color: #666;
            font-size: 16px;
        }

        /* Map Section */
        .map-section {
            max-width: 1200px;
            margin: 0 auto 32px;
            padding: 0 16px;
        }

        .section-title {
            font-size: 24px;
            font-weight: 600;
            color: #0D47A1;
            margin-bottom: 16px;
            text-align: center;
        }

        #sellerMap {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Main Content */
        .main-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 16px 32px;
            flex: 1;
        }

        /* Locations Container */
        .locations-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 24px;
            margin-top: 32px;
        }

        /* Location Card */
        .location-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .location-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        }

        .location-image {
            height: 180px;
            overflow: hidden;
            position: relative;
        }

        .location-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.5s ease;
        }

        .location-card:hover .location-image img {
            transform: scale(1.05);
        }

        .location-type {
            position: absolute;
            top: 16px;
            right: 16px;
            background: rgba(13, 71, 161, 0.8);
            color: white;
            padding: 6px 12px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
        }

        .location-details {
            padding: 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .location-name {
            font-size: 18px;
            font-weight: 700;
            color: #0D47A1;
            margin-bottom: 8px;
        }

        .location-description {
            color: #666;
            margin-bottom: 16px;
            font-size: 14px;
            flex-grow: 1;
        }

        .location-address {
            display: flex;
            gap: 8px;
            color: #555;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .location-address i {
            color: #1976D2;
        }

        .location-phone {
            display: flex;
            gap: 8px;
            color: #555;
            font-size: 14px;
            margin-bottom: 16px;
        }

        .location-phone i {
            color: #1976D2;
        }

        .location-hours {
            background: #F5F9FF;
            padding: 12px;
            border-radius: 8px;
            margin-top: auto;
        }

        .hours-title {
            font-size: 14px;
            font-weight: 600;
            color: #0D47A1;
            margin-bottom: 8px;
        }

        .hours-list {
            font-size: 12px;
            color: #555;
        }

        /* Loading State */
        .loading-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 300px;
        }

        .spinner {
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top: 4px solid #1976D2;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .loading-text {
            margin-top: 16px;
            color: #555;
        }

        /* Empty State */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 300px;
            text-align: center;
            padding: 32px;
        }

        .empty-icon {
            background: rgba(25, 118, 210, 0.1);
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: #1976D2;
            margin-bottom: 24px;
        }

        .empty-title {
            font-size: 24px;
            font-weight: 600;
            color: #0D47A1;
            margin-bottom: 8px;
        }

        .empty-text {
            color: #666;
            max-width: 400px;
        }

        /* Footer */
        .footer {
            background: #0D47A1;
            color: white;
            padding: 32px 0;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 16px;
            display: flex;
            justify-content: center;
        }

        .footer-text {
            text-align: center;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .locations-container {
                grid-template-columns: 1fr;
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
                    <a href="/fishmarket" class="back-button" title="Kembali ke Beranda">
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
            <h1>Lokasi Penjual Ikan</h1>
            <p>Temukan penjual ikan terdekat di sekitar Anda</p>
        </div>

        <!-- Map Container -->
        <div class="map-section">
            <h2 class="section-title">Peta Lokasi Penjual</h2>
            <div id="sellerMap" style="height: 400px; width: 100%; border-radius: 12px; border: 1px solid #e5e7eb; margin-bottom: 2rem;"></div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Loading State (initially shown) -->
            <div class="loading-container" id="loadingContainer">
                <div class="spinner"></div>
                <p class="loading-text">Memuat lokasi penjual...</p>
            </div>

            <!-- Empty State (hidden initially) -->
            <div class="empty-state" id="emptyState" style="display: none;">
                <div class="empty-icon">
                    <i class="fas fa-map-marker-slash"></i>
                </div>
                <h3 class="empty-title">Tidak Ada Lokasi</h3>
                <p class="empty-text">Belum ada lokasi penjual ikan yang tersedia saat ini. Silakan cek kembali nanti.</p>
            </div>

            <!-- Locations Container (hidden initially) -->
            <div class="locations-container" id="locationsContainer" style="display: none;">
                <!-- Locations will be added here dynamically -->
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
    
    <!-- Appointment Modal -->
    <div id="appointmentModal" class="modal-overlay">
        <div class="modal-container">
            <div class="modal-header">
                <h2>Buat Janji Temu</h2>
                <div class="modal-close" onclick="closeAppointmentModal()">
                    <i class="fas fa-times"></i>
                </div>
            </div>
            <div class="modal-body">
                <input type="hidden" id="locationId">
                <input type="hidden" id="sellerId">
                
                <div class="form-group">
                    <label class="form-label">Nama Lokasi</label>
                    <input type="text" id="locationName" class="form-control" readonly>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="appointmentDate">Tanggal Janji Temu</label>
                    <input type="date" id="appointmentDate" class="form-control" required>
                    <div class="form-error-message"></div>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="appointmentTime">Waktu Janji Temu</label>
                    <input type="time" id="appointmentTime" class="form-control" required>
                    <div class="form-error-message"></div>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="appointmentPurpose">Tujuan Janji Temu</label>
                    <select id="appointmentPurpose" class="form-control" required>
                        <option value="">Pilih tujuan...</option>
                        <option value="konsultasi">Konsultasi</option>
                        <option value="pembelian">Pembelian</option>
                        <option value="survei">Survei</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                    <div class="form-error-message"></div>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="appointmentNotes">Catatan</label>
                    <textarea id="appointmentNotes" class="form-control" placeholder="Tambahkan catatan atau detail tambahan..."></textarea>
                    <div class="form-error-message"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeAppointmentModal()">Batal</button>
                <button class="btn btn-primary" onclick="createAppointment()">Buat Janji Temu</button>
            </div>
        </div>
    </div>
    
    <!-- Snackbar for notifications -->
    <div id="snackbar" class="snackbar"></div>

    <script>
        // Map-related variables
        let sellerMap;
        let mapMarkers = [];

        document.addEventListener('DOMContentLoaded', function() {
            initializeMap();
            fetchLocations();
            setupModalEvents();
        });

        function initializeMap() {
            // Initialize map centered on Lamongan
            sellerMap = L.map('sellerMap').setView([-7.1192, 112.4186], 11);

            // Add OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(sellerMap);
        }

        function addMarkersToMap(locations) {
            // Clear existing markers
            mapMarkers.forEach(marker => {
                sellerMap.removeLayer(marker);
            });
            mapMarkers = [];

            // Add markers for each location
            locations.forEach(location => {
                if (location.latitude && location.longitude) {
                    const marker = L.marker([location.latitude, location.longitude])
                        .addTo(sellerMap);

                    // Create popup content
                    const popupContent = `
                        <div style="max-width: 200px;">
                            <h3 style="margin: 0 0 8px 0; font-size: 16px; color: #0D47A1;">${location.nama_usaha}</h3>
                            <p style="margin: 0 0 8px 0; font-size: 12px; color: #666;">${location.seller_type_text}</p>
                            <p style="margin: 0 0 8px 0; font-size: 12px;">${location.alamat_lengkap}</p>
                            <p style="margin: 0 0 8px 0; font-size: 12px;"><i class="fas fa-phone"></i> ${location.telepon}</p>
                            <button onclick="scrollToLocation(${location.id})" style="
                                background: #1976D2; 
                                color: white; 
                                border: none; 
                                padding: 4px 8px; 
                                border-radius: 4px; 
                                font-size: 12px; 
                                cursor: pointer;
                            ">Lihat Detail</button>
                        </div>
                    `;

                    marker.bindPopup(popupContent);
                    mapMarkers.push(marker);
                }
            });

            // Fit map to show all markers if there are any
            if (mapMarkers.length > 0) {
                const group = new L.featureGroup(mapMarkers);
                sellerMap.fitBounds(group.getBounds().pad(0.1));
            }
        }

        function scrollToLocation(locationId) {
            // Find the location card and scroll to it
            const locationCards = document.querySelectorAll('.location-card');
            locationCards.forEach(card => {
                const appointmentBtn = card.querySelector('.appointment-btn');
                if (appointmentBtn && appointmentBtn.onclick && appointmentBtn.onclick.toString().includes(`${locationId},`)) {
                    card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    // Add highlight effect
                    card.style.boxShadow = '0 0 20px rgba(25, 118, 210, 0.5)';
                    setTimeout(() => {
                        card.style.boxShadow = '';
                    }, 3000);
                }
            });
        }
        
        function setupModalEvents() {
            // Handle pressing Enter key in form fields
            const formFields = document.querySelectorAll('#appointmentModal input, #appointmentModal select');
            formFields.forEach(field => {
                field.addEventListener('keypress', function(event) {
                    if (event.key === 'Enter') {
                        event.preventDefault();
                        createAppointment();
                    }
                });
                
                // Add validation on blur
                field.addEventListener('blur', function() {
                    if (this.hasAttribute('required') && !this.value) {
                        this.classList.add('error');
                    } else {
                        this.classList.remove('error');
                    }
                });
            });
            
            // Close modal when clicking outside of it
            const modal = document.getElementById('appointmentModal');
            modal.addEventListener('click', function(event) {
                if (event.target === modal) {
                    closeAppointmentModal();
                }
            });
            
            // Escape key to close modal
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && modal.style.display === 'flex') {
                    closeAppointmentModal();
                }
            });
        }

        async function fetchLocations() {
            try {
                const response = await fetch('/api/locations', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to load locations');
                }

                const data = await response.json();
                
                // Hide loading container
                document.getElementById('loadingContainer').style.display = 'none';
                
                if (data && data.length > 0) {
                    // Display locations
                    displayLocations(data);
                } else {
                    // Show empty state
                    document.getElementById('emptyState').style.display = 'flex';
                    // Clear any existing map markers
                    addMarkersToMap([]);
                }
            } catch (error) {
                console.error('Error loading locations:', error);
                document.getElementById('loadingContainer').style.display = 'none';
                document.getElementById('emptyState').style.display = 'flex';
            }
        }

        function displayLocations(locations) {
            const container = document.getElementById('locationsContainer');
            container.innerHTML = ''; // Clear any existing content
            
            locations.forEach(location => {
                const hoursHtml = location.jam_operasional && location.jam_operasional.length > 0 
                    ? location.jam_operasional.map(hour => 
                        `<div>${hour.hari}: ${hour.jam_buka} - ${hour.jam_tutup}</div>`
                      ).join('')
                    : '<div>Jam operasional tidak tersedia</div>';
                
                const imageUrl = location.foto_urls && location.foto_urls.length > 0 
                    ? location.foto_urls[0] 
                    : '/images/default-location.jpg';
                
                // Encode location name to avoid issues with quotes in HTML attribute
                const encodedLocationName = location.nama_usaha.replace(/'/g, "\\'");
                
                const locationCard = document.createElement('div');
                locationCard.className = 'location-card';
                locationCard.innerHTML = `
                    <div class="location-image">
                        <img src="${imageUrl}" alt="${location.nama_usaha}" onerror="this.src='/images/default-location.jpg'">
                        <div class="location-type">${location.seller_type_text}</div>
                    </div>
                    <div class="location-details">
                        <h3 class="location-name">${location.nama_usaha}</h3>
                        <p class="location-description">${location.deskripsi || 'Tidak ada deskripsi'}</p>
                        <div class="location-address">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>${location.alamat_lengkap}, ${location.kecamatan}, ${location.kota}, ${location.provinsi}</span>
                        </div>
                        <div class="location-phone">
                            <i class="fas fa-phone"></i>
                            <span>${location.telepon}</span>
                        </div>
                        <div class="location-hours">
                            <div class="hours-title">Jam Operasional:</div>
                            <div class="hours-list">${hoursHtml}</div>
                        </div>
                        <button class="btn btn-primary appointment-btn" style="width: 100%; margin-top: 16px;" 
                          onclick="handleAppointmentButton(this, ${location.id}, '${encodedLocationName}', ${location.user.id})">
                            <i class="fas fa-calendar-check"></i> Buat Janji Temu
                        </button>
                    </div>
                `;
                
                container.appendChild(locationCard);
            });
            
            // Show the container
            container.style.display = 'grid';
            
            // Update map with location markers
            addMarkersToMap(locations);
        }
        
        function openAppointmentModal(locationId, locationName, sellerId) {
            // User is already authenticated (checked in handleAppointmentButton)
            // Set values to hidden inputs
            document.getElementById('locationId').value = locationId;
            document.getElementById('sellerId').value = sellerId;
            document.getElementById('locationName').value = locationName;
            
            // Set minimum date to today
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('appointmentDate').min = today;
            
            // Reset any previous validation errors
            clearFormErrors();
            
            // Show the modal with animation
            const modal = document.getElementById('appointmentModal');
            modal.style.display = 'flex';
        }
        
        // Removed checkUserAuthentication as we're now using Laravel's built-in auth()->check()
        
        function handleAppointmentButton(button, locationId, locationName, sellerId) {
            // Disable button and show loading state
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
            button.disabled = true;
            
            // Check if user is logged in with Laravel's auth
            const isLoggedIn = {{ auth()->check() ? 'true' : 'false' }};
            
            if (isLoggedIn) {
                // User is logged in, redirect to the new appointment form page
                window.location.href = '/appointments/create?location_id=' + locationId + '&seller_id=' + sellerId;
            } else {
                // User is not logged in, redirect to login page
                const returnUrl = encodeURIComponent(window.location.pathname);
                window.location.href = '/login?redirect=' + returnUrl;
            }
        }
        
        function closeAppointmentModal() {
            // Reset form
            document.getElementById('appointmentDate').value = '';
            document.getElementById('appointmentTime').value = '';
            document.getElementById('appointmentPurpose').value = '';
            document.getElementById('appointmentNotes').value = '';
            
            // Clear any validation errors
            clearFormErrors();
            
            // Hide modal
            document.getElementById('appointmentModal').style.display = 'none';
        }
        
        function clearFormErrors() {
            // Remove error class from all form controls
            const formControls = document.querySelectorAll('.form-control');
            formControls.forEach(control => {
                control.classList.remove('error');
            });
            
            // Hide all error messages
            const errorMessages = document.querySelectorAll('.form-error-message');
            errorMessages.forEach(message => {
                message.style.display = 'none';
            });
        }
        
        function showFieldError(fieldId, message) {
            const field = document.getElementById(fieldId);
            field.classList.add('error');
            
            // Find or create error message element
            let errorElement = field.nextElementSibling;
            if (!errorElement || !errorElement.classList.contains('form-error-message')) {
                errorElement = document.createElement('div');
                errorElement.className = 'form-error-message';
                field.parentNode.insertBefore(errorElement, field.nextSibling);
            }
            
            errorElement.textContent = message;
            errorElement.style.display = 'block';
        }
        
        function createAppointment() {
            // Clear previous errors
            clearFormErrors();
            
            // Validate required fields
            const date = document.getElementById('appointmentDate').value;
            const time = document.getElementById('appointmentTime').value;
            const purpose = document.getElementById('appointmentPurpose').value;
            
            let hasErrors = false;
            
            if (!date) {
                showFieldError('appointmentDate', 'Tanggal janji temu wajib diisi');
                hasErrors = true;
            }
            
            if (!time) {
                showFieldError('appointmentTime', 'Waktu janji temu wajib diisi');
                hasErrors = true;
            }
            
            if (!purpose) {
                showFieldError('appointmentPurpose', 'Tujuan janji temu wajib diisi');
                hasErrors = true;
            }
            
            if (hasErrors) {
                showSnackbar('Mohon isi semua field yang wajib diisi', 'error');
                return;
            }
            
            // Check if date is in the future
            const selectedDate = new Date(`${date}T${time}`);
            const now = new Date();
            
            if (selectedDate <= now) {
                showFieldError('appointmentDate', 'Tanggal dan waktu janji temu harus di masa depan');
                showSnackbar('Tanggal dan waktu janji temu harus di masa depan', 'error');
                return;
            }
            
            // Already checked authentication when opening modal, directly proceed with appointment creation
            // Prepare appointment data
            const appointmentData = {
                seller_id: document.getElementById('sellerId').value,
                location_id: document.getElementById('locationId').value,
                date: date,
                time: time,
                purpose: purpose,
                notes: document.getElementById('appointmentNotes').value
            };
            
            // Show loading state
            const submitBtn = document.querySelector('.modal-footer .btn-primary');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
            submitBtn.disabled = true;
            
            // Get the Laravel CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Submit appointment with proper CSRF token and credentials
            fetch('/api/appointments', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin', // Important for sending cookies
                body: JSON.stringify(appointmentData)
            })
            .then(response => {
                if (response.status === 401) {
                    // Session expired between modal opening and submission
                    window.location.href = '/login?redirect=' + encodeURIComponent(window.location.pathname);
                    throw new Error('Silakan login terlebih dahulu');
                }
                
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Terjadi kesalahan saat membuat janji temu');
                    });
                }
                return response.json();
            })
            .then(data => {
                showSnackbar('Janji temu berhasil dibuat!', 'success');
                closeAppointmentModal();
            })
            .catch(error => {
                console.error('Error creating appointment:', error);
                if (error.message !== 'Silakan login terlebih dahulu') {
                    showSnackbar(error.message || 'Terjadi kesalahan saat membuat janji temu', 'error');
                }
            })
            .finally(() => {
                // Reset button state
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        }
        
        function showSnackbar(message, type = 'info') {
            const snackbar = document.getElementById('snackbar');
            snackbar.textContent = message;
            snackbar.className = 'snackbar show ' + type;
            
            // Remove the class after the animation
            setTimeout(() => {
                snackbar.className = 'snackbar';
            }, 3000);
        }
        
        // Removed refreshCSRFToken function as we're now using Laravel's built-in CSRF protection
    </script>
</body>
</html>
