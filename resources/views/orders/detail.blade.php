<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detail Pesanan - IwakMart</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Auth Script -->
    <script src="/js/auth.js"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #F0F8FF;
            color: #333;
            line-height: 1.6;
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding-bottom: 20px;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #1565C0 0%, #0D47A1 50%, #002171 100%);
            color: white;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            width: 100%;
        }

        .header-content {
            display: flex;
            align-items: center;
            gap: 16px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .back-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .header-title {
            font-size: 24px;
            font-weight: 700;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        .detail-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
            border: 1px solid #E5E5E5;
            margin-bottom: 20px;
        }

        .detail-card .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid #E5E5E5;
        }

        .order-number {
            font-size: 20px;
            font-weight: 700;
            color: #1565C0;
        }

        .order-status {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            text-transform: capitalize;
        }

        .order-status.menunggu { background: #FFF3E0; color: #F57C00; }
        .order-status.diproses { background: #E3F2FD; color: #1976D2; }
        .order-status.dikirim { background: #E8F5E8; color: #388E3C; }
        .order-status.selesai { background: #E8F5E8; color: #2E7D32; }
        .order-status.dibatalkan { background: #FFEBEE; color: #D32F2F; }

        .order-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .info-item {
            padding: 12px;
            background: #F8F9FA;
            border-radius: 8px;
        }

        .info-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 14px;
            font-weight: 500;
            color: #333;
        }

        /* Order Items */
        .order-items {
            margin-bottom: 24px;
        }

        .order-item {
            display: flex;
            gap: 16px;
            padding: 16px;
            border: 1px solid #E5E5E5;
            border-radius: 12px;
            margin-bottom: 12px;
            background: #FAFAFA;
        }

        .item-image {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            object-fit: cover;
        }

        .item-info {
            flex: 1;
        }

        .item-name {
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 4px;
            color: #333;
        }

        .item-details {
            color: #666;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .item-price {
            font-weight: 600;
            color: #1565C0;
        }

        /* Address Info */
        .address-info {
            background: #F8F9FA;
            padding: 16px;
            border-radius: 8px;
            border-left: 4px solid #4CAF50;
        }

        .address-name {
            font-weight: 600;
            margin-bottom: 4px;
        }

        .address-details {
            color: #666;
            font-size: 14px;
        }

        /* Price Summary */
        .price-summary {
            border-top: 2px solid #E5E5E5;
            padding-top: 16px;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
        }

        .price-row.total {
            font-weight: 700;
            font-size: 18px;
            color: #1565C0;
            border-top: 1px solid #E5E5E5;
            margin-top: 8px;
            padding-top: 8px;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 12px;
            margin-top: 20px;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: #1565C0;
            color: white;
        }

        .btn-primary:hover {
            background: #0D47A1;
            transform: translateY(-1px);
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
            transform: translateY(-1px);
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-success:hover {
            background: #218838;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-1px);
        }

        /* Loading */
        .loading {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 300px;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #1565C0;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .error-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .error-state i {
            font-size: 64px;
            color: #DDD;
            margin-bottom: 16px;
        }

        /* Section Headers */
        .section-header {
            font-size: 18px;
            font-weight: 700;
            color: #333;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-icon {
            color: #1565C0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .order-info {
                grid-template-columns: 1fr;
            }

            .order-item {
                flex-direction: column;
                text-align: center;
            }

            .action-buttons {
                flex-direction: column;
            }
        }

        /* Manual Payment Modal Styles */
        .manual-payment-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .manual-payment-modal.show {
            opacity: 1;
            visibility: visible;
        }

        .manual-payment-content {
            background: white;
            border-radius: 20px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            transform: scale(0.8);
            transition: transform 0.3s ease;
        }

        .manual-payment-modal.show .manual-payment-content {
            transform: scale(1);
        }

        .manual-payment-header {
            background: linear-gradient(135deg, #1565C0 0%, #0D47A1 100%);
            color: white;
            padding: 24px;
            border-radius: 20px 20px 0 0;
            display: flex;
            justify-content: between;
            align-items: center;
            position: relative;
        }

        .manual-payment-header h3 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .close-btn {
            position: absolute;
            top: 24px;
            right: 24px;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s ease;
        }

        .close-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .manual-payment-body {
            padding: 24px;
        }

        .payment-status-card, .payment-deadline-card, .bank-account-card, .instructions-card, .warning-card {
            background: #F8F9FA;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            border-left: 5px solid #1565C0;
        }

        .payment-deadline-card {
            border-left-color: #F57C00;
            background: linear-gradient(135deg, #FFF8E1 0%, #FFFDE7 100%);
        }

        .warning-card {
            border-left-color: #D32F2F;
            background: linear-gradient(135deg, #FFEBEE 0%, #FCE4EC 100%);
        }

        .bank-account-card {
            border-left-color: #4CAF50;
            background: linear-gradient(135deg, #E8F5E8 0%, #F1F8E9 100%);
        }

        .payment-status-icon {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            color: #1565C0;
            font-size: 16px;
            margin-bottom: 12px;
        }

        .bank-info-grid {
            display: grid;
            gap: 16px;
        }

        .bank-info-item {
            background: white;
            padding: 16px;
            border-radius: 8px;
            border: 1px solid #E5E5E5;
        }

        .bank-info-label {
            font-size: 12px;
            font-weight: 600;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .bank-info-value {
            font-size: 16px;
            font-weight: 700;
            color: #333;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .copy-btn {
            background: linear-gradient(135deg, #4CAF50 0%, #66BB6A 100%);
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
        }

        .copy-btn:hover {
            background: linear-gradient(135deg, #2E7D32 0%, #4CAF50 100%);
            transform: translateY(-1px);
        }

        .instructions-title, .warning-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            color: #333;
            font-size: 16px;
            margin-bottom: 12px;
        }

        .warning-title {
            color: #D32F2F;
        }

        .instructions-list, .warning-list {
            margin: 0;
            padding-left: 20px;
            color: #333;
            font-size: 14px;
            line-height: 1.6;
        }

        .warning-list {
            color: #D32F2F;
        }

        .instructions-list li, .warning-list li {
            margin-bottom: 8px;
        }

        .manual-payment-footer {
            padding: 20px 24px;
            border-top: 1px solid #E5E5E5;
            display: flex;
            justify-content: center;
            gap: 12px;
        }

        .deadline-timer {
            font-weight: 700;
            font-size: 18px;
            color: #F57C00;
            text-align: center;
            padding: 12px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            border: 2px solid #FFB74D;
        }

        @keyframes modalFadeOut {
            to {
                opacity: 0;
                transform: scale(0.8);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-content">
            <button class="back-btn" onclick="history.back()">
                <i class="fas fa-arrow-left"></i>
            </button>
            <h1 class="header-title">Detail Pesanan</h1>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div id="loadingState" class="loading">
            <div class="spinner"></div>
        </div>

        <div id="errorState" class="error-state" style="display: none;">
            <i class="fas fa-exclamation-triangle"></i>
            <h3>Pesanan Tidak Ditemukan</h3>
            <p>Pesanan yang Anda cari tidak ditemukan atau Anda tidak memiliki akses ke pesanan ini.</p>
            <button class="btn btn-primary" onclick="window.location.href='/orders'">
                <i class="fas fa-arrow-left"></i>
                Kembali ke Daftar Pesanan
            </button>
        </div>

            <div id="orderDetail" style="display: none;">
                <!-- Order Header -->
                <div class="detail-card">
                    <div class="card-header">
                        <div class="order-number" id="orderNumber">#-</div>
                        <div class="order-status" id="orderStatus">-</div>
                    </div>

                    <div class="order-info">
                        <div class="info-item">
                            <div class="info-label">Tanggal Pesanan</div>
                            <div class="info-value" id="orderDate">-</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Metode Pembayaran</div>
                            <div class="info-value" id="paymentMethod">-</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Status Pembayaran</div>
                            <div class="info-value" id="paymentStatus">-</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Metode Pengiriman</div>
                            <div class="info-value" id="shippingMethod">-</div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons" id="actionButtons">
                        <!-- Buttons will be dynamically added based on order status -->
                    </div>
                </div>

                <!-- Manual Payment Info (shown only for manual payment orders) -->
                <div class="detail-card" id="manualPaymentSection" style="display: none;">
                    <div class="section-header">
                        <i class="fas fa-university section-icon"></i>
                        Informasi Pembayaran Manual
                    </div>

                    <!-- Payment Deadline -->
                    <div id="paymentDeadlineInfo" style="background: #FFF3E0; padding: 16px; border-radius: 8px; margin-bottom: 16px; border-left: 4px solid #FF9800;">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                            <i class="fas fa-clock" style="color: #F57C00;"></i>
                            <span style="font-weight: 600; color: #F57C00;">Batas Waktu Pembayaran</span>
                        </div>
                        <div id="paymentDeadlineText" style="color: #F57C00; font-size: 14px; margin-bottom: 8px;"></div>
                        <div id="paymentCountdownDetail" style="font-weight: 700; font-size: 16px; color: #F57C00;"></div>
                    </div>

                    <!-- Bank Account Info -->
                    <div id="bankAccountInfo" style="background: white; border: 1px solid #E5E5E5; border-radius: 8px; padding: 16px; margin-bottom: 16px;">
                        <h4 style="margin: 0 0 12px 0; color: #1565C0; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-credit-card"></i>
                            Rekening Tujuan Transfer
                        </h4>
                        <div id="bankAccountDetails" style="background: #F8F9FA; padding: 12px; border-radius: 6px;">
                            <!-- Bank account details will be loaded here -->
                        </div>

                        <!-- Show Payment Instructions Button -->
                        <div style="margin-top: 16px; text-align: center;">
                            <button class="btn btn-primary" onclick="showManualPaymentInstructions()" style="background: linear-gradient(135deg, #1565C0 0%, #0D47A1 100%);">
                                <i class="fas fa-info-circle"></i>
                                Lihat Instruksi Pembayaran Lengkap
                            </button>
                        </div>
                    </div>

                    <!-- Payment Proof Upload -->
                    <div id="paymentProofSection">
                        <h4 style="margin: 0 0 12px 0; color: #1565C0; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-upload"></i>
                            Upload Bukti Pembayaran
                        </h4>

                        <!-- Upload Form -->
                        <div id="uploadForm" style="background: #F8F9FA; padding: 16px; border-radius: 8px; border: 2px dashed #DDD;">
                            <div style="text-align: center; margin-bottom: 16px;">
                                <i class="fas fa-cloud-upload-alt" style="font-size: 32px; color: #1565C0; margin-bottom: 8px;"></i>
                                <p style="margin: 0; color: #666; font-size: 14px;">
                                    Pilih atau drag & drop file bukti transfer<br>
                                    <small>Format yang didukung: JPG, PNG, PDF (max 5MB)</small>
                                </p>
                            </div>
                            <input type="file" id="paymentProofFile" accept="image/*,.pdf" style="display: none;" onchange="handleFileSelect(event)">
                            <div style="text-align: center;">
                                <button class="btn btn-primary" onclick="document.getElementById('paymentProofFile').click()">
                                    <i class="fas fa-folder-open"></i>
                                    Pilih File
                                </button>
                            </div>

                            <!-- Selected file preview -->
                            <div id="filePreview" style="display: none; margin-top: 16px; padding: 12px; background: white; border-radius: 6px; border: 1px solid #E5E5E5;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <i class="fas fa-file" style="color: #1565C0; font-size: 20px;"></i>
                                    <div style="flex: 1;">
                                        <div id="fileName" style="font-weight: 600; color: #333;"></div>
                                        <div id="fileSize" style="font-size: 12px; color: #666;"></div>
                                    </div>
                                    <button class="btn btn-success" onclick="uploadPaymentProof()" id="uploadBtn">
                                        <i class="fas fa-upload"></i>
                                        Upload
                                    </button>
                                    <button onclick="clearFileSelection()" style="background: none; border: none; color: #D32F2F; cursor: pointer;">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Uploaded proof display -->
                        <div id="uploadedProof" style="display: none; background: #E8F5E8; padding: 16px; border-radius: 8px; border-left: 4px solid #4CAF50; margin-top: 16px;">
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                                <i class="fas fa-check-circle" style="color: #4CAF50;"></i>
                                <span style="font-weight: 600; color: #2E7D32;">Bukti Pembayaran Telah Diupload</span>
                            </div>
                            <p style="margin: 0; color: #388E3C; font-size: 14px;">
                                File: <span id="uploadedFileName"></span><br>
                                Diupload pada: <span id="uploadedDate"></span><br>
                                Status: Menunggu konfirmasi dari penjual
                            </p>
                            <div style="margin-top: 12px;">
                                <button class="btn btn-secondary" onclick="viewPaymentProof()" style="font-size: 12px; padding: 8px 12px;">
                                    <i class="fas fa-eye"></i>
                                    Lihat Bukti
                                </button>
                                <button class="btn btn-primary" onclick="showUploadForm()" style="font-size: 12px; padding: 8px 12px; margin-left: 8px;">
                                    <i class="fas fa-edit"></i>
                                    Ganti Bukti
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Address Info -->
                <div class="detail-card">
                    <div class="section-header">
                        <i class="fas fa-map-marker-alt section-icon"></i>
                        Alamat Pengiriman
                    </div>
                    <div class="address-info" id="addressInfo">
                        <!-- Address details will be loaded here -->
                    </div>
                </div>

                <!-- Order Items -->
                <div class="detail-card">
                    <div class="section-header">
                        <i class="fas fa-shopping-bag section-icon"></i>
                        Item Pesanan
                    </div>
                    <div class="order-items" id="orderItems">
                        <!-- Order items will be loaded here -->
                    </div>
                </div>

                <!-- Price Summary -->
                <div class="detail-card">
                    <div class="section-header">
                        <i class="fas fa-receipt section-icon"></i>
                        Ringkasan Pembayaran
                    </div>
                    <div class="price-summary">
                        <div class="price-row">
                            <span>Subtotal</span>
                            <span id="subtotal">Rp 0</span>
                        </div>
                        <div class="price-row">
                            <span>Ongkos Kirim</span>
                            <span id="shippingCost">Rp 0</span>
                        </div>
                        <div class="price-row">
                            <span>Pajak</span>
                            <span id="tax">Rp 0</span>
                        </div>
                        <div class="price-row total">
                            <span>Total</span>
                            <span id="totalPrice">Rp 0</span>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="detail-card" id="notesSection" style="display: none;">
                    <div class="section-header">
                        <i class="fas fa-sticky-note section-icon"></i>
                        Catatan
                    </div>
                    <p id="orderNotes" class="info-value"></p>
                </div>
            </div>
        </div>

    <script>
        let order = null;
        const orderId = {{ $orderId }};

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            fetchOrderDetail();
        });

        // Fetch order detail
        async function fetchOrderDetail() {
            try {
                // Get auth token from auth.js
                const token = getAuthToken ? getAuthToken() : null;

                const headers = {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                };

                // Add Authorization header if token exists
                if (token) {
                    headers['Authorization'] = `Bearer ${token}`;
                }

                const response = await fetch(`/api/orders/${orderId}`, {
                    method: 'GET',
                    headers: headers
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.success) {
                        order = data.data;
                        displayOrderDetail();
                    } else {
                        showError();
                    }
                } else {
                    throw new Error('Gagal memuat detail pesanan');
                }
            } catch (error) {
                console.error('Error fetching order detail:', error);
                showError();
            } finally {
                hideLoading();
            }
        }

        // Display order detail
        function displayOrderDetail() {
            if (!order) return;

            // Order header
            document.getElementById('orderNumber').textContent = `#${order.nomor_pesanan || '-'}`;

            const statusElement = document.getElementById('orderStatus');
            statusElement.textContent = order.status_label || order.status || '-';
            statusElement.className = `order-status ${order.status || ''}`;

            // Order info
            document.getElementById('orderDate').textContent = order.tanggal_pesan_formatted || '-';
            document.getElementById('paymentMethod').textContent = order.metode_pembayaran_label || order.metode_pembayaran || '-';
            document.getElementById('paymentStatus').textContent = order.status_pembayaran_label || order.status_pembayaran || '-';
            document.getElementById('shippingMethod').textContent = order.metode_pengiriman || '-';

            // Manual Payment Section
            if (order.metode_pembayaran === 'manual') {
                handleManualPaymentDisplay();
            }

            // Address info
            if (order.address) {
                const addressHtml = `
                    <div class="address-name">${order.address.nama_penerima || '-'}</div>
                    <div class="address-details">${order.address.telepon || '-'}</div>
                    <div class="address-details">
                        ${order.address.alamat_lengkap || '-'}<br>
                        ${order.address.kecamatan || ''}, ${order.address.kota || ''}<br>
                        ${order.address.provinsi || ''} ${order.address.kode_pos || ''}
                    </div>
                `;
                document.getElementById('addressInfo').innerHTML = addressHtml;
            }

            // Order items
            fetchOrderItems();

            // Price summary
            document.getElementById('subtotal').textContent = order.subtotal_formatted || 'Rp 0';
            document.getElementById('shippingCost').textContent = order.biaya_kirim_formatted || 'Rp 0';
            document.getElementById('tax').textContent = order.pajak_formatted || 'Rp 0';
            document.getElementById('totalPrice').textContent = order.total_formatted || 'Rp 0';

            // Notes
            if (order.catatan) {
                document.getElementById('orderNotes').textContent = order.catatan;
                document.getElementById('notesSection').style.display = 'block';
            }

            // Action buttons
            renderActionButtons();

            // Show order detail
            document.getElementById('orderDetail').style.display = 'block';
        }

        // Fetch order items
        async function fetchOrderItems() {
            try {
                const token = getAuthToken ? getAuthToken() : null;

                const headers = {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                };

                if (token) {
                    headers['Authorization'] = `Bearer ${token}`;
                }

                const response = await fetch(`/api/orders/${orderId}/items`, {
                    method: 'GET',
                    headers: headers
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.success) {
                        renderOrderItems(data.data);
                    }
                }
            } catch (error) {
                console.error('Error fetching order items:', error);
            }
        }

        // Render order items
        function renderOrderItems(items) {
            const container = document.getElementById('orderItems');

            if (!items || items.length === 0) {
                container.innerHTML = '<p class="text-center text-muted">Tidak ada item dalam pesanan ini.</p>';
                return;
            }

            const itemsHtml = items.map(item => {
                // Get image source
                let imageSrc = 'https://via.placeholder.com/80x80/BBDEFB/1976D2?text=No+Image';
                if (item.produk && item.produk.gambar) {
                    if (Array.isArray(item.produk.gambar) && item.produk.gambar.length > 0) {
                        imageSrc = `/storage/${item.produk.gambar[0]}`;
                    } else if (typeof item.produk.gambar === 'string') {
                        imageSrc = `/storage/${item.produk.gambar}`;
                    }
                }

                return `
                    <div class="order-item">
                        <img src="${imageSrc}"
                             alt="${item.nama_produk || 'Product'}" class="item-image"
                             onerror="this.src='https://via.placeholder.com/80x80/BBDEFB/1976D2?text=No+Image'">
                        <div class="item-info">
                            <div class="item-name">${item.nama_produk || 'Unknown Product'}</div>
                            <div class="item-details">
                                Jumlah: ${item.jumlah || 0} pcs<br>
                                Harga: ${item.harga_formatted || 'Rp 0'}/pcs
                            </div>
                            <div class="item-price">Subtotal: ${item.subtotal_formatted || 'Rp 0'}</div>
                        </div>
                    </div>
                `;
            }).join('');

            container.innerHTML = itemsHtml;
        }

        // Render action buttons based on order status
        function renderActionButtons() {
            const container = document.getElementById('actionButtons');
            let buttonsHtml = '';

            if (!order) return;

            // Cancel button for orders that can be cancelled
            if (order.can_cancel) {
                buttonsHtml += `
                    <button class="btn btn-danger" onclick="cancelOrder()">
                        <i class="fas fa-times"></i>
                        Batalkan Pesanan
                    </button>
                `;
            }

            // Complete button for orders that can be completed
            if (order.can_complete) {
                buttonsHtml += `
                    <button class="btn btn-success" onclick="completeOrder()">
                        <i class="fas fa-check"></i>
                        Pesanan Diterima
                    </button>
                `;
            }

            // Always show back to orders button
            buttonsHtml += `
                <button class="btn btn-secondary" onclick="window.location.href='/orders'">
                    <i class="fas fa-list"></i>
                    Kembali ke Daftar Pesanan
                </button>
            `;

            container.innerHTML = buttonsHtml;
        }

        // Cancel order
        async function cancelOrder() {
            if (!confirm(`Apakah Anda yakin ingin membatalkan pesanan #${order.nomor_pesanan}?`)) {
                return;
            }

            try {
                const token = getAuthToken ? getAuthToken() : null;

                const headers = {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };

                if (token) {
                    headers['Authorization'] = `Bearer ${token}`;
                }

                const response = await fetch(`/api/orders/${orderId}/cancel`, {
                    method: 'POST',
                    headers: headers
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    alert('Pesanan berhasil dibatalkan');
                    // Reload page to show updated status
                    window.location.reload();
                } else {
                    alert(`Gagal membatalkan pesanan: ${data.message || 'Terjadi kesalahan'}`);
                }
            } catch (error) {
                console.error('Cancel order error:', error);
                alert('Terjadi kesalahan saat membatalkan pesanan');
            }
        }

        // Complete order (mark as received)
        async function completeOrder() {
            if (!confirm(`Konfirmasi bahwa Anda telah menerima pesanan #${order.nomor_pesanan}?`)) {
                return;
            }

            try {
                const token = getAuthToken ? getAuthToken() : null;

                const headers = {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };

                if (token) {
                    headers['Authorization'] = `Bearer ${token}`;
                }

                const response = await fetch(`/api/orders/${orderId}/complete`, {
                    method: 'POST',
                    headers: headers
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    alert('Pesanan berhasil diselesaikan. Terima kasih!');
                    // Reload page to show updated status
                    window.location.reload();
                } else {
                    alert(`Gagal menyelesaikan pesanan: ${data.message || 'Terjadi kesalahan'}`);
                }
            } catch (error) {
                console.error('Complete order error:', error);
                alert('Terjadi kesalahan saat menyelesaikan pesanan');
            }
        }

        // Handle manual payment display
        async function handleManualPaymentDisplay() {
            try {
                // Fetch bank account info for this order
                const token = getAuthToken ? getAuthToken() : null;
                const headers = {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                };

                if (token) {
                    headers['Authorization'] = `Bearer ${token}`;
                }

                const response = await fetch(`/api/orders/${orderId}/bank-account`, {
                    method: 'GET',
                    headers: headers
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.success && data.data.bank_account) {
                        displayManualPaymentInfo(data.data);
                    }
                }
            } catch (error) {
                console.error('Error fetching bank account info:', error);
            }
        }

        // Display manual payment information
        function displayManualPaymentInfo(data) {
            const bankAccount = data.bank_account;
            const manualPaymentSection = document.getElementById('manualPaymentSection');

            // Store bank account info in order object for later use
            if (order) {
                order.bank_account = bankAccount;
            }

            // Show manual payment section
            manualPaymentSection.style.display = 'block';

            // Payment deadline
            if (order.payment_deadline) {
                const deadline = new Date(order.payment_deadline);
                const deadlineFormatted = deadline.toLocaleString('id-ID', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });

                document.getElementById('paymentDeadlineText').innerHTML = `<strong>${deadlineFormatted}</strong><br><span style="font-size: 12px;">Pesanan akan otomatis dibatalkan jika tidak dibayar sebelum batas waktu</span>`;

                // Start countdown
                startPaymentCountdownDetail(deadline);
            }

            // Bank account details
            const bankAccountHtml = `
                <div style="margin-bottom: 8px;">
                    <span style="font-size: 12px; color: #666; text-transform: uppercase;">Bank</span><br>
                    <strong style="font-size: 16px;">${bankAccount.bank_name}</strong>
                </div>
                <div style="margin-bottom: 8px;">
                    <span style="font-size: 12px; color: #666; text-transform: uppercase;">Nomor Rekening</span><br>
                    <strong style="font-size: 18px; color: #1565C0; letter-spacing: 1px;">${bankAccount.account_number}</strong>
                    <button onclick="copyAccountNumber('${bankAccount.account_number}')" style="margin-left: 8px; background: none; border: 1px solid #1565C0; color: #1565C0; padding: 4px 8px; border-radius: 4px; cursor: pointer; font-size: 12px;">
                        <i class="fas fa-copy"></i> Salin
                    </button>
                </div>
                <div>
                    <span style="font-size: 12px; color: #666; text-transform: uppercase;">Atas Nama</span><br>
                    <strong style="font-size: 16px;">${bankAccount.account_holder_name}</strong>
                </div>
            `;
            document.getElementById('bankAccountDetails').innerHTML = bankAccountHtml;

            // Check if payment proof already uploaded
            if (order.payment_proof) {
                showUploadedProof();
            } else if (order.status_pembayaran === 'menunggu' || order.status_pembayaran === 'menunggu_verifikasi') {
                // Show upload form for pending payments, even if deadline passed
                showUploadForm();
            } else {
                // Order completed or cancelled
                hideUploadForm();
            }
        }

        // Start payment countdown for detail page
        function startPaymentCountdownDetail(deadline) {
            const countdownElement = document.getElementById('paymentCountdownDetail');
            if (!countdownElement) return;

            function updateCountdown() {
                const now = new Date().getTime();
                const distance = deadline.getTime() - now;

                if (distance < 0) {
                    countdownElement.innerHTML = '<span style="color: #D32F2F;">⏰ Waktu pembayaran telah habis</span>';
                    hideUploadForm();
                    return;
                }

                const hours = Math.floor(distance / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                countdownElement.innerHTML = `⏰ Sisa waktu: ${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            }

            updateCountdown();
            setInterval(updateCountdown, 1000);
        }

        // Copy account number to clipboard
        function copyAccountNumber(accountNumber) {
            navigator.clipboard.writeText(accountNumber).then(() => {
                alert('✅ Nomor rekening berhasil disalin!');
            }).catch(err => {
                console.error('Failed to copy: ', err);
                alert('❌ Gagal menyalin nomor rekening');
            });
        }

        // Handle file selection
        function handleFileSelect(event) {
            const file = event.target.files[0];
            if (!file) return;

            // Validate file type
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
            if (!allowedTypes.includes(file.type)) {
                alert('❌ Format file tidak didukung. Gunakan JPG, PNG, atau PDF.');
                return;
            }

            // Validate file size (5MB max)
            const maxSize = 5 * 1024 * 1024; // 5MB
            if (file.size > maxSize) {
                alert('❌ Ukuran file terlalu besar. Maksimal 5MB.');
                return;
            }

            // Show file preview
            document.getElementById('fileName').textContent = file.name;
            document.getElementById('fileSize').textContent = formatFileSize(file.size);
            document.getElementById('filePreview').style.display = 'block';
        }

        // Format file size
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Clear file selection
        function clearFileSelection() {
            document.getElementById('paymentProofFile').value = '';
            document.getElementById('filePreview').style.display = 'none';
        }

        // Upload payment proof
        async function uploadPaymentProof() {
            const fileInput = document.getElementById('paymentProofFile');
            const file = fileInput.files[0];

            if (!file) {
                alert('❌ Pilih file terlebih dahulu!');
                return;
            }

            const uploadBtn = document.getElementById('uploadBtn');
            uploadBtn.disabled = true;
            uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';

            try {
                const formData = new FormData();
                formData.append('payment_proof', file);

                const token = getAuthToken ? getAuthToken() : null;
                const headers = {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };

                if (token) {
                    headers['Authorization'] = `Bearer ${token}`;
                }

                const response = await fetch(`/api/orders/${orderId}/payment-proof`, {
                    method: 'POST',
                    headers: headers,
                    body: formData
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    alert('✅ Bukti pembayaran berhasil diupload!');

                    // Update order data with new payment proof info
                    order.payment_proof = data.data.payment_proof;
                    order.payment_proof_uploaded_at = data.data.payment_proof_uploaded_at;

                    // Show uploaded proof
                    showUploadedProof();
                } else {
                    throw new Error(data.message || 'Gagal mengupload bukti pembayaran');
                }
            } catch (error) {
                console.error('Upload error:', error);
                alert(`❌ ${error.message}`);
            } finally {
                uploadBtn.disabled = false;
                uploadBtn.innerHTML = '<i class="fas fa-upload"></i> Upload';
            }
        }

        // Show uploaded proof information
        function showUploadedProof() {
            document.getElementById('uploadForm').style.display = 'none';
            document.getElementById('filePreview').style.display = 'none';

            const uploadedProof = document.getElementById('uploadedProof');

            // Get filename from path
            let fileName = 'Bukti Pembayaran';
            if (order.payment_proof) {
                const parts = order.payment_proof.split('/');
                fileName = parts[parts.length - 1];
            }

            // Format upload date
            let uploadDate = 'Tidak diketahui';
            if (order.payment_proof_uploaded_at) {
                const date = new Date(order.payment_proof_uploaded_at);
                uploadDate = date.toLocaleString('id-ID');
            }

            document.getElementById('uploadedFileName').textContent = fileName;
            document.getElementById('uploadedDate').textContent = uploadDate;
            uploadedProof.style.display = 'block';
        }

        // Show upload form
        function showUploadForm() {
            document.getElementById('uploadForm').style.display = 'block';
            document.getElementById('uploadedProof').style.display = 'none';
            clearFileSelection();
        }

        // Hide upload form
        function hideUploadForm() {
            document.getElementById('paymentProofSection').style.display = 'none';
        }

        // View payment proof
        function viewPaymentProof() {
            if (order.payment_proof) {
                window.open(`/storage/${order.payment_proof}`, '_blank');
            }
        }

        // Show error state
        function showError() {
            document.getElementById('errorState').style.display = 'block';
        }

        // Hide loading
        function hideLoading() {
            document.getElementById('loadingState').style.display = 'none';
        }

        // Show manual payment instructions popup
        function showManualPaymentInstructions() {
            if (!order || !order.bank_account) {
                alert('❌ Data pembayaran tidak ditemukan!');
                return;
            }

            const bankAccount = order.bank_account;

            // Parse payment deadline
            let paymentDeadline;
            if (order.payment_deadline) {
                paymentDeadline = new Date(order.payment_deadline.replace(' ', 'T'));
            } else {
                paymentDeadline = new Date();
                paymentDeadline.setHours(paymentDeadline.getHours() + 2);
            }

            // Format deadline
            const deadlineFormatted = paymentDeadline.toLocaleString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });

            const modalHtml = `
                <div id="manualPaymentInstructionsModal" class="manual-payment-modal show">
                    <div class="manual-payment-content">
                        <div class="manual-payment-header">
                            <h3>
                                <i class="fas fa-university"></i>
                                Instruksi Pembayaran Manual
                            </h3>
                            <button class="close-btn" onclick="closeInstructionsModal()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <div class="manual-payment-body">
                            <!-- Payment Status -->
                            <div class="payment-status-card">
                                <div class="payment-status-icon">
                                    <i class="fas fa-info-circle"></i>
                                    Informasi Pesanan
                                </div>
                                <div style="color: #388E3C; font-size: 14px; line-height: 1.5;">
                                    Nomor Pesanan: <strong>#${order.nomor_pesanan}</strong><br>
                                    Total Pembayaran: <strong>${order.total_formatted}</strong>
                                </div>
                            </div>

                            <!-- Payment Deadline -->
                            <div class="payment-deadline-card">
                                <div class="payment-status-icon" style="color: #F57C00;">
                                    <i class="fas fa-clock"></i>
                                    Batas Waktu Pembayaran
                                </div>
                                <div style="color: #F57C00; font-size: 14px; line-height: 1.5; margin-bottom: 8px;">
                                    <strong>${deadlineFormatted}</strong><br>
                                    <small>Pesanan akan otomatis dibatalkan jika tidak dibayar sebelum batas waktu</small>
                                </div>
                                <div id="paymentCountdownModal" class="deadline-timer"></div>
                            </div>

                            <!-- Bank Account Info -->
                            <div class="bank-account-card">
                                <h4>
                                    <i class="fas fa-credit-card"></i>
                                    Informasi Rekening Tujuan
                                </h4>
                                <div class="bank-info-grid">
                                    <div class="bank-info-item">
                                        <div class="bank-info-label">Bank</div>
                                        <div class="bank-info-value">${bankAccount.bank_name}</div>
                                    </div>
                                    <div class="bank-info-item">
                                        <div class="bank-info-label">Nomor Rekening</div>
                                        <div class="bank-info-value">
                                            <span class="account-number">${bankAccount.account_number}</span>
                                            <button class="copy-btn" onclick="copyAccountNumber('${bankAccount.account_number}')">
                                                <i class="fas fa-copy"></i>
                                                Salin
                                            </button>
                                        </div>
                                    </div>
                                    <div class="bank-info-item">
                                        <div class="bank-info-label">Atas Nama</div>
                                        <div class="bank-info-value">${bankAccount.account_holder_name}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Instructions -->
                            <div class="instructions-card">
                                <h4 class="instructions-title">
                                    <i class="fas fa-list-ol"></i>
                                    Langkah-langkah Pembayaran
                                </h4>
                                <ol class="instructions-list">
                                    <li>Transfer ke rekening di atas dengan nominal <strong>PERSIS ${order.total_formatted}</strong></li>
                                    <li>Simpan bukti transfer Anda</li>
                                    <li>Upload bukti pembayaran di halaman ini</li>
                                    <li>Tunggu konfirmasi dari penjual (maksimal 1x24 jam)</li>
                                </ol>
                            </div>

                            <!-- Warning -->
                            <div class="warning-card">
                                <div class="warning-title">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    Penting!
                                </div>
                                <ul class="warning-list">
                                    <li>Transfer harus dilakukan dengan nominal yang PERSIS sama</li>
                                    <li>Upload bukti pembayaran yang jelas dan mudah dibaca</li>
                                    <li>Pesanan akan dibatalkan otomatis jika melewati batas waktu</li>
                                </ul>
                            </div>
                        </div>

                        <div class="manual-payment-footer">
                            <button class="btn btn-secondary" onclick="closeInstructionsModal()">
                                <i class="fas fa-times"></i>
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            `;

            // Add modal to body
            document.body.insertAdjacentHTML('beforeend', modalHtml);

            // Start countdown for modal
            startPaymentCountdownModal(paymentDeadline);

            // Add event listeners for closing modal
            const modal = document.getElementById('manualPaymentInstructionsModal');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        closeInstructionsModal();
                    }
                });

                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        closeInstructionsModal();
                    }
                });
            }
        }

        // Close instructions modal
        function closeInstructionsModal() {
            const modal = document.getElementById('manualPaymentInstructionsModal');
            if (modal) {
                // Clear countdown interval
                if (window.paymentCountdownModalInterval) {
                    clearInterval(window.paymentCountdownModalInterval);
                    window.paymentCountdownModalInterval = null;
                }

                modal.classList.remove('show');
                modal.style.animation = 'modalFadeOut 0.3s ease-out forwards';

                setTimeout(() => {
                    modal.remove();
                }, 300);
            }
        }

        // Start payment countdown for modal
        function startPaymentCountdownModal(deadline) {
            const countdownElement = document.getElementById('paymentCountdownModal');
            if (!countdownElement) return;

            function updateCountdown() {
                const now = new Date().getTime();
                const deadlineTime = deadline.getTime();
                const distance = deadlineTime - now;

                if (distance < 0) {
                    countdownElement.innerHTML = '<span style="color: #D32F2F;">⏰ Waktu pembayaran telah habis</span>';
                    return;
                }

                const hours = Math.floor(distance / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                countdownElement.innerHTML = `⏰ Sisa waktu: ${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            }

            updateCountdown();
            const countdownInterval = setInterval(updateCountdown, 1000);

            if (window.paymentCountdownModalInterval) {
                clearInterval(window.paymentCountdownModalInterval);
            }
            window.paymentCountdownModalInterval = countdownInterval;
        }

        // Copy account number
        function copyAccountNumber(accountNumber) {
            navigator.clipboard.writeText(accountNumber).then(() => {
                // Show success message
                const successMsg = document.createElement('div');
                successMsg.innerHTML = `
                    <div style="
                        position: fixed;
                        top: 20px;
                        right: 20px;
                        background: linear-gradient(135deg, #4CAF50 0%, #66BB6A 100%);
                        color: white;
                        padding: 16px 20px;
                        border-radius: 12px;
                        box-shadow: 0 6px 20px rgba(76, 175, 80, 0.3);
                        z-index: 9999;
                        display: flex;
                        align-items: center;
                        gap: 12px;
                        animation: slideInRight 0.3s ease-out;
                    ">
                        <i class="fas fa-check-circle" style="font-size: 20px;"></i>
                        <span style="font-weight: 600;">Nomor rekening berhasil disalin!</span>
                    </div>
                `;

                document.body.appendChild(successMsg);

                setTimeout(() => {
                    successMsg.remove();
                }, 3000);
            }).catch(err => {
                console.error('Failed to copy: ', err);
                alert('❌ Gagal menyalin nomor rekening');
            });
        }
    </script>
</body>
</html>
