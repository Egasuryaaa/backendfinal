<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detail Janji Temu - IwakMart</title>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDzqwsx9CdVPiKUWKpi4FSqieAmojz2mlw&libraries=places&language=id&region=ID"></script>
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
            background: linear-gradient(135deg, #F0F8FF 0%, #E3F2FD 100%);
            min-height: 100vh;
            line-height: 1.6;
            color: #333;
        }
        
        .custom-app-bar {
            background: linear-gradient(135deg, #2563EB, #1D4ED8, #1E40AF);
            padding: 12px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .app-bar-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .app-bar-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .logo-icon {
            background: rgba(255, 255, 255, 0.1);
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
        }

        .app-title {
            color: white;
            font-size: 22px;
            font-weight: 600;
            margin: 0;
        }

        .app-bar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .back-button {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: white;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            font-size: 16px;
        }

        .history-button {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
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

        .back-button:hover, .history-button:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }
        
        .container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .page-title {
            text-align: center;
            padding: 40px 20px;
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(10px);
            margin-bottom: 32px;
        }

        .page-title h1 {
            font-size: 36px;
            font-weight: 700;
            color: #0D47A1;
            margin-bottom: 8px;
        }

        .page-title p {
            color: #666;
            font-size: 18px;
            font-weight: 400;
        }

        .main-content {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px 40px;
            flex: 1;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .card-header {
            background: linear-gradient(135deg, #1565C0, #0D47A1);
            color: white;
            padding: 24px;
            font-weight: 600;
            font-size: 18px;
        }
        
        .card-body {
            padding: 32px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 24px;
            padding: 16px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .detail-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .detail-item {
            flex: 1;
        }

        .detail-label {
            display: block;
            color: #666;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .detail-value {
            font-weight: 600;
            font-size: 16px;
            color: #333;
        }

        .detail-full {
            margin-bottom: 24px;
        }

        .detail-full .detail-label {
            margin-bottom: 8px;
        }

        .detail-full .detail-value {
            line-height: 1.6;
        }
        
        .badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .bg-warning {
            background: linear-gradient(135deg, #FFC107, #FF8F00);
            color: #000;
        }
        
        .bg-success {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }
        
        .bg-primary {
            background: linear-gradient(135deg, #1565C0, #0D47A1);
            color: white;
        }
        
        .bg-danger {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
        }
        
        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            border: none;
        }
        
        .alert-success {
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.1), rgba(32, 201, 151, 0.1));
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .seller-location-info {
            background: linear-gradient(135deg, rgba(21, 101, 192, 0.05), rgba(13, 71, 161, 0.05));
            border: 1px solid rgba(21, 101, 192, 0.1);
            border-radius: 16px;
            padding: 24px;
            margin: 32px 0;
        }

        .seller-location-info h6 {
            color: #0D47A1;
            font-weight: 700;
            font-size: 18px;
            margin-bottom: 20px;
        }
        
        .seller-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .seller-icon {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #1565C0, #0D47A1);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            margin-right: 16px;
            box-shadow: 0 4px 12px rgba(21, 101, 192, 0.3);
        }

        .seller-info h5 {
            font-size: 20px;
            font-weight: 700;
            color: #333;
            margin-bottom: 4px;
        }

        .seller-info p {
            color: #666;
            margin: 0;
            font-size: 14px;
        }

        .location-details {
            background: white;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid rgba(0,0,0,0.05);
        }

        .location-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 12px;
            padding: 8px 0;
        }

        .location-item:last-child {
            margin-bottom: 16px;
        }

        .icon-wrapper {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            flex-shrink: 0;
        }

        .location-item span {
            flex: 1;
            line-height: 1.5;
        }

        .btn {
            padding: 12px 20px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 14px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #1565C0, #0D47A1);
            color: white;
            box-shadow: 0 4px 12px rgba(21, 101, 192, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(21, 101, 192, 0.4);
        }
        
        .btn-outline-secondary {
            background: transparent;
            color: #666;
            border: 2px solid #ddd;
        }
        
        .btn-outline-secondary:hover {
            background: #f8f9fa;
            border-color: #999;
            transform: translateY(-1px);
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
        }
        
        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
        }
        
        .btn-success {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
        }
        
        .btn-outline-primary {
            background: transparent;
            color: #1565C0;
            border: 2px solid #1565C0;
        }
        
        .btn-outline-primary:hover {
            background: rgba(21, 101, 192, 0.1);
            transform: translateY(-1px);
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 13px;
        }

        .button-group {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            margin-top: 32px;
            justify-content: center;
        }

        .button-group .btn {
            min-width: 160px;
        }

        .action-buttons {
            display: flex;
            gap: 12px;
            margin-top: 16px;
            flex-wrap: wrap;
        }
        
        .footer {
            background: linear-gradient(135deg, #0D47A1, #002171);
            color: white;
            padding: 24px 0;
            text-align: center;
            margin-top: auto;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .app-title {
                font-size: 18px;
            }

            .logo-icon {
                width: 32px;
                height: 32px;
                font-size: 16px;
            }

            .back-button {
                width: 32px;
                height: 32px;
                font-size: 14px;
            }

            .history-button {
                padding: 6px 12px;
                font-size: 12px;
            }

            .history-button span {
                display: none;
            }

            .page-title h1 {
                font-size: 28px;
            }

            .card-body {
                padding: 24px;
            }

            .detail-row {
                flex-direction: column;
                gap: 8px;
            }

            .seller-header {
                flex-direction: column;
                text-align: center;
            }

            .seller-icon {
                margin: 0 0 12px 0;
            }

            .button-group {
                flex-direction: column;
            }

            .button-group .btn {
                min-width: 100%;
            }

            .action-buttons {
                flex-direction: column;
            }

            .action-buttons .btn {
                width: 100%;
            }

            .app-bar-content {
                padding: 0 16px;
            }

            .main-content {
                padding: 0 16px 32px;
            }
        }

        @media (max-width: 480px) {
            .app-bar-right {
                flex-direction: column;
                gap: 8px;
            }

            .history-button {
                font-size: 12px;
                padding: 8px 12px;
            }
        }

        /* Animation */
        .card {
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .btn {
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="custom-app-bar">
            <div class="app-bar-content">
                <div class="app-bar-left">
                    <a href="{{ route('appointments.history') }}" class="back-button" title="Kembali ke Riwayat Janji Temu">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div class="logo-section">
                        <div class="logo-icon">
                            <i class="fas fa-fish"></i>
                        </div>
                        <h1 class="app-title">IwakMart</h1>
                    </div>
                </div>
                <div class="app-bar-right">
                    <a href="{{ route('locations') }}" class="history-button" title="Lihat Lokasi">
                        <i class="fas fa-map-marked-alt"></i>
                        <span>Lihat Lokasi</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="page-title">
            <h1>Detail Janji Temu</h1>
            <p>Informasi lengkap tentang janji temu Anda</p>
        </div>
        
        <div class="main-content">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-info-circle"></i> Detail Janji Temu
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success" role="alert">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                        </div>
                    @endif

                    <div class="detail-row">
                        <div class="detail-item">
                            <span class="detail-label">ID Janji Temu</span>
                            <span class="detail-value">{{ $appointment->id }}</span>
                        </div>
                        <div class="detail-item" style="text-align: right;">
                            <span class="detail-label">Status</span>
                            <div>
                                @if($appointment->status == 'menunggu')
                                    <span class="badge bg-warning">Menunggu Konfirmasi</span>
                                @elseif($appointment->status == 'dikonfirmasi')
                                    <span class="badge bg-success">Dikonfirmasi</span>
                                @elseif($appointment->status == 'selesai')
                                    <span class="badge bg-primary">Selesai</span>
                                @elseif($appointment->status == 'dibatalkan')
                                    <span class="badge bg-danger">Dibatalkan</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-item">
                            <span class="detail-label">Tanggal Janji Temu</span>
                            <span class="detail-value">{{ \Carbon\Carbon::parse($appointment->tanggal_janji)->format('d M Y') }}</span>
                        </div>
                        <div class="detail-item" style="text-align: right;">
                            <span class="detail-label">Waktu Janji Temu</span>
                            <span class="detail-value">{{ \Carbon\Carbon::parse($appointment->tanggal_janji)->format('H:i') }} WIB</span>
                        </div>
                    </div>

                    <div class="detail-full">
                        <span class="detail-label">Tujuan Janji Temu</span>
                        <div class="detail-value">{{ ucfirst($appointment->tujuan) }}</div>
                    </div>

                    <div class="detail-full">
                        <span class="detail-label">Catatan Tambahan</span>
                        <div class="detail-value">{{ $appointment->catatan ?: 'Tidak ada catatan' }}</div>
                    </div>

                    <div class="seller-location-info">
                        <h6><i class="fas fa-store"></i> Informasi Lokasi Penjual</h6>
                        <div class="seller-header">
                            <div class="seller-icon">
                                <i class="fas fa-store"></i>
                            </div>
                            <div class="seller-info">
                                <h5>{{ $appointment->sellerLocation->nama_usaha }}</h5>
                                <p>{{ $appointment->seller->name }}</p>
                            </div>
                        </div>
                        
                        <div class="location-details">
                            <div class="location-item">
                                <div class="icon-wrapper">
                                    <i class="fas fa-map-marker-alt" style="color: #1565C0;"></i>
                                </div>
                                <span>{{ $appointment->sellerLocation->alamat_lengkap }}, {{ $appointment->sellerLocation->kecamatan }}, {{ $appointment->sellerLocation->kota }}, {{ $appointment->sellerLocation->provinsi }}</span>
                            </div>
                            <div class="location-item">
                                <div class="icon-wrapper">
                                    <i class="fas fa-phone" style="color: #1565C0;"></i>
                                </div>
                                <span>{{ $appointment->sellerLocation->telepon }}</span>
                            </div>
                            
                            <div class="action-buttons">
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $appointment->sellerLocation->telepon) }}" target="_blank" class="btn btn-success btn-sm">
                                    <i class="fab fa-whatsapp"></i> Hubungi via WhatsApp
                                </a>
                                <a href="https://www.google.com/maps?q={{ $appointment->sellerLocation->latitude }},{{ $appointment->sellerLocation->longitude }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-map"></i> Lihat di Maps
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Meeting Location Map -->
                    @if($appointment->meeting_location || ($appointment->sellerLocation->latitude && $appointment->sellerLocation->longitude))
                    <div class="seller-location-info">
                        <h6><i class="fas fa-map-marked-alt"></i> Lokasi Pertemuan</h6>
                        
                        @if($appointment->meeting_location)
                            <div class="location-item">
                                <div class="icon-wrapper">
                                    <i class="fas fa-map-pin" style="color: #DC2626;"></i>
                                </div>
                                <div>
                                    <strong>Lokasi Khusus Pertemuan:</strong><br>
                                    <span>{{ $appointment->meeting_location['address'] ?? 'Lokasi khusus dipilih' }}</span><br>
                                    <small style="color: #666;">Koordinat: {{ number_format($appointment->meeting_location['lat'], 6) }}, {{ number_format($appointment->meeting_location['lng'], 6) }}</small>
                                </div>
                            </div>
                        @else
                            <div class="location-item">
                                <div class="icon-wrapper">
                                    <i class="fas fa-store" style="color: #2563EB;"></i>
                                </div>
                                <span>Pertemuan di lokasi penjual</span>
                            </div>
                        @endif

                        <div id="appointmentMap" style="height: 350px; border: 1px solid #ddd; border-radius: 8px; margin: 15px 0;"></div>
                        
                        <div class="action-buttons">
                            @if($appointment->meeting_location)
                                <a href="https://www.google.com/maps?q={{ $appointment->meeting_location['lat'] }},{{ $appointment->meeting_location['lng'] }}" target="_blank" class="btn btn-primary btn-sm">
                                    <i class="fas fa-directions"></i> Rute ke Lokasi Pertemuan
                                </a>
                            @endif
                            <a href="https://www.google.com/maps?q={{ $appointment->sellerLocation->latitude }},{{ $appointment->sellerLocation->longitude }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-directions"></i> Rute ke Lokasi Penjual
                            </a>
                        </div>
                    </div>
                    @endif

                    <div class="button-group">
                        <a href="{{ route('appointments.history') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali ke Riwayat
                        </a>
                        @if($appointment->status == 'menunggu')
                            <form action="{{ route('appointments.update-status', $appointment->id) }}" method="POST" style="margin: 0;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="dibatalkan">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin membatalkan janji temu ini?')">
                                    <i class="fas fa-times"></i> Batalkan Janji Temu
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <div class="footer-content">
                <div class="footer-text">
                    &copy; 2025 IwakMart. All rights reserved.
                </div>
            </div>
        </div>
    </div>

    <script>
        let map;

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Google Maps if location data is available
            @if($appointment->sellerLocation->latitude && $appointment->sellerLocation->longitude)
                initMap();
            @endif

            // Handle close button for alerts
            const closeButtons = document.querySelectorAll('.btn-close');
            closeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const alert = this.closest('.alert');
                    if (alert) {
                        alert.style.opacity = '0';
                        setTimeout(() => {
                            alert.style.display = 'none';
                        }, 300);
                    }
                });
            });

            // Add loading animation to buttons
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    if (this.type === 'submit') {
                        this.style.pointerEvents = 'none';
                        const originalText = this.innerHTML;
                        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
                        
                        setTimeout(() => {
                            this.innerHTML = originalText;
                            this.style.pointerEvents = 'auto';
                        }, 2000);
                    }
                });
            });
        });

        function initMap() {
            // Seller location
            const sellerLocation = { 
                lat: {{ $appointment->sellerLocation->latitude }}, 
                lng: {{ $appointment->sellerLocation->longitude }} 
            };

            @if($appointment->meeting_location)
                // Meeting location (if exists)
                const meetingLocation = { 
                    lat: {{ $appointment->meeting_location['lat'] }}, 
                    lng: {{ $appointment->meeting_location['lng'] }} 
                };
                const centerLocation = meetingLocation;
            @else
                const centerLocation = sellerLocation;
            @endif

            // Initialize map
            map = new google.maps.Map(document.getElementById('appointmentMap'), {
                zoom: 15,
                center: centerLocation,
                mapTypeControl: false,
                streetViewControl: false,
                fullscreenControl: true
            });

            // Add seller location marker
            const sellerMarker = new google.maps.Marker({
                position: sellerLocation,
                map: map,
                title: '{{ $appointment->sellerLocation->nama_usaha }}',
                icon: {
                    url: 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png'
                }
            });

            // Add seller info window
            const sellerInfoWindow = new google.maps.InfoWindow({
                content: `
                    <div style="padding: 8px; max-width: 250px;">
                        <h6 style="margin: 0 0 5px 0; color: #2563EB;">{{ $appointment->sellerLocation->nama_usaha }}</h6>
                        <p style="margin: 0 0 5px 0; font-size: 12px;">{{ $appointment->sellerLocation->alamat_lengkap }}</p>
                        <small style="color: #666;">Lokasi Penjual</small>
                    </div>
                `
            });

            sellerMarker.addListener('click', () => {
                sellerInfoWindow.open(map, sellerMarker);
            });

            @if($appointment->meeting_location)
                // Add meeting location marker
                const meetingMarker = new google.maps.Marker({
                    position: meetingLocation,
                    map: map,
                    title: 'Lokasi Pertemuan',
                    icon: {
                        url: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png'
                    }
                });

                // Add meeting location info window
                const meetingInfoWindow = new google.maps.InfoWindow({
                    content: `
                        <div style="padding: 8px; max-width: 250px;">
                            <h6 style="margin: 0 0 5px 0; color: #DC2626;">Lokasi Pertemuan</h6>
                            <p style="margin: 0 0 5px 0; font-size: 12px;">{{ $appointment->meeting_location['address'] ?? 'Lokasi khusus dipilih' }}</p>
                            <small style="color: #666;">Koordinat: {{ number_format($appointment->meeting_location['lat'], 6) }}, {{ number_format($appointment->meeting_location['lng'], 6) }}</small>
                        </div>
                    `
                });

                meetingMarker.addListener('click', () => {
                    meetingInfoWindow.open(map, meetingMarker);
                });

                // Draw line between seller and meeting location
                const path = new google.maps.Polyline({
                    path: [sellerLocation, meetingLocation],
                    geodesic: true,
                    strokeColor: '#1565C0',
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    map: map
                });

                // Adjust map bounds to show both locations
                const bounds = new google.maps.LatLngBounds();
                bounds.extend(sellerLocation);
                bounds.extend(meetingLocation);
                map.fitBounds(bounds);
            @else
                // Show seller info window by default if no meeting location
                sellerInfoWindow.open(map, sellerMarker);
            @endif
        }
    </script>
</body>
</html>