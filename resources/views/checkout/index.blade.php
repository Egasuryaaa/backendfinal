<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Checkout - IwakMart</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Auth Script -->
    <script src="/js/auth.js"></script>

    <style>
        /* General Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #F0F8FF 0%, #E3F2FD 50%, #BBDEFB 100%);
            color: #333;
            line-height: 1.6;
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding-bottom: 120px; /* Space for floating footer */
        }

        /* Smooth Animations */
        .fade-in {
            opacity: 0;
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .stagger {
            animation-delay: calc(var(--i) * 0.1s);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        @keyframes slideIn {
            from {
                transform: translateX(-100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #1565C0 0%, #0D47A1 50%, #002171 100%);
            color: white;
            padding: 20px;
            box-shadow: 0 8px 32px rgba(21, 101, 192, 0.3);
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .header-content {
            display: flex;
            align-items: center;
            gap: 16px;
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .back-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            padding: 12px;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(10px);
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .back-btn:active {
            transform: translateY(0) scale(0.95);
        }

        .header-icon {
            background: rgba(255, 255, 255, 0.2);
            padding: 12px;
            border-radius: 16px;
            backdrop-filter: blur(10px);
            animation: pulse 3s ease-in-out infinite;
        }

        .header-info h1 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .header-info p {
            font-size: 12px;
            opacity: 0.8;
        }

        /* Content */
        .content {
            padding: 20px;
        }

        /* Loading */
        .loading {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 400px;
            gap: 20px;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid rgba(25, 118, 210, 0.1);
            border-top: 4px solid #1976D2;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        .loading-text {
            color: #666;
            font-size: 16px;
            font-weight: 500;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Modern Card */
        .modern-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            padding: 28px;
            margin-bottom: 24px;
            border: 1px solid rgba(25, 118, 210, 0.08);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .modern-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #1976D2 0%, #42A5F5 50%, #64B5F6 100%);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s ease;
        }

        .modern-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.12);
        }

        .modern-card:hover::before {
            transform: scaleX(1);
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
        }

        .card-icon {
            padding: 14px;
            border-radius: 16px;
            transition: all 0.3s ease;
        }

        .card-icon:hover {
            transform: scale(1.1) rotate(5deg);
        }

        .card-icon i {
            font-size: 22px;
            display: block;
        }

        .card-title {
            font-size: 18px;
            font-weight: 700;
            color: #0D47A1;
            margin: 0;
        }

        /* Order Summary */
        .order-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }
        .order-item:last-child {
            border-bottom: none;
        }
        .order-item:hover {
            background: rgba(25, 118, 210, 0.04);
            margin: 0 -16px;
            padding: 16px;
            border-radius: 12px;
        }
        .order-item-img {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            object-fit: cover;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .order-item:hover .order-item-img {
            transform: scale(1.05);
        }
        .order-item-info {
            flex: 1;
        }
        .order-item-name {
            font-weight: 600;
            margin-bottom: 4px;
            color: #333;
        }
        .order-item-price {
            font-size: 13px;
            color: #666;
        }
        .order-item-total {
            font-weight: bold;
            color: #4CAF50;
            font-size: 15px;
        }

        /* Address & Options */
        .address-list .address-item, .option-list .option-item {
            border: 2px solid rgba(0, 0, 0, 0.08);
            border-radius: 16px;
            margin-bottom: 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: #fafafa;
            position: relative;
            overflow: hidden;
        }

        .address-list .address-item:hover, .option-list .option-item:hover {
            border-color: rgba(25, 118, 210, 0.3);
            background: #f8fafe;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(25, 118, 210, 0.1);
        }

        .address-list .address-item.selected, .option-list .option-item.selected {
            border-color: #1976D2;
            background: linear-gradient(135deg, #E3F2FD 0%, #BBDEFB 100%);
            box-shadow: 0 8px 25px rgba(25, 118, 210, 0.2);
        }

        .address-list .address-item.selected::before, .option-list .option-item.selected::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #1976D2 0%, #42A5F5 100%);
        }

        .address-radio, .option-radio {
            padding: 20px;
            cursor: pointer;
        }

        .address-radio label, .option-radio label {
            display: flex;
            align-items: center;
            gap: 14px;
            cursor: pointer;
            position: relative;
        }

        .address-radio input[type="radio"], .option-radio input[type="radio"] {
            width: 20px;
            height: 20px;
            margin: 0;
            accent-color: #1976D2;
        }

        .address-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 4px;
        }

        .address-main-badge {
            background: linear-gradient(135deg, #4CAF50 0%, #66BB6A 100%);
            color: white;
            font-size: 10px;
            padding: 4px 8px;
            border-radius: 8px;
            margin-left: 8px;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(76, 175, 80, 0.3);
        }

        .option-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 2px;
        }

        .option-subtitle {
            font-size: 13px;
            color: #666;
        }

        .option-price {
            margin-left: auto;
            font-weight: bold;
            color: #4CAF50;
            font-size: 15px;
        }

        /* Payment Groups - Horizontal Layout */
        .payment-method-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .payment-tabs {
            display: flex;
            gap: 12px;
            overflow-x: auto;
            padding: 4px;
            background: rgba(0, 0, 0, 0.02);
            border-radius: 16px;
            margin-bottom: 20px;
        }

        .payment-tab {
            flex: 1;
            min-width: 120px;
            padding: 16px 20px;
            border: 2px solid rgba(0, 0, 0, 0.08);
            border-radius: 12px;
            background: white;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .payment-tab:hover {
            border-color: rgba(25, 118, 210, 0.3);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(25, 118, 210, 0.1);
        }

        .payment-tab.active {
            border-color: #1976D2;
            background: linear-gradient(135deg, #E3F2FD 0%, #BBDEFB 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(25, 118, 210, 0.2);
        }

        .payment-tab.active::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #1976D2 0%, #42A5F5 100%);
        }

        .payment-tab-icon {
            font-size: 28px;
            margin-bottom: 8px;
            display: block;
            transition: transform 0.3s ease;
        }

        .payment-tab:hover .payment-tab-icon {
            transform: scale(1.1);
        }

        .payment-tab-name {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            margin: 0;
        }

        .payment-tab.active .payment-tab-name {
            color: #1976D2;
        }

        .payment-tab-subtitle {
            font-size: 11px;
            color: #666;
            margin-top: 2px;
        }

        /* Payment Options Panel */
        .payment-options-panel {
            min-height: 80px;
            border-radius: 16px;
            padding: 20px;
            background: white;
            border: 2px solid rgba(25, 118, 210, 0.1);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .payment-options-panel.has-content {
            min-height: 200px;
            align-items: flex-start;
            justify-content: flex-start;
        }

        .payment-options-placeholder {
            text-align: center;
            color: #666;
            font-style: italic;
        }

        .payment-options-placeholder i {
            font-size: 32px;
            color: #ccc;
            margin-bottom: 12px;
            display: block;
        }

        .payment-options-content {
            display: none;
        }

        .payment-options-content.active {
            display: block;
            animation: slideInRight 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .payment-options-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .payment-options-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 12px;
        }

        /* COD Special Styling */
        .payment-tab.cod {
            background: linear-gradient(135deg, #E8F5E9 0%, #C8E6C9 100%);
            border-color: rgba(76, 175, 80, 0.3);
        }

        .payment-tab.cod.active {
            background: linear-gradient(135deg, #4CAF50 0%, #66BB6A 100%);
            color: white;
            border-color: #4CAF50;
        }

        .payment-tab.cod.active .payment-tab-name,
        .payment-tab.cod.active .payment-tab-subtitle {
            color: white;
        }

        .payment-tab.cod.active::before {
            background: linear-gradient(90deg, #2E7D32 0%, #4CAF50 100%);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .payment-tabs {
                flex-direction: column;
                gap: 8px;
            }

            .payment-tab {
                flex: none;
                padding: 12px 16px;
            }

            .payment-tab-icon {
                font-size: 24px;
                margin-bottom: 6px;
            }

            .payment-options-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Payment Options with Logo */
        .payment-option-logo {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            object-fit: contain;
            background: white;
            padding: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: bold;
            min-width: 48px;
        }

        .option-item:hover .payment-option-logo {
            transform: scale(1.1);
        }

        .option-item.selected .payment-option-logo {
            box-shadow: 0 4px 16px rgba(25, 118, 210, 0.3);
            border: 2px solid #1976D2;
        }

        /* Bank Specific Logo Styling */
        .logo-bca {
            background: linear-gradient(135deg, #003d82 0%, #0066cc 100%);
            color: white;
        }
        .logo-bri {
            background: linear-gradient(135deg, #003d7a 0%, #0066cc 100%);
            color: white;
        }
        .logo-bni {
            background: linear-gradient(135deg, #e65100 0%, #ff9800 100%);
            color: white;
        }
        .logo-mandiri {
            background: linear-gradient(135deg, #003d7a 0%, #1976d2 100%);
            color: white;
        }
        .logo-bsi {
            background: linear-gradient(135deg, #2e7d32 0%, #4caf50 100%);
            color: white;
        }
        .logo-cimb {
            background: linear-gradient(135deg, #d32f2f 0%, #f44336 100%);
            color: white;
        }
        .logo-permata {
            background: linear-gradient(135deg, #424242 0%, #616161 100%);
            color: white;
        }

        /* E-Wallet Specific Logo Styling */
        .logo-ovo {
            background: linear-gradient(135deg, #4c51bf 0%, #667eea 100%);
            color: white;
        }
        .logo-dana {
            background: linear-gradient(135deg, #118eff 0%, #1e88e5 100%);
            color: white;
        }
        .logo-linkaja {
            background: linear-gradient(135deg, #e53e3e 0%, #fc8181 100%);
            color: white;
        }
        .logo-shopeepay {
            background: linear-gradient(135deg, #ee5a24 0%, #ff6348 100%);
            color: white;
        }

        /* QRIS Styling */
        .logo-qris {
            background: linear-gradient(135deg, #1a365d 0%, #2d3748 100%);
            color: white;
            font-size: 20px;
        }

        /* COD Styling */
        .logo-cod {
            background: linear-gradient(135deg, #4caf50 0%, #66bb6a 100%);
            color: white;
            font-size: 20px;
        }

        /* Manual Payment Styling */
        .logo-manual {
            background: linear-gradient(135deg, #ff9800 0%, #ffb74d 100%);
            color: white;
            font-size: 20px;
        }

        /* Enhanced Payment Item Layout */
        .payment-option-content {
            display: flex;
            align-items: center;
            gap: 16px;
            width: 100%;
        }

        .payment-option-info {
            flex: 1;
            min-width: 0; /* Prevent overflow */
        }

        .payment-option-badge {
            font-size: 11px;
            padding: 4px 8px;
            border-radius: 6px;
            background: #e3f2fd;
            color: #1976d2;
            font-weight: 600;
            margin-top: 4px;
            display: inline-block;
        }

        .payment-option-badge.popular {
            background: #fff3e0;
            color: #f57c00;
        }

        .payment-option-badge.trusted {
            background: #e3f2fd;
            color: #1976d2;
        }

        .payment-option-badge.instant {
            background: #e8f5e9;
            color: #2e7d32;
        }

        /* Notes */
        .notes-field {
            width: 100%;
            padding: 16px;
            border-radius: 16px;
            border: 2px solid rgba(0, 0, 0, 0.08);
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            min-height: 100px;
            resize: vertical;
            transition: all 0.3s ease;
        }

        .notes-field:focus {
            outline: none;
            border-color: #1976D2;
            box-shadow: 0 0 0 4px rgba(25, 118, 210, 0.1);
        }

        /* Total Summary */
        .total-summary-card {
            background: linear-gradient(135deg, #E8F5E9 0%, #C8E6C9 50%, #A5D6A7 100%);
            border: 2px solid rgba(76, 175, 80, 0.3);
            position: relative;
            overflow: hidden;
        }

        .total-summary-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, transparent 50%);
            animation: shimmer 3s ease-in-out infinite;
        }

        @keyframes shimmer {
            0%, 100% { transform: rotate(0deg); }
            50% { transform: rotate(180deg); }
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            position: relative;
            z-index: 1;
        }
        .price-row.total {
            font-weight: bold;
            font-size: 20px;
            color: #2E7D32;
            border-top: 2px solid rgba(76, 175, 80, 0.3);
            margin-top: 12px;
            padding-top: 16px;
        }

        /* Floating Footer */
        .floating-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(248,250,252,0.95) 100%);
            backdrop-filter: blur(20px);
            padding: 20px;
            border-radius: 25px 25px 0 0;
            box-shadow: 0 -8px 32px rgba(0, 0, 0, 0.12);
            display: flex;
            align-items: center;
            gap: 20px;
            border-top: 1px solid rgba(25, 118, 210, 0.1);
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                transform: translateY(100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .footer-total-section {
            flex: 1;
        }
        .footer-total-label {
            font-size: 12px;
            color: #666;
            margin-bottom: 4px;
        }
        .footer-total-price {
            font-size: 22px;
            font-weight: 700;
            color: #4CAF50;
        }
        .process-checkout-btn {
            flex: 1;
            background: linear-gradient(135deg, #1976D2 0%, #1565C0 50%, #0D47A1 100%);
            color: white;
            border: none;
            padding: 18px 24px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 8px 25px rgba(25, 118, 210, 0.3);
            position: relative;
            overflow: hidden;
        }

        .process-checkout-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .process-checkout-btn:hover::before {
            left: 100%;
        }

        .process-checkout-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(25, 118, 210, 0.4);
        }

        .process-checkout-btn:active {
            transform: translateY(-1px);
        }

        .process-checkout-btn:disabled {
            background: linear-gradient(135deg, #9e9e9e 0%, #757575 100%);
            cursor: not-allowed;
            transform: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .process-checkout-btn.loading {
            pointer-events: none;
        }

        .process-checkout-btn .loading-spinner {
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        /* Success Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(5px);
        }
        .modal.show {
            display: flex;
            animation: modalFadeIn 0.4s ease-out;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .modal-content {
            background: white;
            border-radius: 24px;
            padding: 40px;
            max-width: 420px;
            width: 90%;
            text-align: center;
            position: relative;
            animation: modalSlideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        @keyframes modalSlideIn {
            from {
                transform: translateY(50px) scale(0.9);
                opacity: 0;
            }
            to {
                transform: translateY(0) scale(1);
                opacity: 1;
            }
        }

        .modal-icon {
            padding: 24px;
            background: linear-gradient(135deg, #4CAF50 0%, #66BB6A 50%, #81C784 100%);
            border-radius: 50%;
            display: inline-block;
            margin-bottom: 28px;
            box-shadow: 0 12px 30px rgba(76, 175, 80, 0.4);
            animation: successPulse 2s ease-in-out infinite;
        }

        @keyframes successPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .modal-icon i {
            color: white;
            font-size: 44px;
        }

        .modal-content h2 {
            color: #333;
            margin-bottom: 16px;
            font-size: 24px;
            font-weight: 700;
        }

        .modal-content p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 0;
        }

        .modal-actions {
            display: flex;
            gap: 16px;
            justify-content: center;
            margin-top: 32px;
        }
        .btn {
            padding: 14px 28px;
            border-radius: 16px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            font-size: 14px;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
        .btn-secondary {
            background: #f5f5f5;
            color: #666;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .btn-secondary:hover {
            background: #eeeeee;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
        }
        .btn-primary {
            background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(25, 118, 210, 0.3);
        }
        .btn-primary:hover {
            box-shadow: 0 6px 16px rgba(25, 118, 210, 0.4);
        }

        /* Manage Address Button */
        .manage-address-btn {
            background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 16px;
            font-size: 14px;
        }

        .manage-address-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(255, 152, 0, 0.3);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .floating-footer {
                padding: 16px;
                gap: 16px;
            }

            .process-checkout-btn {
                padding: 16px 20px;
                font-size: 15px;
            }

            .footer-total-price {
                font-size: 20px;
            }

            .modern-card {
                padding: 20px;
                margin-bottom: 16px;
            }

            .modal-content {
                padding: 32px 24px;
                margin: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-content">
            <button class="back-btn" onclick="window.location.href='/cart'">
                <i class="fas fa-arrow-left"></i>
            </button>
            <div class="header-icon">
                <i class="fas fa-receipt"></i>
            </div>
            <div class="header-info">
                <h1>Checkout</h1>
                <p id="itemCountHeader">0 item dipilih</p>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="content">
            <div id="loadingState" class="loading">
                <div class="spinner"></div>
                <div class="loading-text">Memuat halaman checkout...</div>
            </div>

            <div id="checkoutContent" style="display: none;">
                <!-- Order Summary -->
                <div class="modern-card fade-in" style="--i: 0">
                    <div class="card-header">
                        <div class="card-icon" style="background-color: rgba(25, 118, 210, 0.1);"><i class="fas fa-receipt" style="color: #1976D2;"></i></div>
                        <h2 class="card-title">Ringkasan Pesanan</h2>
                    </div>
                    <div id="orderSummary"></div>
                </div>

                <!-- Shipping Address -->
                <div class="modern-card fade-in" style="--i: 1">
                    <div class="card-header">
                        <div class="card-icon" style="background-color: rgba(76, 175, 80, 0.1);"><i class="fas fa-location-dot" style="color: #4CAF50;"></i></div>
                        <h2 class="card-title">Alamat Pengiriman</h2>
                    </div>
                    <div id="addressList" class="address-list"></div>
                    <button class="manage-address-btn" onclick="window.location.href='/addresses'">
                        <i class="fas fa-map-marker-alt"></i> Kelola Alamat
                    </button>
                </div>

                <!-- Shipping Method -->
                <div class="modern-card fade-in" style="--i: 2">
                    <div class="card-header">
                        <div class="card-icon" style="background-color: rgba(156, 39, 176, 0.1);"><i class="fas fa-truck-fast" style="color: #9C27B0;"></i></div>
                        <h2 class="card-title">Metode Pengiriman</h2>
                    </div>
                    <div id="shippingMethodList" class="option-list"></div>
                </div>

                <!-- Payment Method -->
                <div class="modern-card fade-in" style="--i: 3">
                    <div class="card-header">
                        <div class="card-icon" style="background-color: rgba(46, 125, 50, 0.1);"><i class="fas fa-credit-card" style="color: #2E7D32;"></i></div>
                        <h2 class="card-title">Metode Pembayaran</h2>
                    </div>
                    <div class="payment-method-container">
                        <!-- Payment Tabs -->
                        <div class="payment-tabs" id="paymentTabs"></div>

                        <!-- Payment Options Panel -->
                        <div class="payment-options-panel" id="paymentOptionsPanel">
                            <div class="payment-options-placeholder" id="paymentPlaceholder">
                                <i class="fas fa-credit-card"></i>
                                <div>Pilih metode pembayaran di atas untuk melihat opsi yang tersedia</div>
                            </div>
                            <div id="paymentOptionsContent" style="display: none; width: 100%;"></div>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="modern-card fade-in" style="--i: 4">
                    <div class="card-header">
                        <div class="card-icon" style="background-color: rgba(255, 152, 0, 0.1);"><i class="fas fa-note-sticky" style="color: #FF9800;"></i></div>
                        <h2 class="card-title">Catatan (Opsional)</h2>
                    </div>
                    <textarea id="notes" class="notes-field" placeholder="Tambahkan catatan untuk penjual (misal: kirim pada jam tertentu, jangan dibunyikan bel, dll)..."></textarea>
                </div>

                <!-- Total Summary -->
                <div class="modern-card total-summary-card fade-in" style="--i: 5">
                    <div class="card-header">
                        <div class="card-icon" style="background-color: rgba(76, 175, 80, 0.2);"><i class="fas fa-receipt" style="color: #2E7D32;"></i></div>
                        <h2 class="card-title">Ringkasan Pembayaran</h2>
                    </div>
                    <div class="price-row">
                        <span>Subtotal Produk</span>
                        <span id="subtotalPrice">Rp 0</span>
                    </div>
                    <div class="price-row total">
                        <span>Total Pembayaran</span>
                        <span id="grandTotalPrice">Rp 0</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Footer -->
    <div class="floating-footer">
        <div class="footer-total-section">
            <div class="footer-total-label">Total Pembayaran</div>
            <div class="footer-total-price" id="footerTotalPrice">Rp 0</div>
        </div>
        <button id="processCheckoutBtn" class="process-checkout-btn" disabled>
            <i class="fas fa-shield-alt"></i>
            <span id="checkoutBtnText">Proses Checkout</span>
        </button>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="modal">
        <div class="modal-content">
            <div class="modal-icon">
                <i class="fas fa-check"></i>
            </div>
            <h2>Pesanan Berhasil!</h2>
            <p>Pesanan Anda telah berhasil dibuat. Anda akan menerima konfirmasi segera.</p>
            <div class="modal-actions">
                <button class="btn btn-secondary" onclick="window.location.href='/fishmarket'">Kembali ke Beranda</button>
                <button class="btn btn-primary" onclick="window.location.href='/orders'">Lihat Pesanan</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // State
            let cartItems = [];
            let addresses = [];
            let selectedAddressId = null;
            let selectedShippingMethod = 'reguler';
            let selectedPaymentMethod = 'cod';
            let selectedPaymentType = 'cod';
            let selectedXenditMethod = '';
            let selectedPaymentChannel = '';
            let shippingCost = 10000;
            let isLoading = false;

            const formatPrice = (price) => new Intl.NumberFormat('id-ID').format(Math.round(price));

            // Functions
            function init() {
                const itemsFromStorage = sessionStorage.getItem('checkoutItems');
                if (!itemsFromStorage) {
                    alert('Tidak ada item untuk di-checkout. Kembali ke keranjang.');
                    window.location.href = '/cart';
                    return;
                }
                cartItems = JSON.parse(itemsFromStorage);
                document.getElementById('itemCountHeader').textContent = `${cartItems.length} item dipilih`;

                renderOrderSummary();
                fetchAddresses();
                renderShippingMethods();
                fetchAndRenderPaymentMethods();
                updateTotals();

                // Smooth transition to show content
                setTimeout(() => {
                    document.getElementById('loadingState').style.display = 'none';
                    document.getElementById('checkoutContent').style.display = 'block';

                    // Trigger animations
                    const cards = document.querySelectorAll('.fade-in');
                    cards.forEach((card, index) => {
                        setTimeout(() => {
                            card.style.opacity = '1';
                        }, index * 100);
                    });
                }, 800);
            }

            function renderOrderSummary() {
                const container = document.getElementById('orderSummary');
                container.innerHTML = cartItems.map(item => `
                    <div class="order-item">
                        <img src="/storage/${item.product.gambar[0]}" class="order-item-img" alt="${item.product.nama}">
                        <div class="order-item-info">
                            <div class="order-item-name">${item.product.nama} (${item.jumlah}x)</div>
                            <div class="order-item-price">Rp ${formatPrice(item.product.harga)}/kg</div>
                        </div>
                        <div class="order-item-total">Rp ${formatPrice(item.product.harga * item.jumlah)}</div>
                    </div>
                `).join('');
            }

            async function fetchAddresses() {
                try {
                    // Get auth token from auth.js
                    const token = getAuthToken ? getAuthToken() : null;

                    const headers = {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    };

                    // Add Authorization header if token exists
                    if (token) {
                        headers['Authorization'] = `Bearer ${token}`;
                    }

                    const response = await fetch('/api/addresses', {
                        headers: headers
                    });
                    const data = await response.json();
                    if (data.success) {
                        addresses = data.data;
                        const mainAddress = addresses.find(addr => addr.alamat_utama) || addresses[0];
                        if (mainAddress) {
                            selectedAddressId = mainAddress.id;
                        }
                        renderAddresses();
                        updateTotals();
                    }
                } catch (error) {
                    console.error('Failed to fetch addresses:', error);
                }
            }

            function renderAddresses() {
                const container = document.getElementById('addressList');
                if (addresses.length === 0) {
                    container.innerHTML = '<p>Belum ada alamat. Silakan tambahkan alamat terlebih dahulu.</p>';
                    return;
                }
                container.innerHTML = addresses.map(addr => `
                    <div class="address-item ${selectedAddressId === addr.id ? 'selected' : ''}" onclick="selectAddress(${addr.id})">
                        <div class="address-radio">
                            <input type="radio" id="addr_${addr.id}" name="address" value="${addr.id}" ${selectedAddressId === addr.id ? 'checked' : ''}>
                            <label for="addr_${addr.id}">
                                <div>
                                    <span class="address-name">${addr.nama_penerima}</span>
                                    ${addr.alamat_utama ? '<span class="address-main-badge">Utama</span>' : ''}
                                    <div>${addr.telepon}</div>
                                    <div>${addr.alamat_lengkap}, ${addr.kota}, ${addr.provinsi}</div>
                                </div>
                            </label>
                        </div>
                    </div>
                `).join('');
            }

            window.selectAddress = (id) => {
                selectedAddressId = id;
                renderAddresses();
                updateTotals();
            };

            function renderShippingMethods() {
                const methods = [
                    { id: 'cak-ed', name: 'Cak Ed Delivery', desc: 'Pengiriman gratis dengan pelayanan ramah - Hubungi: +62 813-3519-0701', price: 0 }
                ];
                const container = document.getElementById('shippingMethodList');
                container.innerHTML = methods.map(method => `
                    <div class="option-item ${selectedShippingMethod === method.id ? 'selected' : ''}" onclick="selectShipping('${method.id}', ${method.price})">
                        <div class="option-radio">
                             <label>
                                <input type="radio" name="shipping" value="${method.id}" ${selectedShippingMethod === method.id ? 'checked' : ''}>
                                <div>
                                    <div class="option-title">${method.name}</div>
                                    <div class="option-subtitle">${method.desc}</div>
                                </div>
                            </label>
                        </div>
                    </div>
                `).join('');
            }

            window.selectShipping = (id, price) => {
                selectedShippingMethod = id;
                shippingCost = price;
                renderShippingMethods();
                updateTotals();
            };

            async function fetchAndRenderPaymentMethods() {
                try {
                    const response = await fetch('/api/payment/methods');
                    const data = await response.json();

                    if (data.success) {
                        renderPaymentMethods(data.data);
                    } else {
                        console.error('Failed to fetch payment methods');
                        renderDefaultPaymentMethods();
                    }
                } catch (error) {
                    console.error('Error fetching payment methods:', error);
                    renderDefaultPaymentMethods();
                }
            }

            let currentPaymentTab = null; // Start with no tab selected

            function renderPaymentMethods(paymentData) {
                renderPaymentTabs(paymentData);
                renderPaymentOptions(paymentData);
            }

            function renderPaymentTabs(paymentData) {
                const tabsContainer = document.getElementById('paymentTabs');
                let tabsHtml = '';

                // COD Tab
                tabsHtml += `
                    <div class="payment-tab cod ${currentPaymentTab === 'cod' ? 'active' : ''}" onclick="switchPaymentTab('cod')">
                        <div class="payment-tab-icon">💵</div>
                        <div class="payment-tab-name">COD</div>
                        <div class="payment-tab-subtitle">Bayar di Tempat</div>
                    </div>
                `;

                // Manual Payment Tab
                tabsHtml += `
                    <div class="payment-tab manual ${currentPaymentTab === 'manual' ? 'active' : ''}" onclick="switchPaymentTab('manual')">
                        <div class="payment-tab-icon">💰</div>
                        <div class="payment-tab-name">Manual</div>
                        <div class="payment-tab-subtitle">Pembayaran Manual</div>
                    </div>
                `;

                // Xendit Tabs
                paymentData.forEach(method => {
                    if (method.is_active) {
                        const iconMap = {
                            'bank_transfer': '🏦',
                            'e_wallet': '💳',
                            'retail': '🏪',
                            'qris': '📱',
                            'credit_card': '💳'
                        };

                        const subtitleMap = {
                            'bank_transfer': 'Transfer Bank',
                            'e_wallet': 'E-Wallet',
                            'retail': 'Retail',
                            'qris': 'QR Code',
                            'credit_card': 'Kartu Kredit'
                        };

                        tabsHtml += `
                            <div class="payment-tab ${currentPaymentTab === method.id ? 'active' : ''}" onclick="switchPaymentTab('${method.id}')">
                                <div class="payment-tab-icon">${iconMap[method.id] || '�'}</div>
                                <div class="payment-tab-name">${method.name}</div>
                                <div class="payment-tab-subtitle">${subtitleMap[method.id] || 'Digital'}</div>
                            </div>
                        `;
                    }
                });

                tabsContainer.innerHTML = tabsHtml;

                // Show placeholder if no tab is selected
                if (!currentPaymentTab) {
                    showPaymentPlaceholder();
                }
            }

            function renderPaymentOptions(paymentData) {
                const optionsContainer = document.getElementById('paymentOptionsContent');
                let optionsHtml = '';

                // COD Options
                optionsHtml += `
                    <div class="payment-options-content" id="payment-options-cod">
                        <div class="payment-options-title">
                            <span>💵</span>
                            <span>Bayar di Tempat (COD)</span>
                        </div>
                        <div class="payment-options-grid">
                            <div class="option-item ${selectedPaymentMethod === 'cod' ? 'selected' : ''}" onclick="selectPayment('cod', 'cod', '', '')">
                                <div class="option-radio">
                                    <label>
                                        <input type="radio" name="payment" value="cod" ${selectedPaymentMethod === 'cod' ? 'checked' : ''}>
                                        <div class="payment-option-content">
                                            <div class="payment-option-logo logo-cod">💵</div>
                                            <div class="payment-option-info">
                                                <div class="option-title">Cash on Delivery</div>
                                                <div class="option-subtitle">Bayar saat barang diterima di lokasi Anda</div>
                                                <div class="payment-option-badge popular">Paling Populer</div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                // Manual Payment Options
                optionsHtml += `
                    <div class="payment-options-content" id="payment-options-manual">
                        <div class="payment-options-title">
                            <span>💰</span>
                            <span>Pembayaran Manual</span>
                        </div>
                        <div class="payment-options-grid">
                            <div class="option-item ${selectedPaymentMethod === 'manual' ? 'selected' : ''}" onclick="selectPayment('manual', 'manual', '', '')">
                                <div class="option-radio">
                                    <label>
                                        <input type="radio" name="payment" value="manual" ${selectedPaymentMethod === 'manual' ? 'checked' : ''}>
                                        <div class="payment-option-content">
                                            <div class="payment-option-logo logo-manual">💰</div>
                                            <div class="payment-option-info">
                                                <div class="option-title">Pembayaran Manual</div>
                                                <div class="option-subtitle">Transfer manual ke rekening toko - Hubungi: +62 813-3519-0701</div>
                                                <div class="payment-option-badge trusted">Terpercaya</div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                // Xendit Options
                paymentData.forEach(method => {
                    if (method.is_active) {
                        const iconMap = {
                            'bank_transfer': '🏦',
                            'e_wallet': '💳',
                            'retail': '🏪',
                            'qris': '📱',
                            'credit_card': '💳'
                        };

                        optionsHtml += `
                            <div class="payment-options-content" id="payment-options-${method.id}">
                                <div class="payment-options-title">
                                    <span>${iconMap[method.id] || '💰'}</span>
                                    <span>${method.name}</span>
                                </div>
                                <div class="payment-options-grid">
                        `;

                        method.channels.forEach(channel => {
                            const channelId = `${method.id}_${channel.code}`;
                            const logo = getPaymentLogo(channel.code);
                            const isPopular = ['BCA', 'OVO', 'DANA'].includes(channel.code);
                            const isInstant = ['OVO', 'DANA', 'QRIS'].includes(channel.code);

                            optionsHtml += `
                                <div class="option-item ${selectedPaymentMethod === channelId ? 'selected' : ''}" onclick="selectPayment('${channelId}', 'xendit', '${method.id}', '${channel.code}')">
                                    <div class="option-radio">
                                        <label>
                                            <input type="radio" name="payment" value="${channelId}" ${selectedPaymentMethod === channelId ? 'checked' : ''}>
                                            <div class="payment-option-content">
                                                <div class="payment-option-logo ${logo.class}">${logo.text}</div>
                                                <div class="payment-option-info">
                                                    <div class="option-title">${channel.name}</div>
                                                    <div class="option-subtitle">${getPaymentDescription(method.id, channel.code)}</div>
                                                    ${isPopular ? '<div class="payment-option-badge popular">Populer</div>' : ''}
                                                    ${isInstant && !isPopular ? '<div class="payment-option-badge instant">Instan</div>' : ''}
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            `;
                        });

                        optionsHtml += `
                                </div>
                            </div>
                        `;
                    }
                });

                optionsContainer.innerHTML = optionsHtml;
            }

            function renderDefaultPaymentMethods() {
                const paymentData = [
                    {
                        id: 'bank_transfer',
                        name: 'Transfer Bank',
                        is_active: true,
                        channels: [
                            { code: 'BCA', name: 'Bank BCA' },
                            { code: 'BRI', name: 'Bank BRI' },
                            { code: 'BNI', name: 'Bank BNI' },
                            { code: 'MANDIRI', name: 'Bank Mandiri' }
                        ]
                    },
                    {
                        id: 'e_wallet',
                        name: 'E-Wallet',
                        is_active: true,
                        channels: [
                            { code: 'OVO', name: 'OVO' },
                            { code: 'DANA', name: 'DANA' },
                            { code: 'LINKAJA', name: 'LinkAja' },
                            { code: 'SHOPEEPAY', name: 'ShopeePay' }
                        ]
                    },
                    {
                        id: 'qris',
                        name: 'QRIS',
                        is_active: true,
                        channels: [
                            { code: 'QRIS', name: 'QRIS' }
                        ]
                    }
                ];

                renderPaymentTabs(paymentData);
                renderPaymentOptions(paymentData);
            }

            function showPaymentPlaceholder() {
                const placeholder = document.getElementById('paymentPlaceholder');
                const content = document.getElementById('paymentOptionsContent');
                const panel = document.getElementById('paymentOptionsPanel');

                placeholder.style.display = 'block';
                content.style.display = 'none';
                panel.classList.remove('has-content');
            }

            function hidePaymentPlaceholder() {
                const placeholder = document.getElementById('paymentPlaceholder');
                const content = document.getElementById('paymentOptionsContent');
                const panel = document.getElementById('paymentOptionsPanel');

                placeholder.style.display = 'none';
                content.style.display = 'block';
                panel.classList.add('has-content');
            }

            window.switchPaymentTab = (tabId) => {
                currentPaymentTab = tabId;

                // Update tab active states
                document.querySelectorAll('.payment-tab').forEach(tab => {
                    tab.classList.remove('active');
                });
                document.querySelector(`.payment-tab[onclick="switchPaymentTab('${tabId}')"]`).classList.add('active');

                // Hide placeholder and show content
                hidePaymentPlaceholder();

                // Update content active states
                document.querySelectorAll('.payment-options-content').forEach(content => {
                    content.classList.remove('active');
                });

                const targetContent = document.getElementById(`payment-options-${tabId}`);
                if (targetContent) {
                    targetContent.classList.add('active');
                }
            };

            function renderPaymentMethodsSelection() {
                // Simpan tab aktif sebelum re-render
                const activeTab = currentPaymentTab;

                // Re-render untuk update selection
                fetchAndRenderPaymentMethods();

                // Restore tab aktif setelah render
                if (activeTab) {
                    setTimeout(() => {
                        switchPaymentTab(activeTab);
                    }, 100);
                }
            }

            function getPaymentLogo(channelCode) {
                const logos = {
                    // Banks
                    'BCA': { text: 'BCA', class: 'logo-bca' },
                    'BRI': { text: 'BRI', class: 'logo-bri' },
                    'BNI': { text: 'BNI', class: 'logo-bni' },
                    'MANDIRI': { text: 'MDR', class: 'logo-mandiri' },
                    'BSI': { text: 'BSI', class: 'logo-bsi' },
                    'CIMB': { text: 'CIMB', class: 'logo-cimb' },
                    'PERMATA': { text: 'PRM', class: 'logo-permata' },

                    // E-Wallets
                    'OVO': { text: 'OVO', class: 'logo-ovo' },
                    'DANA': { text: 'DANA', class: 'logo-dana' },
                    'LINKAJA': { text: 'LINK', class: 'logo-linkaja' },
                    'SHOPEEPAY': { text: 'SPY', class: 'logo-shopeepay' },

                    // Others
                    'QRIS': { text: 'QR', class: 'logo-qris' },
                    'COD': { text: '💵', class: 'logo-cod' },
                    'MANUAL': { text: '💰', class: 'logo-manual' }
                };

                return logos[channelCode] || { text: '💰', class: '' };
            }

            function getPaymentIcon(channelCode) {
                // Keep this for backward compatibility, but we'll use logos now
                const icons = {
                    'BCA': '🔵',
                    'BRI': '🟢',
                    'BNI': '🟠',
                    'MANDIRI': '🔴',
                    'BSI': '🟢',
                    'CIMB': '🔴',
                    'PERMATA': '⚫',
                    'OVO': '🟣',
                    'DANA': '🔵',
                    'LINKAJA': '🔴',
                    'SHOPEEPAY': '🟠',
                    'ALFAMART': '🔴',
                    'INDOMARET': '🔵',
                    'QRIS': '📱',
                    'CREDIT_CARD': '💳'
                };
                return icons[channelCode] || '💰';
            }

            function getPaymentDescription(methodType, channelCode) {
                const descriptions = {
                    'bank_transfer': {
                        'BCA': 'Transfer via Virtual Account BCA',
                        'BRI': 'Transfer via Virtual Account BRI',
                        'BNI': 'Transfer via Virtual Account BNI',
                        'MANDIRI': 'Transfer via Virtual Account Mandiri',
                        'BSI': 'Transfer via Virtual Account BSI',
                        'CIMB': 'Transfer via Virtual Account CIMB',
                        'PERMATA': 'Transfer via Virtual Account Permata'
                    },
                    'e_wallet': {
                        'OVO': 'Bayar dengan saldo OVO',
                        'DANA': 'Bayar dengan saldo DANA',
                        'LINKAJA': 'Bayar dengan saldo LinkAja',
                        'SHOPEEPAY': 'Bayar dengan saldo ShopeePay'
                    },
                    'retail': {
                        'ALFAMART': 'Bayar di kasir Alfamart',
                        'INDOMARET': 'Bayar di kasir Indomaret'
                    },
                    'qr_code': {
                        'QRIS': 'Scan QR code dengan aplikasi e-wallet'
                    },
                    'credit_card': {
                        'CREDIT_CARD': 'Visa, Mastercard, JCB'
                    }
                };

                return descriptions[methodType]?.[channelCode] || 'Metode pembayaran digital';
            }

            window.selectPayment = (id, type, paymentMethod, paymentChannel) => {
                selectedPaymentMethod = id;
                selectedPaymentType = type || 'cod';
                selectedXenditMethod = paymentMethod || '';
                selectedPaymentChannel = paymentChannel || '';

                // Update current tab based on selection
                if (selectedPaymentType === 'cod') {
                    currentPaymentTab = 'cod';
                } else if (selectedPaymentType === 'manual') {
                    currentPaymentTab = 'manual';
                } else {
                    currentPaymentTab = selectedXenditMethod;
                }

                // Update selection visual immediately
                document.querySelectorAll('.option-item').forEach(item => {
                    item.classList.remove('selected');
                });

                const selectedItem = document.querySelector(`.option-item[onclick*="'${id}'"]`);
                if (selectedItem) {
                    selectedItem.classList.add('selected');
                    const radio = selectedItem.querySelector('input[type="radio"]');
                    if (radio) radio.checked = true;
                }

                // Tidak perlu re-render, hanya update visual
                console.log('Payment selected:', { id, type, paymentMethod, paymentChannel });
            };

            function updateTotals() {
                const subtotal = cartItems.reduce((sum, item) => sum + (item.product.harga * item.jumlah), 0);
                const grandTotal = subtotal; // Tidak ada biaya pengiriman

                document.getElementById('subtotalPrice').textContent = `Rp ${formatPrice(subtotal)}`;
                document.getElementById('grandTotalPrice').textContent = `Rp ${formatPrice(grandTotal)}`;
                document.getElementById('footerTotalPrice').textContent = `Rp ${formatPrice(grandTotal)}`;

                document.getElementById('processCheckoutBtn').disabled = !selectedAddressId || isLoading;
            }

            async function processCheckout() {
                if (!selectedAddressId) {
                    alert('Pilih alamat pengiriman terlebih dahulu.');
                    return;
                }

                isLoading = true;
                const btnElement = document.getElementById('processCheckoutBtn');
                const btnTextElement = document.getElementById('checkoutBtnText');
                const btnIcon = btnElement.querySelector('i');

                // Update button state
                btnElement.classList.add('loading');
                btnElement.disabled = true;
                btnIcon.className = 'loading-spinner';
                btnTextElement.textContent = 'Memproses...';

                const checkoutData = {
                    alamat_id: selectedAddressId,
                    metode_pengiriman: selectedShippingMethod,
                    biaya_kirim: shippingCost,
                    metode_pembayaran: selectedPaymentMethod,
                    items: cartItems.map(item => ({ product_id: item.product.id, jumlah: item.jumlah })),
                    catatan: document.getElementById('notes').value
                };

                // Tambahkan data Xendit jika bukan COD
                if (selectedPaymentType === 'xendit') {
                    checkoutData.payment_method = selectedXenditMethod;
                    checkoutData.payment_channel = selectedPaymentChannel;
                }

                try {
                    // Get auth token from auth.js
                    const token = getAuthToken ? getAuthToken() : null;

                    const headers = {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    };

                    // Add Authorization header if token exists
                    if (token) {
                        headers['Authorization'] = `Bearer ${token}`;
                    }

                    const response = await fetch('/api/orders/checkout', {
                        method: 'POST',
                        headers: headers,
                        body: JSON.stringify(checkoutData)
                    });

                    const data = await response.json();
                    if (response.ok && data.success) {
                        sessionStorage.removeItem('checkoutItems');

                        // Jika ada payment_url, redirect ke Xendit
                        if (data.data.payment_url && data.data.requires_payment) {
                            // Update button untuk redirect
                            btnTextElement.textContent = 'Mengarahkan ke pembayaran...';

                            // Simpan order info untuk redirect nanti
                            sessionStorage.setItem('checkout_success', JSON.stringify({
                                order_id: data.data.order.id,
                                order_number: data.data.order.nomor_pesanan,
                                payment_id: data.data.payment_id
                            }));

                            // Redirect ke halaman pembayaran Xendit
                            setTimeout(() => {
                                window.location.href = data.data.payment_url;
                            }, 1000);
                        } else {
                            // COD atau tidak perlu payment, tampilkan modal sukses
                            btnTextElement.textContent = 'Berhasil!';
                            btnIcon.className = 'fas fa-check';

                            setTimeout(() => {
                                document.getElementById('successModal').classList.add('show');
                            }, 500);
                        }
                    } else {
                        throw new Error(data.message || 'Terjadi kesalahan');
                    }
                } catch (error) {
                    console.error('Checkout error:', error);
                    alert(`Checkout gagal: ${error.message}`);

                    // Reset button state
                    btnElement.classList.remove('loading');
                    btnIcon.className = 'fas fa-shield-alt';
                    btnTextElement.textContent = 'Proses Checkout';
                    isLoading = false;
                    updateTotals();
                }
            }

            document.getElementById('processCheckoutBtn').addEventListener('click', processCheckout);

            // Init
            init();
        });
    </script>
</body>
</html>
