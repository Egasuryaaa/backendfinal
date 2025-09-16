<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pembayaran Gagal - IwakMart</title>

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
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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

        .error-icon {
            background: linear-gradient(135deg, #f44336, #d32f2f);
            color: white;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            font-size: 36px;
            animation: shake 0.8s ease-in-out;
        }

        @keyframes shake {
            0%, 100% {
                transform: translateX(0);
            }
            25% {
                transform: translateX(-5px);
            }
            75% {
                transform: translateX(5px);
            }
        }

        .title {
            font-size: 24px;
            font-weight: 700;
            color: #d32f2f;
            margin-bottom: 12px;
        }

        .message {
            font-size: 16px;
            color: #666;
            margin-bottom: 32px;
            line-height: 1.6;
        }

        .error-info {
            background: #ffebee;
            border: 1px solid #ffcdd2;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 32px;
        }

        .error-info h3 {
            font-size: 18px;
            margin-bottom: 12px;
            color: #d32f2f;
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

        .btn-danger {
            background: linear-gradient(135deg, #f44336, #d32f2f);
            color: white;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(244, 67, 54, 0.4);
        }

        .btn-secondary {
            background: #f5f5f5;
            color: #666;
        }

        .btn-secondary:hover {
            background: #e0e0e0;
        }

        .help-section {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            text-align: left;
        }

        .help-section h4 {
            margin-bottom: 12px;
            color: #333;
        }

        .help-section ul {
            margin-left: 20px;
            color: #666;
        }

        .help-section li {
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-icon">
            <i class="fas fa-times"></i>
        </div>

        <h1 class="title">Pembayaran Gagal</h1>
        <p class="message">
            Maaf, pembayaran Anda tidak dapat diproses.
            Silakan coba lagi atau gunakan metode pembayaran lain.
        </p>

        <div class="error-info" id="errorInfo">
            <h3>Detail Kesalahan</h3>
            <div class="info-item">
                <span class="info-label">Status:</span>
                <span class="info-value">Pembayaran Gagal</span>
            </div>
            <div class="info-item">
                <span class="info-label">Waktu:</span>
                <span class="info-value" id="errorTime">-</span>
            </div>
            <div class="info-item" id="orderNumberItem" style="display: none;">
                <span class="info-label">Nomor Pesanan:</span>
                <span class="info-value" id="orderNumber">-</span>
            </div>
        </div>

        <div class="help-section">
            <h4>Apa yang bisa Anda lakukan?</h4>
            <ul>
                <li>Coba gunakan metode pembayaran lain</li>
                <li>Pastikan saldo atau limit kartu mencukupi</li>
                <li>Periksa koneksi internet Anda</li>
                <li>Hubungi customer service jika masalah berlanjut</li>
            </ul>
        </div>

        <div class="actions">
            <a href="/orders" class="btn btn-primary" id="retryPaymentBtn" style="display: none;">
                <i class="fas fa-redo"></i>
                Coba Bayar Lagi
            </a>
            <a href="/checkout" class="btn btn-danger">
                <i class="fas fa-shopping-cart"></i>
                Kembali ke Checkout
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
            const error = urlParams.get('error');

            // Set error time
            document.getElementById('errorTime').textContent = new Date().toLocaleString('id-ID');

            // Show order number if available
            if (orderId) {
                document.getElementById('orderNumberItem').style.display = 'flex';
                document.getElementById('orderNumber').textContent = `#ORD${orderId}`;
                document.getElementById('retryPaymentBtn').style.display = 'inline-block';
                document.getElementById('retryPaymentBtn').href = `/orders/${orderId}`;
            }

            // Update message based on error type
            if (error) {
                updateErrorMessage(error);
            }
        });

        function updateErrorMessage(errorType) {
            const messageElement = document.querySelector('.message');

            switch (errorType) {
                case 'expired':
                    messageElement.textContent = 'Waktu pembayaran telah habis. Silakan buat pesanan baru atau coba bayar lagi.';
                    break;
                case 'cancelled':
                    messageElement.textContent = 'Pembayaran dibatalkan. Anda dapat mencoba lagi kapan saja.';
                    break;
                case 'insufficient_funds':
                    messageElement.textContent = 'Saldo tidak mencukupi. Silakan coba dengan metode pembayaran lain.';
                    break;
                case 'network_error':
                    messageElement.textContent = 'Terjadi gangguan jaringan. Silakan periksa koneksi internet dan coba lagi.';
                    break;
                default:
                    messageElement.textContent = 'Maaf, pembayaran Anda tidak dapat diproses. Silakan coba lagi atau gunakan metode pembayaran lain.';
            }
        }
    </script>
</body>
</html>
