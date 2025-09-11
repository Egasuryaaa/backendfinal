<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola Alamat - IwakMart</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #F0F8FF 0%, #E3F2FD 100%);
            color: #333;
            line-height: 1.6;
            min-height: 100vh;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #0D47A1 0%, #1565C0 25%, #1976D2 50%, #2196F3 100%);
            color: white;
            padding: 24px;
            box-shadow: 0 8px 40px rgba(0, 0, 0, 0.15);
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 25px;
            right: -35px;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            animation: float 3.5s ease-in-out infinite;
        }

        .header::after {
            content: '';
            position: absolute;
            top: 45px;
            left: -25px;
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 50%;
            animation: float 3.5s ease-in-out infinite reverse;
        }

        @keyframes float {
            0%, 100% { transform: translateY(-15px); }
            50% { transform: translateY(15px); }
        }

        .header-content {
            display: flex;
            align-items: center;
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .back-btn {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 12px;
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
            color: white;
            text-decoration: none;
        }

        .header-icon {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 16px;
            border-radius: 20px;
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.2);
        }

        .header-info h1 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .header-info p {
            font-size: 12px;
            opacity: 0.8;
        }

        .stats-container {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 20px;
            margin-top: 20px;
            display: flex;
            gap: 20px;
            backdrop-filter: blur(10px);
        }

        .stat-item {
            flex: 1;
            text-align: center;
        }

        .stat-icon {
            background: rgba(255, 255, 255, 0.2);
            padding: 8px;
            border-radius: 12px;
            display: inline-block;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 11px;
            opacity: 0.8;
        }

        .stat-divider {
            width: 1px;
            background: rgba(255, 255, 255, 0.3);
        }

        /* Content */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 24px;
        }

        /* Loading */
        .loading {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 300px;
            gap: 24px;
        }

        .loading-spinner {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #1976D2, #0D47A1);
            border-radius: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .spinner {
            width: 32px;
            height: 32px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top: 3px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .loading-text {
            font-size: 16px;
            font-weight: 600;
            color: #0D47A1;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 48px 32px;
        }

        .empty-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #1976D2, #0D47A1);
            border-radius: 60px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 32px;
            box-shadow: 0 15px 30px rgba(25, 118, 210, 0.3);
            animation: float 3s ease-in-out infinite;
        }

        .empty-title {
            font-size: 20px;
            font-weight: 700;
            color: #0D47A1;
            margin-bottom: 16px;
        }

        .empty-subtitle {
            font-size: 14px;
            color: #666;
            margin-bottom: 32px;
            line-height: 1.5;
        }

        .add-address-btn {
            background: linear-gradient(135deg, #1976D2, #0D47A1);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 10px 20px rgba(25, 118, 210, 0.4);
        }

        .add-address-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(25, 118, 210, 0.5);
        }

        /* Address Cards */
        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding: 20px 0;
            border-bottom: 1px solid rgba(25, 118, 210, 0.1);
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 18px;
            font-weight: 700;
            color: #0D47A1;
        }

        .section-title i {
            background: linear-gradient(135deg, #1976D2, #0D47A1);
            color: white;
            padding: 8px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(25, 118, 210, 0.3);
        }

        .address-card {
            background: white;
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid #E3F2FD;
            transition: all 0.3s ease;
        }

        .address-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
        }

        .address-card.main {
            border-color: rgba(76, 175, 80, 0.3);
            border-width: 2px;
            box-shadow: 0 8px 20px rgba(76, 175, 80, 0.1);
        }

        .address-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 16px;
        }

        .address-icon {
            padding: 12px;
            border-radius: 16px;
            background: linear-gradient(135deg, #1976D2, #0D47A1);
            color: white;
            box-shadow: 0 4px 10px rgba(25, 118, 210, 0.3);
        }

        .address-icon.main {
            background: linear-gradient(135deg, #4CAF50, #2E7D32);
            box-shadow: 0 4px 10px rgba(76, 175, 80, 0.3);
        }

        .address-name {
            font-size: 16px;
            font-weight: 700;
            color: #0D47A1;
            margin-bottom: 4px;
        }

        .main-badge {
            background: linear-gradient(135deg, #4CAF50, #2E7D32);
            color: white;
            font-size: 10px;
            font-weight: 700;
            padding: 4px 8px;
            border-radius: 12px;
        }

        .contact-info {
            background: rgba(25, 118, 210, 0.05);
            border: 1px solid rgba(25, 118, 210, 0.1);
            border-radius: 12px;
            padding: 14px;
            margin-bottom: 12px;
        }

        .contact-row {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .contact-icon {
            color: #1976D2;
            font-size: 16px;
        }

        .contact-label {
            color: #666;
            font-size: 13px;
        }

        .contact-value {
            color: #1976D2;
            font-size: 13px;
            font-weight: 600;
        }

        .address-details {
            background: #f9f9f9;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            padding: 14px;
            margin-bottom: 20px;
        }

        .address-label {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #4CAF50;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .address-text {
            color: #0D47A1;
            font-size: 13px;
            line-height: 1.4;
            margin-bottom: 8px;
        }

        .address-location {
            color: #666;
            font-size: 12px;
        }

        .address-actions {
            display: flex;
            gap: 12px;
        }

        .action-btn {
            flex: 1;
            border: 1px solid;
            background: transparent;
            padding: 12px;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 600;
        }

        .set-main-btn {
            border-color: rgba(76, 175, 80, 0.3);
            color: #4CAF50;
        }

        .set-main-btn:hover {
            background: rgba(76, 175, 80, 0.1);
        }

        .delete-btn {
            border-color: rgba(211, 47, 47, 0.3);
            color: #D32F2F;
        }

        .delete-btn:hover {
            background: rgba(211, 47, 47, 0.1);
        }

        /* Floating Action Button */
        .fab {
            position: fixed;
            bottom: 24px;
            right: 24px;
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #1976D2, #0D47A1);
            border: none;
            border-radius: 28px;
            color: white;
            cursor: pointer;
            box-shadow: 0 10px 20px rgba(25, 118, 210, 0.4);
            transition: all 0.3s ease;
            z-index: 100;
        }

        .fab:hover {
            transform: scale(1.1);
            box-shadow: 0 15px 30px rgba(25, 118, 210, 0.5);
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal.show {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 25px;
            padding: 32px;
            max-width: 600px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        .form-section {
            margin-bottom: 32px;
            padding: 20px;
            background: #F8FBFF;
            border-radius: 16px;
            border: 1px solid #E3F2FD;
        }

        .form-section:last-of-type {
            margin-bottom: 24px;
        }

        .modal-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
        }

        .modal-icon {
            background: linear-gradient(135deg, #1976D2, #0D47A1);
            padding: 8px;
            border-radius: 12px;
            color: white;
        }

        .modal-title {
            font-size: 18px;
            font-weight: 700;
            color: #0D47A1;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-label {
            font-size: 14px;
            font-weight: 600;
            color: #0D47A1;
            margin-bottom: 8px;
            display: block;
        }

        .form-control {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #E3F2FD;
            border-radius: 12px;
            font-size: 14px;
            background: #F8FBFF;
            transition: all 0.3s ease;
            font-family: 'Inter', sans-serif;
            height: 50px;
            display: flex;
            align-items: center;
        }

        .form-control:focus {
            outline: none;
            border-color: #1976D2;
            background: white;
            box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.1);
        }

        .form-control.has-icon {
            padding-left: 50px;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 80px;
            height: auto;
            align-items: flex-start;
            padding-top: 14px;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #1976D2;
            font-size: 16px;
            pointer-events: none;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
        }

        .form-group .input-icon {
            top: calc(50% + 8px);
        }

        .form-group textarea + .input-icon {
            top: calc(25% + 16px);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 80px;
            height: auto;
            align-items: flex-start;
            padding-top: 14px;
        }

        .form-row {
            display: flex;
            gap: 16px;
        }

        .form-col {
            flex: 1;
        }

        .form-col-2 {
            flex: 2;
        }

        .modal-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 24px;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-secondary {
            background: #f5f5f5;
            color: #666;
        }

        .btn-secondary:hover {
            background: #e0e0e0;
        }

        .btn-primary {
            background: linear-gradient(135deg, #1976D2, #0D47A1);
            color: white;
            box-shadow: 0 4px 12px rgba(25, 118, 210, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(25, 118, 210, 0.4);
        }

        .btn-danger {
            background: #D32F2F;
            color: white;
        }

        .btn-danger:hover {
            background: #B71C1C;
        }

        /* Notifications */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 16px 20px;
            border-radius: 12px;
            color: white;
            font-weight: 500;
            z-index: 2000;
            transform: translateX(400px);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .notification.show {
            transform: translateX(0);
        }

        .notification.success {
            background: #4CAF50;
        }

        .notification.error {
            background: #D32F2F;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header {
                padding: 20px 16px;
            }

            .header-content {
                flex-direction: row;
                align-items: center;
                justify-content: flex-start;
                text-align: left;
                gap: 16px;
            }

            .stats-container {
                flex-direction: column;
                gap: 16px;
                margin-top: 16px;
                padding: 16px;
            }

            .stat-divider {
                display: none;
            }

            .container {
                padding: 16px;
            }

            .section-header {
                flex-direction: column;
                gap: 16px;
                align-items: stretch;
            }

            .address-actions {
                flex-direction: column;
                gap: 8px;
            }

            .action-btn {
                justify-content: center;
            }

            .modal {
                padding: 10px;
            }

            .modal-content {
                max-width: 100%;
                margin: 0;
                padding: 24px;
            }

            .form-row {
                flex-direction: column;
                gap: 0;
            }

            .form-section {
                padding: 16px;
                margin-bottom: 20px;
            }

            .fab {
                bottom: 16px;
                right: 16px;
            }
        }

        @media (max-width: 480px) {
            .header-info h1 {
                font-size: 20px;
            }

            .address-card {
                padding: 16px;
            }

            .address-header {
                gap: 12px;
            }

            .contact-info,
            .address-details {
                padding: 12px;
            }

            .modal-content {
                padding: 20px;
            }

            .form-section {
                padding: 12px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-content">
            <a href="/fishmarket" class="back-btn">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div class="header-icon">
                <i class="fas fa-location-dot"></i>
            </div>
            <div class="header-info">
                <h1>Kelola Alamat</h1>
                <p>Atur alamat pengiriman Anda</p>
            </div>
        </div>
        <div class="stats-container">
            <div class="stat-item">
                <div class="stat-icon">
                    <i class="fas fa-location-dot"></i>
                </div>
                <div class="stat-value" id="totalAddresses">0</div>
                <div class="stat-label">Total Alamat</div>
            </div>
            <div class="stat-divider"></div>
            <div class="stat-item">
                <div class="stat-icon">
                    <i class="fas fa-home"></i>
                </div>
                <div class="stat-value" id="mainAddresses">0</div>
                <div class="stat-label">Alamat Utama</div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Loading State -->
        <div id="loadingState" class="loading">
            <div class="loading-spinner">
                <div class="spinner"></div>
            </div>
            <div class="loading-text">Memuat alamat...</div>
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="empty-state" style="display: none;">
            <div class="empty-icon">
                <i class="fas fa-location-slash" style="font-size: 50px; color: white;"></i>
            </div>
            <div class="empty-title">Belum ada alamat tersimpan</div>
            <div class="empty-subtitle">
                Tambahkan alamat untuk memudahkan<br>
                proses pengiriman pesanan Anda
            </div>
            <button class="add-address-btn" onclick="showAddModal()">
                <i class="fas fa-plus"></i>
                Tambah Alamat
            </button>
        </div>

        <!-- Address List -->
        <div id="addressList"></div>
        
        <!-- Address List Container with Header -->
        <div id="addressListContainer" style="display: none;">
            <div class="section-header">
                <div class="section-title">
                    <i class="fas fa-location-dot"></i>
                    Daftar Alamat
                </div>
                <button class="btn btn-primary" onclick="showAddModal()">
                    <i class="fas fa-plus"></i>
                    Tambah Alamat Baru
                </button>
            </div>
            <div id="addressCards"></div>
        </div>
    </div>

    <!-- Floating Action Button -->
    <button class="fab" onclick="showAddModal()" id="fab" style="display: none;">
        <i class="fas fa-plus"></i>
    </button>

    <!-- Add/Edit Address Modal -->
    <div id="addressModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon">
                    <i class="fas fa-location-plus"></i>
                </div>
                <div class="modal-title" id="modalTitle">Tambah Alamat Baru</div>
            </div>
            <form id="addressForm">
                <!-- Personal Information Section -->
                <div class="form-section">
                    <h4 style="color: #0D47A1; margin-bottom: 16px; font-size: 16px; font-weight: 600;">
                        <i class="fas fa-user" style="margin-right: 8px;"></i>
                        Informasi Penerima
                    </h4>
                    
                    <div class="form-group">
                        <label class="form-label">Nama Penerima</label>
                        <div style="position: relative;">
                            <input type="text" class="form-control has-icon" id="nama_penerima" placeholder="Masukkan nama lengkap penerima" required>
                            <i class="input-icon fas fa-user"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">No. Telepon</label>
                        <div style="position: relative;">
                            <input type="tel" class="form-control has-icon" id="telepon" placeholder="Contoh: 08123456789" required>
                            <i class="input-icon fas fa-phone"></i>
                        </div>
                    </div>
                </div>

                <!-- Address Information Section -->
                <div class="form-section">
                    <h4 style="color: #0D47A1; margin-bottom: 16px; font-size: 16px; font-weight: 600;">
                        <i class="fas fa-location-dot" style="margin-right: 8px;"></i>
                        Detail Alamat
                    </h4>
                    
                    <div class="form-group">
                        <label class="form-label">Alamat Lengkap</label>
                        <div style="position: relative;">
                            <textarea class="form-control has-icon" id="alamat_lengkap" rows="3" placeholder="Jalan, Gang, Nomor Rumah, RT/RW, Patokan" required></textarea>
                            <i class="input-icon fas fa-location-dot"></i>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-col-2">
                            <div class="form-group">
                                <label class="form-label">Provinsi</label>
                                <div style="position: relative;">
                                    <input type="text" class="form-control has-icon" id="provinsi" placeholder="Contoh: Jawa Timur" required>
                                    <i class="input-icon fas fa-map"></i>
                                </div>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label">Kode Pos</label>
                                <div style="position: relative;">
                                    <input type="text" class="form-control has-icon" id="kode_pos" placeholder="12345" maxlength="5" required>
                                    <i class="input-icon fas fa-mail-bulk"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label">Kota/Kabupaten</label>
                                <div style="position: relative;">
                                    <input type="text" class="form-control has-icon" id="kota" placeholder="Contoh: Surabaya" required>
                                    <i class="input-icon fas fa-building"></i>
                                </div>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label">Kecamatan</label>
                                <div style="position: relative;">
                                    <input type="text" class="form-control has-icon" id="kecamatan" placeholder="Contoh: Gubeng" required>
                                    <i class="input-icon fas fa-map-marker-alt"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="hideAddModal()">
                        <i class="fas fa-times" style="margin-right: 6px;"></i>
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save" style="margin-right: 6px;"></i>
                        Simpan Alamat
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon" style="background: #D32F2F;">
                    <i class="fas fa-trash"></i>
                </div>
                <div class="modal-title">Hapus Alamat</div>
            </div>
            <p id="deleteMessage">Apakah Anda yakin ingin menghapus alamat ini?</p>
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" onclick="hideDeleteModal()">
                    <i class="fas fa-times" style="margin-right: 6px;"></i>
                    Batal
                </button>
                <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                    <i class="fas fa-trash" style="margin-right: 6px;"></i>
                    Hapus
                </button>
            </div>
        </div>
    </div>

    <script>
        // State
        let addresses = [];
        let isLoading = false;
        let editingId = null;
        let deletingId = null;

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            fetchAddresses();
            
            // Format phone number input
            const teleponInput = document.getElementById('telepon');
            teleponInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/[^0-9]/g, '');
                if (value.length > 13) {
                    value = value.substring(0, 13);
                }
                e.target.value = value;
            });

            // Format postal code input
            const kodePostInput = document.getElementById('kode_pos');
            kodePostInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/[^0-9]/g, '');
                if (value.length > 5) {
                    value = value.substring(0, 5);
                }
                e.target.value = value;
            });

            // Auto capitalize first letter for text inputs
            const textInputs = ['nama_penerima', 'alamat_lengkap', 'provinsi', 'kota', 'kecamatan'];
            textInputs.forEach(id => {
                const input = document.getElementById(id);
                input.addEventListener('input', function(e) {
                    const words = e.target.value.split(' ');
                    const capitalizedWords = words.map(word => {
                        if (word.length > 0) {
                            return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
                        }
                        return word;
                    });
                    e.target.value = capitalizedWords.join(' ');
                });
            });
        });

        // Fetch addresses
        async function fetchAddresses() {
            showLoading(true);
            try {
                const response = await fetch('/api/addresses', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const data = await response.json();
                if (data.success) {
                    addresses = data.data || [];
                    renderAddresses();
                    updateStats();
                } else {
                    showNotification('Gagal mengambil alamat', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Terjadi kesalahan', 'error');
            }
            showLoading(false);
        }

        // Render addresses
        function renderAddresses() {
            const addressList = document.getElementById('addressList');
            const addressListContainer = document.getElementById('addressListContainer');
            const addressCards = document.getElementById('addressCards');
            const emptyState = document.getElementById('emptyState');
            const fab = document.getElementById('fab');

            if (addresses.length === 0) {
                addressList.innerHTML = '';
                addressListContainer.style.display = 'none';
                emptyState.style.display = 'block';
                fab.style.display = 'none';
                return;
            }

            emptyState.style.display = 'none';
            addressListContainer.style.display = 'block';
            fab.style.display = 'block';

            addressCards.innerHTML = addresses.map(address => {
                const isMain = address.utama; // Gunakan field 'utama' sesuai database
                return `
                    <div class="address-card ${isMain ? 'main' : ''}">
                        <div class="address-header">
                            <div class="address-icon ${isMain ? 'main' : ''}">
                                <i class="fas fa-${isMain ? 'home' : 'location-dot'}"></i>
                            </div>
                            <div style="flex: 1;">
                                <div class="address-name">
                                    ${address.nama_penerima || 'Nama Penerima'}
                                    ${isMain ? '<span class="main-badge">Alamat Utama</span>' : ''}
                                </div>
                            </div>
                        </div>

                        <div class="contact-info">
                            <div class="contact-row">
                                <i class="contact-icon fas fa-phone"></i>
                                <span class="contact-label">Telepon:</span>
                                <span class="contact-value">${address.telepon || '-'}</span>
                            </div>
                        </div>

                        <div class="address-details">
                            <div class="address-label">
                                <i class="fas fa-location-dot"></i>
                                Alamat Lengkap:
                            </div>
                            <div class="address-text">${address.alamat_lengkap || '-'}</div>
                            <div class="address-location">
                                ${address.kecamatan || '-'}, ${address.kota || '-'}, ${address.provinsi || '-'} ${address.kode_pos || '-'}
                            </div>
                        </div>

                        <div class="address-actions">
                            ${!isMain ? `
                                <button class="action-btn set-main-btn" onclick="setAsMain(${address.id})">
                                    <i class="fas fa-home"></i>
                                    Jadikan Utama
                                </button>
                            ` : ''}
                            <button class="action-btn delete-btn" onclick="showDeleteModal(${address.id}, '${address.nama_penerima || 'Alamat'}')">
                                <i class="fas fa-trash"></i>
                                Hapus
                            </button>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // Update stats
        function updateStats() {
            document.getElementById('totalAddresses').textContent = addresses.length;
            document.getElementById('mainAddresses').textContent = addresses.filter(addr => addr.utama).length;
        }

        // Show/hide loading
        function showLoading(show) {
            document.getElementById('loadingState').style.display = show ? 'flex' : 'none';
            document.getElementById('addressList').style.display = show ? 'none' : 'block';
            document.getElementById('addressListContainer').style.display = show ? 'none' : (addresses.length > 0 ? 'block' : 'none');
        }

        // Show add modal
        function showAddModal() {
            editingId = null;
            document.getElementById('modalTitle').textContent = 'Tambah Alamat Baru';
            document.getElementById('addressForm').reset();
            document.getElementById('addressModal').classList.add('show');
        }

        // Hide add modal
        function hideAddModal() {
            document.getElementById('addressModal').classList.remove('show');
        }

        // Show delete modal
        function showDeleteModal(id, name) {
            deletingId = id;
            document.getElementById('deleteMessage').textContent = `Apakah Anda yakin ingin menghapus alamat "${name}"?`;
            document.getElementById('deleteModal').classList.add('show');
        }

        // Hide delete modal
        function hideDeleteModal() {
            document.getElementById('deleteModal').classList.remove('show');
            deletingId = null;
        }

        // Handle form submission
        document.getElementById('addressForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Validate phone number
            const telepon = document.getElementById('telepon').value;
            if (!/^[0-9+\-\s]{10,15}$/.test(telepon)) {
                showNotification('Nomor telepon tidak valid. Gunakan format: 08123456789', 'error');
                return;
            }

            // Validate postal code
            const kodePos = document.getElementById('kode_pos').value;
            if (!/^[0-9]{5}$/.test(kodePos)) {
                showNotification('Kode pos harus 5 digit angka', 'error');
                return;
            }
            
            const formData = {
                nama_penerima: document.getElementById('nama_penerima').value.trim(),
                telepon: telepon.trim(),
                alamat_lengkap: document.getElementById('alamat_lengkap').value.trim(),
                provinsi: document.getElementById('provinsi').value.trim(),
                kota: document.getElementById('kota').value.trim(),
                kecamatan: document.getElementById('kecamatan').value.trim(),
                kode_pos: kodePos.trim()
            };

            // Check if all fields are filled
            for (const [key, value] of Object.entries(formData)) {
                if (!value) {
                    showNotification('Semua field harus diisi', 'error');
                    return;
                }
            }

            try {
                const response = await fetch('/api/addresses', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();
                if (data.success) {
                    showNotification('Alamat berhasil ditambahkan', 'success');
                    hideAddModal();
                    fetchAddresses();
                } else {
                    let errorMsg = 'Gagal menambahkan alamat';
                    if (data.errors) {
                        errorMsg = Object.values(data.errors).flat().join(', ');
                    } else if (data.message) {
                        errorMsg = data.message;
                    }
                    showNotification(errorMsg, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Terjadi kesalahan saat menyimpan alamat', 'error');
            }
        });

        // Set as main address
        async function setAsMain(id) {
            try {
                const response = await fetch(`/api/addresses/${id}/set-as-main`, {
                    method: 'PUT',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();
                if (data.success) {
                    showNotification('Alamat utama berhasil diubah', 'success');
                    fetchAddresses();
                } else {
                    showNotification('Gagal mengubah alamat utama', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Terjadi kesalahan', 'error');
            }
        }

        // Confirm delete
        async function confirmDelete() {
            if (!deletingId) return;

            try {
                const response = await fetch(`/api/addresses/${deletingId}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();
                if (data.success) {
                    showNotification('Alamat berhasil dihapus', 'success');
                    hideDeleteModal();
                    fetchAddresses();
                } else {
                    showNotification('Gagal menghapus alamat', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Terjadi kesalahan', 'error');
            }
        }

        // Show notification
        function showNotification(message, type = 'success') {
            // Remove existing notification
            const existing = document.querySelector('.notification');
            if (existing) {
                existing.remove();
            }

            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i>
                <span>${message}</span>
            `;
            
            document.body.appendChild(notification);
            
            // Show notification
            setTimeout(() => {
                notification.classList.add('show');
            }, 100);

            // Hide notification after 3 seconds
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 3000);
        }

        // Close modals when clicking outside
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('modal')) {
                e.target.classList.remove('show');
            }
        });
    </script>
</body>
</html>
