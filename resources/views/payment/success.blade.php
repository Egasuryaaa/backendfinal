<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pembayaran Berhasil - IwakMart</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
        }

        .container {
            background: white;
            border-radius: 20px;
            padding: 40px;
            max-width: 500px;
            width: 90%;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .success-icon {
            background: linear-gradient(135deg, #4CAF50, #2E7D32);
            color: white;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            font-size: 36px;
            animation: bounce 1s ease-in-out;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }

        .title {
            font-size: 24px;
            font-weight: 700;
            color: #2E7D32;
            margin-bottom: 12px;
        }

        .message {
            font-size: 16px;
            color: #666;
            margin-bottom: 32px;
            line-height: 1.6;
        }

        .order-info {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 32px;
        }

        .order-info h3 {
            font-size: 18px;
            margin-bottom: 12px;
            color: #333;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .info-label {
            color: #666;
        }

        .info-value {
            font-weight: 600;
        }

        .actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 14px 28px;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            font-size: 14px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #1976D2, #0D47A1);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(25, 118, 210, 0.4);
        }

        .btn-secondary {
            background: #f5f5f5;
            color: #666;
        }

        .btn-secondary:hover {
            background: #e0e0e0;
        }

        .loading {
            display: none;
            text-align: center;
            margin: 20px 0;
        }

        .spinner {
            width: 30px;
            height: 30px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #1976D2;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 12px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-icon">
            <i class="fas fa-check"></i>
        </div>

        <h1 class="title">Pembayaran Berhasil!</h1>
        <p class="message">
            Terima kasih! Pembayaran Anda telah berhasil diproses.
            Pesanan Anda akan segera diproses oleh penjual.
        </p>

        <div class="loading" id="loadingInfo">
            <div class="spinner"></div>
            <p>Memuat informasi pesanan...</p>
        </div>

        <div class="order-info" id="orderInfo" style="display: none;">
            <h3>Detail Pesanan</h3>
            <div class="info-item">
                <span class="info-label">Nomor Pesanan:</span>
                <span class="info-value" id="orderNumber">-</span>
            </div>
            <div class="info-item">
                <span class="info-label">Status:</span>
                <span class="info-value">Pembayaran Berhasil</span>
            </div>
            <div class="info-item">
                <span class="info-label">Waktu:</span>
                <span class="info-value" id="paymentTime">-</span>
            </div>
        </div>

        <div class="actions">
            <a href="/orders" class="btn btn-primary">
                <i class="fas fa-list"></i>
                Lihat Pesanan
            </a>
            <a href="/fishmarket" class="btn btn-secondary">
                <i class="fas fa-home"></i>
                Kembali Berbelanja
            </a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get order info from URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            const orderId = urlParams.get('order_id');
            const paymentId = urlParams.get('payment_id');

            if (orderId) {
                loadOrderInfo(orderId);
            } else {
                // Check if there's saved checkout info
                const checkoutInfo = sessionStorage.getItem('checkout_success');
                if (checkoutInfo) {
                    const info = JSON.parse(checkoutInfo);
                    loadOrderInfo(info.order_id);
                    sessionStorage.removeItem('checkout_success');
                } else {
                    showDefaultInfo();
                }
            }
        });

        async function loadOrderInfo(orderId) {
            document.getElementById('loadingInfo').style.display = 'block';

            try {
                // You can add API call here to get order details
                // For now, show basic info
                setTimeout(() => {
                    document.getElementById('loadingInfo').style.display = 'none';
                    document.getElementById('orderInfo').style.display = 'block';
                    document.getElementById('orderNumber').textContent = `#ORD${orderId}`;
                    document.getElementById('paymentTime').textContent = new Date().toLocaleString('id-ID');
                }, 1000);
            } catch (error) {
                console.error('Failed to load order info:', error);
                showDefaultInfo();
            }
        }

        function showDefaultInfo() {
            document.getElementById('loadingInfo').style.display = 'none';
            document.getElementById('orderInfo').style.display = 'block';
            document.getElementById('orderNumber').textContent = 'Berhasil';
            document.getElementById('paymentTime').textContent = new Date().toLocaleString('id-ID');
        }
    </script>
</body>
</html>
