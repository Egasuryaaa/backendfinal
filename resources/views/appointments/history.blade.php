<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Riwayat Janji Temu - IwakMart</title>
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
        
        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }
        
        .card-header {
            background: linear-gradient(135deg, #1565C0, #0D47A1);
            color: white;
            padding: 16px 20px;
            font-weight: 600;
        }
        
        .card-body {
            padding: 20px;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }
        
        .table th {
            text-align: left;
            padding: 14px 15px;
            border-bottom: 2px solid #dee2e6;
            color: #495057;
            font-weight: 600;
            background-color: #f8f9fa;
        }
        
        .table td {
            padding: 14px 15px;
            border-bottom: 1px solid #dee2e6;
            vertical-align: middle;
        }
        
        .table tr:hover {
            background-color: rgba(0, 123, 255, 0.03);
        }
        
        /* Status Badge Styles */
        .badge {
            padding: 6px 10px;
            font-weight: 500;
            font-size: 0.75rem;
            border-radius: 4px;
            text-transform: uppercase;
            display: inline-block;
            line-height: 1;
        }

        .bg-warning {
            background-color: #ffeeba !important;
            color: #856404 !important;
        }
        
        .bg-success {
            background-color: #c3e6cb !important;
            color: #155724 !important;
        }
        
        .bg-danger {
            background-color: #f5c6cb !important;
            color: #721c24 !important;
        }
        
        .bg-primary {
            background-color: #b8daff !important;
            color: #004085 !important;
        }
        
        /* Action column width */
        .actions-column {
            width: 120px;
        }

        /* Seller column style */
        .seller-info {
            font-weight: 500;
        }

        .location-info {
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 2px;
        }
        
        .btn {
            display: inline-block;
            font-weight: 500;
            text-align: center;
            vertical-align: middle;
            cursor: pointer;
            padding: 8px 12px;
            font-size: 14px;
            line-height: 1.5;
            border-radius: 6px;
            transition: all 0.2s ease;
            text-decoration: none;
            margin-right: 5px;
            border: none;
        }
        
        .btn-sm {
            padding: 4px 8px;
            font-size: 12px;
        }
        
        .btn-primary {
            background: #1565C0;
            color: white;
        }
        
        .btn-primary:hover {
            background: #0D47A1;
        }
        
        .btn-info {
            background-color: #17a2b8;
            color: white;
        }
        
        .btn-info:hover {
            background-color: #138496;
        }
        
        .btn-success {
            background: #28a745;
            color: white;
        }
        
        .btn-success:hover {
            background: #218838;
        }
        
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        
        .btn-danger:hover {
            background: #c82333;
        }
        
        .badge {
            padding: 5px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .bg-warning {
            background-color: #ffc107;
        }
        
        .bg-success {
            background-color: #28a745;
            color: white;
        }
        
        .bg-primary {
            background-color: #1565C0;
            color: white;
        }
        
        .bg-danger {
            background-color: #dc3545;
            color: white;
        }
        
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .text-muted {
            color: #6c757d;
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

        /* Main Content */
        .main-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 16px 32px;
            flex: 1;
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

        .container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        @media (max-width: 768px) {
            .app-title {
                font-size: 20px;
            }
            
            .table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
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
                <a href="{{ route('locations') }}" class="history-button" title="Cari Lokasi Penjual">
                    <i class="fas fa-map-marked-alt"></i> Lihat Lokasi
                </a>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Page Title -->
        <div class="page-title">
            <h1>Riwayat Janji Temu</h1>
            <p>Kelola dan lihat janji temu Anda dengan penjual</p>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Daftar Janji Temu</h5>
                </div>
                    
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                    
                    @if(count($appointments) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Tanggal & Waktu</th>
                                        <th>Penjual & Lokasi</th>
                                        <th>Tujuan</th>
                                        <th>Status</th>
                                        <th class="actions-column">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($appointments as $appointment)
                                        <tr>
                                            <td>
                                                <div class="fw-bold">{{ \Carbon\Carbon::parse($appointment->tanggal_janji)->format('d M Y') }}</div>
                                                <small>{{ \Carbon\Carbon::parse($appointment->tanggal_janji)->format('H:i') }} WIB</small>
                                            </td>
                                            <td>
                                                <div class="seller-info">{{ $appointment->seller->name }}</div>
                                                <div class="location-info">{{ $appointment->sellerLocation->nama }}</div>
                                            </td>
                                            <td>{{ ucfirst($appointment->tujuan) }}</td>
                                            <td>
                                                @if($appointment->status == 'menunggu')
                                                    <span class="badge bg-warning">Menunggu Konfirmasi</span>
                                                @elseif($appointment->status == 'dikonfirmasi')
                                                    <span class="badge bg-success">Dikonfirmasi</span>
                                                @elseif($appointment->status == 'selesai')
                                                    <span class="badge bg-primary">Selesai</span>
                                                @elseif($appointment->status == 'dibatalkan')
                                                    <span class="badge bg-danger">Dibatalkan</span>
                                                @endif
                                            </td>
                                            <td class="actions-column">
                                                <div class="d-flex">
                                                    <a href="{{ route('appointments.show', $appointment->id) }}" class="btn btn-sm btn-info me-1" title="Lihat Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    
                                                    @if($appointment->status == 'menunggu')
                                                        <form action="{{ route('appointments.update-status', $appointment->id) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="status" value="dibatalkan">
                                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin membatalkan janji temu ini?')" title="Batalkan">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    
                                                    @if($appointment->status == 'dikonfirmasi')
                                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $appointment->sellerLocation->telepon) }}" target="_blank" class="btn btn-sm btn-success ms-1" title="Hubungi via WhatsApp">
                                                            <i class="fab fa-whatsapp"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-3">
                            {{ $appointments->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-calendar-times fa-4x text-muted opacity-50"></i>
                            </div>
                            <h5 class="mb-2">Belum Ada Riwayat Janji Temu</h5>
                            <p class="text-muted mb-4">Anda belum memiliki janji temu dengan penjual manapun</p>
                            <a href="{{ route('locations') }}" class="btn btn-primary">
                                <i class="fas fa-map-marked-alt me-2"></i> Cari Lokasi Penjual
                            </a>
                        </div>
                    @endif
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
    document.addEventListener('DOMContentLoaded', function() {
        // Handle close button for alerts
        const closeButtons = document.querySelectorAll('.btn-close');
        closeButtons.forEach(button => {
            button.addEventListener('click', function() {
                const alert = this.closest('.alert');
                if (alert) {
                    alert.style.display = 'none';
                }
            });
        });
    });
</script>
</body>
</html>
