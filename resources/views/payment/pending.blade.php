<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pembayaran Menunggu - IwakMart</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        .pending-icon {
            font-size: 4rem;
            color: #ffc107;
        }
        .order-summary {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 2rem;
        }
        .payment-info {
            background: #fff4e5;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .spinner {
            animation: spin 2s linear infinite;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Pending Message -->
                <div class="text-center mb-4">
                    <i class="fas fa-clock pending-icon spinner"></i>
                    <h1 class="mt-3 text-warning">Pembayaran Sedang Diproses</h1>
                    <p class="lead text-muted">Pembayaran Anda sedang dalam proses verifikasi. Mohon tunggu beberapa saat.</p>
                </div>

                <!-- Payment Information -->
                <div class="payment-info">
                    <h5 class="mb-3"><i class="fas fa-info-circle"></i> Informasi Pembayaran</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nomor Pesanan:</strong><br>{{ $order->nomor_pesanan }}</p>
                            <p><strong>Status Pesanan:</strong><br>
                                <span class="badge bg-warning">{{ $order->status_text }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Total Pembayaran:</strong><br>
                                <span class="h5 text-warning">{{ $order->formatted_total }}</span>
                            </p>
                            <p><strong>Status Pembayaran:</strong><br>
                                <span class="badge bg-warning">{{ $order->payment_status_text }}</span>
                            </p>
                        </div>
                    </div>

                    @if($payment)
                    <div class="mt-3">
                        <p><strong>ID Pembayaran:</strong> {{ $payment->payment_id }}</p>
                        <p><strong>Metode Pembayaran:</strong> {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</p>
                        <p><strong>Waktu Pembayaran:</strong> {{ $payment->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    @endif
                </div>

                <!-- Processing Info -->
                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle"></i> Informasi Pemrosesan:</h6>
                    <ul class="mb-0">
                        <li>Pembayaran sedang diverifikasi oleh sistem</li>
                        <li>Proses verifikasi biasanya memakan waktu 1-10 menit</li>
                        <li>Halaman ini akan otomatis refresh setiap 30 detik</li>
                        <li>Anda akan menerima notifikasi email saat pembayaran selesai</li>
                    </ul>
                </div>

                <!-- Order Summary -->
                <div class="order-summary">
                    <h5 class="mb-3"><i class="fas fa-shopping-cart"></i> Ringkasan Pesanan</h5>

                    @if($order->orderItems && $order->orderItems->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th class="text-center">Jumlah</th>
                                        <th class="text-end">Harga</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->orderItems as $item)
                                    <tr>
                                        <td>
                                            <strong>{{ $item->product ? $item->product->nama : 'Produk tidak ditemukan' }}</strong>
                                            @if($item->catatan)
                                                <br><small class="text-muted">{{ $item->catatan }}</small>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $item->jumlah }}</td>
                                        <td class="text-end">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                        <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                        <td class="text-end"><strong>{{ $order->formatted_subtotal }}</strong></td>
                                    </tr>
                                    @if($order->biaya_kirim > 0)
                                    <tr>
                                        <td colspan="3" class="text-end">Biaya Pengiriman:</td>
                                        <td class="text-end">{{ $order->formatted_shipping }}</td>
                                    </tr>
                                    @endif
                                    @if($order->pajak > 0)
                                    <tr>
                                        <td colspan="3" class="text-end">Pajak:</td>
                                        <td class="text-end">Rp {{ number_format($order->pajak, 0, ',', '.') }}</td>
                                    </tr>
                                    @endif
                                    <tr class="table-warning">
                                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                        <td class="text-end"><strong>{{ $order->formatted_total }}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">Tidak ada item dalam pesanan ini.</p>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="text-center mt-5">
                    <button onclick="window.location.reload()" class="btn btn-warning me-3">
                        <i class="fas fa-sync-alt"></i> Refresh Status
                    </button>
                    <a href="/orders" class="btn btn-secondary me-3">
                        <i class="fas fa-list"></i> Lihat Pesanan
                    </a>
                    <a href="/fishmarket" class="btn btn-outline-secondary">
                        <i class="fas fa-home"></i> Kembali Berbelanja
                    </a>
                </div>

                <!-- Auto Refresh -->
                <div class="mt-4 text-center">
                    <div class="alert alert-warning">
                        <i class="fas fa-clock"></i>
                        <strong>Auto Refresh:</strong> Halaman ini akan otomatis refresh dalam
                        <span id="countdown">30</span> detik untuk update status terbaru.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Auto Refresh Script -->
    <script>
        let countdown = 30;
        const countdownElement = document.getElementById('countdown');

        const timer = setInterval(() => {
            countdown--;
            countdownElement.textContent = countdown;

            if (countdown <= 0) {
                window.location.reload();
            }
        }, 1000);
    </script>
</body>
</html>
