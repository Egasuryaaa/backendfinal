<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>IwakMart - PORTAL JANJI TEMU</title>
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
            background: #F0F8FF;
            min-height: 100vh;
            line-height: 1.6;
        }

        .header {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            padding: 2rem 0;
            text-align: center;
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            font-weight: 700;
        }

        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .tabs {
            display: flex;
            margin-bottom: 2rem;
            background: white;
            border-radius: 12px;
            padding: 0.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .tab {
            flex: 1;
            padding: 1rem 2rem;
            border: none;
            background: transparent;
            cursor: pointer;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .tab.active {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .action-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .search-box {
            display: flex;
            gap: 1rem;
        }

        .search-input {
            padding: 0.75rem;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            width: 250px;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
        }

        .btn-secondary {
            background: #f8f9fa;
            color: #333;
            border: 2px solid #e9ecef;
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-warning {
            background: #ffc107;
            color: #333;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
        }

        .card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .card-image {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            margin-right: 1rem;
            flex-shrink: 0;
        }

        .card-info h3 {
            color: #333;
            margin-bottom: 0.5rem;
            font-size: 1.25rem;
        }

        .card-info p {
            color: #666;
            margin: 0;
        }

        .card-details {
            margin-bottom: 1.5rem;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .detail-label {
            color: #666;
            font-weight: 500;
        }

        .detail-value {
            color: #333;
            font-weight: 600;
        }

        .card-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-aktif {
            background: #d4edda;
            color: #155724;
        }

        .status-tidak-aktif {
            background: #f8d7da;
            color: #721c24;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #666;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .back-button {
            position: fixed;
            top: 2rem;
            left: 2rem;
            width: 50px;
            height: 50px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #667eea;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .back-button:hover {
            transform: scale(1.1);
            color: #667eea;
        }

        .loading {
            text-align: center;
            padding: 2rem;
            color: #666;
        }

        @media (max-width: 768px) {
            .header h1 {
                font-size: 2rem;
            }
            
            .container {
                padding: 1rem;
            }
            
            .grid {
                grid-template-columns: 1fr;
            }
            
            .action-bar {
                flex-direction: column;
                gap: 1rem;
            }
            
            .search-box {
                width: 100%;
            }
            
            .search-input {
                width: 100%;
            }
        }

        .location-info {
            margin-bottom: 2rem;
        }

        .info-card {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .info-card h4 {
            margin-bottom: 0.5rem;
            font-size: 1.2rem;
        }

        .info-card p {
            margin-bottom: 0.25rem;
        }

        .distance-badge {
            background: rgba(0, 255, 0, 0.2);
            color: #00cc00;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
            display: inline-block;
            margin-top: 0.5rem;
        }

        .collector-card .distance-info {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            padding: 0.5rem;
            border-radius: 8px;
            margin-top: 1rem;
            text-align: center;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 10000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 0;
            border-radius: 16px;
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            animation: modalSlideIn 0.3s ease-out;
        }

        .modal-large {
            max-width: 800px !important;
            margin: 5% auto !important;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            padding: 2rem 2rem 1rem 2rem;
            text-align: center;
            border-bottom: 1px solid #e9ecef;
        }

        .modal-header i {
            font-size: 3rem;
            color: #667eea;
            margin-bottom: 1rem;
        }

        .modal-header h3 {
            color: #333;
            margin-bottom: 0.5rem;
            font-size: 1.5rem;
        }

        .modal-header p {
            color: #666;
            font-size: 1rem;
            line-height: 1.5;
        }

        .modal-body {
            padding: 1.5rem 2rem;
            max-height: 60vh;
            overflow-y: auto;
        }

        .modal-body::-webkit-scrollbar {
            width: 8px;
        }

        .modal-body::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .modal-body::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }

        .modal-body::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        .warning-box {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border: 1px solid #ffc107;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .warning-box h4 {
            color: #856404;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .warning-box ul {
            color: #856404;
            margin-left: 1.5rem;
            line-height: 1.6;
        }

        .modal-footer {
            padding: 1rem 2rem 2rem 2rem;
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .modal-footer .btn {
            min-width: 120px;
        }

        .close {
            position: absolute;
            right: 1rem;
            top: 1rem;
            color: #999;
            font-size: 1.5rem;
            font-weight: bold;
            cursor: pointer;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .close:hover {
            color: #333;
            background: #f8f9fa;
        }

        .collector-detail-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        .detail-section {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 12px;
            border-left: 4px solid #1e40af;
        }

        .detail-section h4 {
            color: #1e40af;
            margin-bottom: 1rem;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .detail-section .detail-item {
            margin-bottom: 0.75rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #333;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            border-color: #1e40af;
            outline: none;
            box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.1);
        }

        /* Collector List Simple Styles */
        .collector-list-item {
            background: white;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border: 1px solid #f0f0f0;
            cursor: pointer;
            position: relative;
        }

        .collector-list-item:hover {
            box-shadow: 0 8px 25px rgba(33, 150, 243, 0.15);
            transform: translateY(-3px);
            border-color: #2196f3;
            background: linear-gradient(135deg, #ffffff 0%, #f8fbff 100%);
        }

        .collector-list-item:hover .collector-info h4 {
            color: #2196f3;
        }

        .collector-list-item:hover .rank-number {
            background: linear-gradient(135deg, #4caf50, #388e3c);
            transform: scale(1.1);
        }

        .collector-list-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(33, 150, 243, 0.05) 0%, rgba(33, 150, 243, 0.1) 100%);
            border-radius: 12px;
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }

        .collector-list-item:hover::before {
            opacity: 1;
        }

        .list-item-content {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .rank-number {
            background: linear-gradient(135deg, #2196f3, #1976d2);
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
            flex-shrink: 0;
        }

        .collector-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            overflow: hidden;
            flex-shrink: 0;
            background: #f5f5f5;
        }

        .collector-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .default-avatar {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #4caf50, #388e3c);
            color: white;
            font-size: 20px;
        }

        .collector-info {
            flex: 1;
            min-width: 0;
        }

        .collector-info h4 {
            margin: 0 0 4px 0;
            font-size: 16px;
            font-weight: 600;
            color: #333;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .collector-info p {
            margin: 2px 0;
            font-size: 13px;
            color: #666;
            display: flex;
            align-items: center;
            gap: 6px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .collector-info p i {
            width: 12px;
            color: #999;
            flex-shrink: 0;
        }

        .collector-info .rate {
            color: #4caf50;
            font-weight: 600;
        }

        .collector-status {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 8px;
            flex-shrink: 0;
        }

        .distance-badge {
            background: linear-gradient(135deg, #2196f3, #1976d2);
            color: white;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            text-align: center;
            min-width: 50px;
        }

        .distance-badge.meters {
            background: linear-gradient(135deg, #ff9800, #f57c00);
        }

        .status-indicator {
            padding: 3px 8px;
            border-radius: 8px;
            font-size: 10px;
            font-weight: 600;
            text-align: center;
        }

        .status-indicator.active {
            background: #e8f5e8;
            color: #4caf50;
        }

        .status-indicator.inactive {
            background: #ffebee;
            color: #f44336;
        }

        .quick-actions {
            display: flex;
            gap: 6px;
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid #f0f0f0;
        }

        .btn-quick {
            width: 32px;
            height: 32px;
            border: none;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .btn-wa {
            background: linear-gradient(135deg, #25d366, #128c7e);
            color: white;
        }

        .btn-wa:hover {
            background: linear-gradient(135deg, #128c7e, #075e54);
            transform: scale(1.1);
        }

        .btn-location {
            background: linear-gradient(135deg, #2196f3, #1976d2);
            color: white;
        }

        .btn-location:hover {
            background: linear-gradient(135deg, #1976d2, #1565c0);
            transform: scale(1.1);
        }

        .btn-appointment {
            background: linear-gradient(135deg, #ff9800, #f57c00);
            color: white;
        }

        .btn-appointment:hover {
            background: linear-gradient(135deg, #f57c00, #ef6c00);
            transform: scale(1.1);
        }

        /* Modal for collector detail */
        .collector-detail-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            overflow-y: auto;
        }

        .modal-content {
            background: white;
            margin: 5% auto;
            padding: 0;
            border-radius: 16px;
            max-width: 600px;
            width: 90%;
            box-shadow: 0 8px 32px rgba(0,0,0,0.2);
        }

        .modal-header {
            padding: 20px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #999;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-close:hover {
            background: #f5f5f5;
            color: #333;
        }

        .modal-body {
            padding: 20px;
        }

        /* Collector Detail Modal Styles */
        .collector-detail-grid {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .detail-header {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 20px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .detail-image {
            width: 80px;
            height: 80px;
            border-radius: 16px;
            overflow: hidden;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .detail-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .default-detail-image {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #4caf50, #388e3c);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
        }

        .detail-info h3 {
            margin: 0 0 8px 0;
            font-size: 24px;
            font-weight: 700;
            color: #333;
        }

        .detail-info .owner {
            margin: 4px 0;
            color: #666;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .detail-item.full-width {
            grid-column: 1 / -1;
        }

        .detail-label {
            font-size: 14px;
            color: #666;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .detail-value {
            font-size: 16px;
            color: #333;
            font-weight: 500;
            line-height: 1.4;
        }

        .detail-value.price {
            color: #4caf50;
            font-size: 18px;
            font-weight: 700;
        }

        .detail-value.description {
            color: #555;
            line-height: 1.6;
        }

        .detail-actions {
            display: flex;
            gap: 12px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #f0f0f0;
        }

        .detail-actions .btn {
            flex: 1;
            padding: 12px 20px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
        }

        .detail-actions .btn-success {
            background: linear-gradient(135deg, #25d366, #128c7e);
            color: white;
        }

        .detail-actions .btn-success:hover {
            background: linear-gradient(135deg, #128c7e, #075e54);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 211, 102, 0.3);
        }

        .detail-actions .btn-info {
            background: linear-gradient(135deg, #2196f3, #1976d2);
            color: white;
        }

        .detail-actions .btn-info:hover {
            background: linear-gradient(135deg, #1976d2, #1565c0);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(33, 150, 243, 0.3);
        }

        .detail-actions .btn-primary {
            background: linear-gradient(135deg, #ff9800, #f57c00);
            color: white;
        }

        .detail-actions .btn-primary:hover {
            background: linear-gradient(135deg, #f57c00, #ef6c00);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 152, 0, 0.3);
        }

        @media (max-width: 768px) {
            .detail-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            
            .detail-header {
                flex-direction: column;
                text-align: center;
            }
            
            .detail-actions {
                flex-direction: column;
            }
            
            .detail-actions .btn {
                margin-bottom: 8px;
            }
        }

        @media (max-width: 768px) {
            .collector-info h4 {
                font-size: 14px;
            }
            
            .collector-info p {
                font-size: 12px;
            }
            
            .list-item-content {
                gap: 12px;
            }
            
            .collector-avatar {
                width: 40px;
                height: 40px;
            }
            
            .rank-number {
                width: 28px;
                height: 28px;
                font-size: 12px;
            }
        }

        @media (min-width: 768px) {
            .collector-detail-grid {
                grid-template-columns: 1fr 1fr;
            }
        }
    </style>
</head>
<body>
    <a href="{{ route('fishmarket') }}" class="back-button" title="Kembali ke Beranda">
        <i class="fas fa-arrow-left"></i>
    </a>

    <div class="header">
        <h1><i class="fas fa-fish"></i> Manajemen Tambak Ikan</h1>
        <p>Kelola tambak dan hubungi pemilik untuk kerjasama</p>
    </div>

    <div class="container">
        <!-- Tab Navigation -->
        <div class="tabs">
            <button class="tab active" onclick="switchTab('fish-farms')">
                <i class="fas fa-fish"></i> Tambak Ikan
            </button>
            <button class="tab" onclick="switchTab('collectors')">
                <i class="fas fa-users"></i> Cari Pengepul
            </button>
            <button class="tab" onclick="switchTab('nearest-collectors')">
                <i class="fas fa-map-marker-alt"></i> Pengepul Terdekat
            </button>
        </div>

        <!-- Fish Farms Tab -->
        <div id="fish-farms" class="tab-content active">
            <div class="action-bar">
                <div class="search-box">
                    <input type="text" class="search-input" placeholder="Cari tambak..." id="fishFarmSearch">
                    <button class="btn btn-secondary" onclick="searchFishFarms()">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
                <button class="btn btn-primary" onclick="showFishFarmRegistrationModal()">
                    <i class="fas fa-plus"></i> Tambah Tambak
                </button>
            </div>

            <div id="fishFarmsContainer" class="grid">
                <div class="loading">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                    <p>Memuat data tambak...</p>
                </div>
            </div>
        </div>

        <!-- Collectors Tab -->
        <div id="collectors" class="tab-content">
            <div class="action-bar">
                <div class="search-box">
                    <input type="text" class="search-input" placeholder="Cari pengepul..." id="collectorSearch">
                    <button class="btn btn-secondary" onclick="searchCollectors()">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
                <button class="btn btn-primary" onclick="showCollectorRegistrationModal()">
                    <i class="fas fa-plus"></i> Kelola Usaha Pengepul
                </button>
            </div>
            
            <div id="collectorsContainer" class="grid">
                <div class="loading">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                    <p>Memuat data pengepul...</p>
                </div>
            </div>
        </div>

        <!-- Nearest Collectors Tab -->
        <div id="nearest-collectors" class="tab-content">
            <div class="action-bar">
                <div class="search-box">
                    <button class="btn btn-primary" onclick="searchNearestCollectors()">
                        <i class="fas fa-search"></i> Cari Pengepul Terdekat
                    </button>
                </div>
            </div>
            
            <div id="nearestCollectorsContainer" class="grid">
                <div class="empty-state">
                    <i class="fas fa-map-marker-alt"></i>
                    <h3>Cari Pengepul Terdekat</h3>
                    <p>Klik tombol di atas untuk menemukan pengepul terdekat dari lokasi Anda</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Peringatan Pendaftaran Pengepul -->
    <div id="collectorRegistrationModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeCollectorRegistrationModal()">&times;</span>
            
            <div class="modal-header">
                <i class="fas fa-truck"></i>
                <h3>Pendaftaran Sebagai Pengepul</h3>
                <p>Anda akan mendaftar sebagai pengepul ikan</p>
            </div>
            
            <div class="modal-body">
                <div class="warning-box">
                    <h4>
                        <i class="fas fa-exclamation-triangle"></i>
                        Penting untuk Diketahui:
                    </h4>
                    <ul>
                        <li>Akun Anda akan <strong>otomatis diupgrade</strong> menjadi akun Pengepul</li>
                        <li>Anda akan dapat <strong>menerima penjemputan ikan</strong> dari petani tambak</li>
                        <li>Anda akan mendapat <strong>akses fitur tambahan</strong> untuk mengelola bisnis pengepul</li>
                        <li>Perubahan ini akan <strong>disimpan secara permanen</strong> di profil Anda</li>
                    </ul>
                </div>
                
                <p style="text-align: center; color: #666; font-size: 0.95rem;">
                    Apakah Anda yakin ingin melanjutkan pendaftaran sebagai pengepul?
                </p>
            </div>
            
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeCollectorRegistrationModal()">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button class="btn btn-primary" onclick="proceedToCollectorRegistration()">
                    <i class="fas fa-check"></i> Ya, Lanjutkan
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Peringatan Pendaftaran Tambak Ikan -->
    <div id="fishFarmRegistrationModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeFishFarmRegistrationModal()">&times;</span>
            
            <div class="modal-header">
                <i class="fas fa-fish"></i>
                <h3>Pendaftaran Tambak Ikan</h3>
                <p>Anda akan mendaftarkan tambak ikan baru</p>
            </div>
            
            <div class="modal-body">
                <div class="warning-box">
                    <h4>
                        <i class="fas fa-info-circle"></i>
                        Informasi Penting:
                    </h4>
                    <ul>
                        <li>Tambak yang didaftarkan akan <strong>terlihat oleh semua pengepul</strong> di platform</li>
                        <li>Anda akan dapat <strong>menerima tawaran penjemputan</strong> dari pengepul</li>
                        <li>Pastikan <strong>informasi yang diisi akurat</strong> untuk memudahkan komunikasi</li>
                        <li>Anda dapat <strong>mengelola tambak</strong> melalui dashboard setelah pendaftaran</li>
                        <li>Lokasi tambak akan <strong>ditampilkan di peta</strong> untuk pengepul terdekat</li>
                    </ul>
                </div>
                
                <p style="text-align: center; color: #666; font-size: 0.95rem;">
                    Apakah Anda yakin ingin melanjutkan pendaftaran tambak ikan?
                </p>
            </div>
            
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeFishFarmRegistrationModal()">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button class="btn btn-primary" onclick="proceedToFishFarmRegistration()">
                    <i class="fas fa-check"></i> Ya, Lanjutkan
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Detail Pengepul -->
    <div id="collectorDetailModal" class="modal">
        <div class="modal-content modal-large">
            <span class="close" onclick="closeCollectorDetailModal()">&times;</span>
            
            <div class="modal-header">
                <i class="fas fa-truck"></i>
                <h3 id="collectorDetailName">Detail Pengepul</h3>
            </div>
            
            <div class="modal-body">
                <div id="collectorDetailContent">
                    <!-- Content will be populated by JavaScript -->
                </div>
            </div>
            
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeCollectorDetailModal()">
                    <i class="fas fa-times"></i> Tutup
                </button>
                <button id="appointmentBtn" class="btn btn-primary" onclick="showAppointmentForm()" style="display: none;">
                    <i class="fas fa-calendar-plus"></i> Buat Janji Temu
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Buat Janji Temu -->
    <div id="appointmentModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeAppointmentModal()">&times;</span>
            
            <div class="modal-header">
                <i class="fas fa-calendar-plus"></i>
                <h3>Buat Janji Temu Penjemputan</h3>
            </div>
            
            <div class="modal-body">
                <form id="appointmentForm" onsubmit="submitAppointment(event)">
                    @csrf
                    <input type="hidden" id="appointment_collector_id" name="collector_id">
                    
                    <div class="form-group">
                        <label for="fish_farm_select">
                            <i class="fas fa-fish"></i> Pilih Tambak:
                        </label>
                        <select id="fish_farm_select" name="fish_farm_id" class="form-control" required>
                            <option value="">-- Pilih Tambak --</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="appointment_date">
                            <i class="fas fa-calendar"></i> Tanggal Penjemputan:
                        </label>
                        <input type="date" id="appointment_date" name="appointment_date" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="appointment_time">
                            <i class="fas fa-clock"></i> Waktu Penjemputan:
                        </label>
                        <input type="time" id="appointment_time" name="appointment_time" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="fish_type">
                            <i class="fas fa-fish"></i> Jenis Ikan:
                        </label>
                        <input type="text" id="fish_type" name="fish_type" class="form-control" placeholder="Contoh: Lele, Nila, Gurame" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="estimated_weight">
                            <i class="fas fa-weight-hanging"></i> Perkiraan Berat (kg):
                        </label>
                        <input type="number" id="estimated_weight" name="estimated_weight" class="form-control" min="1" placeholder="Contoh: 100" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="pickup_address">
                            <i class="fas fa-map-marker-alt"></i> Alamat Penjemputan:
                        </label>
                        <textarea id="pickup_address" name="pickup_address" class="form-control" rows="3" placeholder="Masukkan alamat lengkap untuk penjemputan (atau akan diisi otomatis dari tambak yang dipilih)" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="notes">
                            <i class="fas fa-sticky-note"></i> Catatan Tambahan:
                        </label>
                        <textarea id="notes" name="notes" class="form-control" rows="2" placeholder="Catatan khusus atau instruksi tambahan (opsional)"></textarea>
                    </div>
                </form>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeAppointmentModal()">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="submit" class="btn btn-primary" form="appointmentForm">
                    <i class="fas fa-check"></i> Buat Janji Temu
                </button>
            </div>
        </div>
    </div>

    <script src="/js/auth.js"></script>
    <script>
        let fishFarms = [];
        let currentUserId = null;
        let collectors = [];
        let nearestCollectors = [];
        let allCollectorsData = []; // Global storage for all collectors data
        let userLocation = null; // Global storage for user location

        // Ensure switchTab is available globally
        window.switchTab = function(tabName) {
            // Hide all tab contents
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(content => {
                content.classList.remove('active');
            });

            // Remove active class from all tabs
            const tabs = document.querySelectorAll('.tab');
            tabs.forEach(tab => {
                tab.classList.remove('active');
            });

            // Show selected tab content
            const selectedTab = document.getElementById(tabName);
            if (selectedTab) {
                selectedTab.classList.add('active');
            }

            // Add active class to clicked tab
            const clickedTab = document.querySelector(`.tab[onclick="switchTab('${tabName}')"]`);
            if (clickedTab) {
                clickedTab.classList.add('active');
            }

            // Load content based on tab
            if (tabName === 'fish-farms') {
                loadFishFarms();
            } else if (tabName === 'collectors') {
                loadCollectors();
            } else if (tabName === 'nearest-collectors') {
                // Don't auto-load, let user click search button
            }
        };

        // Enhanced function to get CSRF token
        function getCSRFToken() {
            return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        }

        // Enhanced function to make API requests with proper headers
        async function makeAPIRequest(url, options = {}) {
            const token = getToken();
            const csrfToken = getCSRFToken();
            
            const defaultHeaders = {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            };
            
            if (token) {
                defaultHeaders['Authorization'] = 'Bearer ' + token;
            }
            
            if (csrfToken) {
                defaultHeaders['X-CSRF-TOKEN'] = csrfToken;
            }
            
            const mergedOptions = {
                ...options,
                headers: {
                    ...defaultHeaders,
                    ...(options.headers || {})
                }
            };
            
            try {
                const response = await fetch(url, mergedOptions);
                return response;
            } catch (error) {
                console.error('API Request failed:', error);
                throw error;
            }
        }

        // Fixed WhatsApp contact function with +62 format
        function contactCollectorFixed(phoneNumber, businessName) {
            if (!phoneNumber || phoneNumber === 'null' || phoneNumber === '') {
                alert('Nomor telepon tidak tersedia untuk pengepul ini');
                return;
            }
            
            // Format phone number - remove non-numeric characters and format properly
            let cleanPhone = phoneNumber.replace(/\D/g, '');
            
            // Remove leading 0 if present and ensure +62 format
            if (cleanPhone.startsWith('0')) {
                cleanPhone = cleanPhone.substring(1); // Remove leading 0
            }
            
            // Add country code if not present
            if (!cleanPhone.startsWith('62')) {
                cleanPhone = '62' + cleanPhone;
            }
            
            // Final format with + sign for display
            const formattedPhone = '+' + cleanPhone;
            
            console.log(`Original phone: ${phoneNumber}, Formatted: ${formattedPhone}`);
            
            const message = `Halo, saya tertarik dengan layanan pengepulan ikan dari ${businessName}. Bisakah saya mendapatkan informasi lebih lanjut?`;
            const encodedMessage = encodeURIComponent(message);
            const whatsappUrl = `https://wa.me/${cleanPhone}?text=${encodedMessage}`;
            
            // Show formatted number to user for confirmation
            if (confirm(`Akan menghubungi ${formattedPhone} via WhatsApp?`)) {
                window.open(whatsappUrl, '_blank');
            }
        }

        // Fixed appointment form function with fish farm selection
        async function showAppointmentFormFixed(collectorId) {
            if (!collectorId) {
                alert('ID Pengepul tidak valid');
                return;
            }
            
            // Check if appointment modal exists, if not create it
            let appointmentModal = document.getElementById('appointmentModal');
            if (!appointmentModal) {
                createAppointmentModal();
                appointmentModal = document.getElementById('appointmentModal');
            }
            
            // Set collector ID in the form
            const collectorIdInput = document.getElementById('appointment_collector_id');
            if (collectorIdInput) {
                collectorIdInput.value = collectorId;
            }
            
            // Load fish farms for current user
            await loadFishFarmsForAppointment();
            
            appointmentModal.style.display = 'block';
        }

        // Debug function to test fish farm loading - can be called from browser console
        window.debugFishFarms = async function() {
            console.log('=== DEBUG FISH FARMS ===');
            
            try {
                // Test 1: Check authentication
                console.log('1. Testing authentication...');
                const userResponse = await makeAPIRequest('/api/user');
                console.log('User response status:', userResponse.status);
                
                if (userResponse.ok) {
                    const userData = await safeParseJSON(userResponse);
                    console.log('User data:', userData);
                } else {
                    console.error('User not authenticated');
                    return;
                }
                
                // Test 2: Get all fish farms (no filter)
                console.log('2. Testing all fish farms...');
                const allResponse = await makeAPIRequest('/api/fish-farms');
                console.log('All fish farms response status:', allResponse.status);
                
                if (allResponse.ok) {
                    const allResult = await safeParseJSON(allResponse);
                    console.log('All fish farms:', allResult);
                } else {
                    console.error('Failed to get all fish farms');
                }
                
                // Test 3: Get user-only fish farms
                console.log('3. Testing user-only fish farms...');
                const userOnlyResponse = await makeAPIRequest('/api/fish-farms?user_only=true');
                console.log('User-only fish farms response status:', userOnlyResponse.status);
                
                if (userOnlyResponse.ok) {
                    const userOnlyResult = await safeParseJSON(userOnlyResponse);
                    console.log('User-only fish farms:', userOnlyResult);
                } else {
                    console.error('Failed to get user-only fish farms');
                }
                
                console.log('=== DEBUG COMPLETE ===');
                
            } catch (error) {
                console.error('Debug error:', error);
            }
        };

        // Load fish farms for appointment form with proper debugging
        async function loadFishFarmsForAppointment() {
            try {
                console.log('Loading fish farms for appointment...');
                
                // First check if user is authenticated
                const userCheckResponse = await makeAPIRequest('/api/user');
                if (!userCheckResponse.ok) {
                    console.error('User not authenticated:', userCheckResponse.status);
                    alert('Anda perlu login terlebih dahulu untuk membuat janji temu');
                    return;
                }
                
                const userData = await safeParseJSON(userCheckResponse);
                console.log('Current user data:', userData);
                
                // Use user_only parameter to get only current user's fish farms
                const response = await makeAPIRequest('/api/fish-farms?user_only=true');

                if (response.ok) {
                    const result = await safeParseJSON(response);
                    console.log('Fish farms API response:', result);
                    
                    const myFishFarms = result.data?.data || result.data || [];
                    console.log('My fish farms:', myFishFarms);
                    
                    const fishFarmSelect = document.getElementById('fish_farm_select');
                    if (fishFarmSelect) {
                        // Clear existing options
                        fishFarmSelect.innerHTML = '<option value="">-- Pilih Tambak --</option>';
                        
                        if (myFishFarms.length > 0) {
                            myFishFarms.forEach(farm => {
                                const option = document.createElement('option');
                                option.value = farm.id;
                                option.textContent = `${farm.nama_tambak} - ${farm.jenis_ikan}`;
                                option.dataset.latitude = farm.latitude || '';
                                option.dataset.longitude = farm.longitude || '';
                                option.dataset.alamat = farm.alamat || '';
                                fishFarmSelect.appendChild(option);
                                console.log(`Added farm option: ${option.textContent}`);
                            });
                        } else {
                            const noDataOption = document.createElement('option');
                            noDataOption.value = '';
                            noDataOption.textContent = 'Belum ada tambak terdaftar';
                            noDataOption.disabled = true;
                            fishFarmSelect.appendChild(noDataOption);
                            console.log('No fish farms found for current user - showing registration message');
                            
                            // Show message to register fish farm
                            setTimeout(() => {
                                if (confirm('Anda belum memiliki tambak terdaftar. Apakah ingin mendaftarkan tambak terlebih dahulu?')) {
                                    // Could redirect to fish farm registration or open registration modal
                                    console.log('User wants to register fish farm');
                                }
                            }, 500);
                        }
                        
                        // Add event listener to auto-fill pickup address
                        fishFarmSelect.removeEventListener('change', handleFishFarmSelection); // Remove existing listener
                        fishFarmSelect.addEventListener('change', handleFishFarmSelection);
                    } else {
                        console.error('Fish farm select element not found');
                    }
                    
                } else {
                    console.error('Failed to load fish farms:', response.status, response.statusText);
                    const errorData = await safeParseJSON(response);
                    console.error('Error details:', errorData);
                    
                    // Show error in dropdown
                    const fishFarmSelect = document.getElementById('fish_farm_select');
                    if (fishFarmSelect) {
                        fishFarmSelect.innerHTML = '<option value="">Error loading tambak</option>';
                    }
                }
            } catch (error) {
                console.error('Error loading fish farms:', error);
                
                // Show error in dropdown
                const fishFarmSelect = document.getElementById('fish_farm_select');
                if (fishFarmSelect) {
                    fishFarmSelect.innerHTML = '<option value="">Error loading tambak</option>';
                }
            }
        }

        // Handle fish farm selection
        function handleFishFarmSelection(event) {
            const selectedOption = event.target.options[event.target.selectedIndex];
            const pickupAddressField = document.getElementById('pickup_address');
            
            if (selectedOption.value && pickupAddressField) {
                const alamat = selectedOption.dataset.alamat || '';
                pickupAddressField.value = alamat;
                console.log('Auto-filled pickup address:', alamat);
            }
        }

        // Close appointment modal
        function closeAppointmentModal() {
            const modal = document.getElementById('appointmentModal');
            if (modal) {
                modal.style.display = 'none';
                // Reset form
                const form = document.getElementById('appointmentForm');
                if (form) {
                    form.reset();
                }
            }
        }

        // Open appointment modal with collector ID
        function openAppointmentModal(collectorId) {
            console.log('Opening appointment modal for collector:', collectorId);
            
            // Validate collector ID
            if (!collectorId || collectorId === 'undefined' || collectorId === 'null' || collectorId === null) {
                alert('Collector ID tidak valid. Silakan refresh halaman dan coba lagi.');
                console.error('Invalid collector ID:', collectorId);
                return;
            }
            
            // Set collector ID in hidden field
            const collectorIdField = document.getElementById('appointment_collector_id');
            if (collectorIdField) {
                collectorIdField.value = collectorId;
                console.log('Set collector ID:', collectorId);
            } else {
                console.error('Collector ID field not found');
                alert('Form error: Collector ID field tidak ditemukan. Silakan refresh halaman.');
                return;
            }
            
            // Check if user is authenticated
            console.log('Checking authentication...');
            
            // Load fish farms for the current user
            console.log('Loading fish farms...');
            loadFishFarmsForAppointment();
            
            // Set minimum date to tomorrow (backend requires after:today)
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            const tomorrowString = tomorrow.toISOString().split('T')[0];
            const dateField = document.getElementById('appointment_date');
            if (dateField) {
                dateField.min = tomorrowString;
                console.log('Set minimum date to tomorrow:', tomorrowString);
            }
            
            // Show modal
            const modal = document.getElementById('appointmentModal');
            if (modal) {
                modal.style.display = 'block';
                console.log('Modal displayed');
            } else {
                console.error('Appointment modal not found');
            }
        }

        // Submit appointment with fish farm selection
        async function submitAppointment(event) {
            event.preventDefault();
            
            // Get the form element from the event target (which should be the form)
            const form = event.target;
            if (!form || form.tagName !== 'FORM') {
                alert('Error: Form tidak ditemukan');
                return;
            }
            
            const formData = new FormData(form);
            
            // Debug: Log all form data
            console.log('Form data entries:');
            for (let [key, value] of formData.entries()) {
                console.log(`${key}: ${value}`);
            }
            
            // Map frontend field names to backend expected field names
            const appointmentData = {
                collector_id: formData.get('collector_id'),
                fish_farm_id: formData.get('fish_farm_id'),
                tanggal_janji: formData.get('appointment_date'),  // Backend expects tanggal_janji
                waktu_janji: formData.get('appointment_time'),    // Backend expects waktu_janji
                perkiraan_berat: parseFloat(formData.get('estimated_weight')), // Backend expects perkiraan_berat
                pesan_pemilik: formData.get('notes') || ''        // Backend expects pesan_pemilik
            };
            
            // Debug: Log appointment data object
            console.log('Appointment data object:', appointmentData);
            
            // Validate required fields (based on backend validation rules)
            console.log('Validating appointment data:', appointmentData);
            
            // Debug: Show collector ID specifically
            console.log('Collector ID from form:', appointmentData.collector_id);
            console.log('Collector ID from hidden field:', document.getElementById('appointment_collector_id')?.value);
            
            if (!appointmentData.collector_id) {
                alert(`Collector ID tidak ditemukan. Debug info:
- Collector ID from form: ${appointmentData.collector_id}
- Collector ID from hidden field: ${document.getElementById('appointment_collector_id')?.value}
- Hidden field exists: ${!!document.getElementById('appointment_collector_id')}

Silakan coba lagi.`);
                return;
            }
            
            if (!appointmentData.fish_farm_id) {
                alert('Silakan pilih tambak terlebih dahulu.');
                return;
            }
            
            if (!appointmentData.tanggal_janji) {
                alert('Silakan isi tanggal penjemputan.');
                return;
            }
            
            // Check if the date is in the future (backend requires after:today)
            const appointmentDate = new Date(appointmentData.tanggal_janji);
            const today = new Date();
            today.setHours(0, 0, 0, 0); // Set to start of today
            appointmentDate.setHours(0, 0, 0, 0); // Set to start of appointment date
            
            if (appointmentDate <= today) {
                alert('Tanggal penjemputan harus lebih dari hari ini. Silakan pilih tanggal besok atau sesudahnya.');
                return;
            }
            
            if (!appointmentData.perkiraan_berat || appointmentData.perkiraan_berat <= 0) {
                alert('Silakan isi perkiraan berat yang valid (lebih dari 0 kg).');
                return;
            }
            
            // Get fish farm name for display
            const fishFarmSelect = document.getElementById('fish_farm_select');
            const selectedOption = fishFarmSelect.options[fishFarmSelect.selectedIndex];
            const fishFarmName = selectedOption.textContent;
            
            // Show loading state - find the submit button correctly
            const submitBtn = document.querySelector('button[form="appointmentForm"][type="submit"]') || 
                             document.querySelector('#appointmentModal .btn-primary[type="submit"]');
            
            let originalText = '';
            if (submitBtn) {
                originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
                submitBtn.disabled = true;
            }
            
            try {
                const token = getToken();
                const csrfToken = getCSRFToken();
                
                if (!token) {
                    alert('Anda harus login terlebih dahulu');
                    return;
                }
                
                if (!csrfToken) {
                    alert('Token keamanan tidak ditemukan. Silakan refresh halaman.');
                    return;
                }

                // Try FormData approach first (often works better with Laravel CSRF)
                const formData = new FormData();
                Object.keys(appointmentData).forEach(key => {
                    formData.append(key, appointmentData[key]);
                });
                
                console.log('Sending FormData request to /api/appointments/collector');
                console.log('Headers:', {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                });
                
                let response = await fetch('/api/appointments/collector', {
                    method: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                });

                // If FormData fails with CSRF error, try JSON approach
                if (!response.ok && response.status === 419) {
                    console.log('FormData failed, trying JSON...');
                    response = await fetch('/api/appointments/collector', {
                        method: 'POST',
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify(appointmentData)
                    });
                }

                console.log('Response status:', response.status);
                console.log('Response ok:', response.ok);
                
                if (!response.ok) {
                    const errorResponse = await response.text();
                    console.log('Error response body:', errorResponse);
                }

                if (response.ok) {
                    const result = await response.json();
                    alert(`Janji penjemputan berhasil dibuat!\n\nDetail:\n- Tambak: ${fishFarmName}\n- Tanggal: ${appointmentData.tanggal_janji}\n- Waktu: ${appointmentData.waktu_janji}\n- Berat: ${appointmentData.perkiraan_berat} kg\n\nPengepul akan menghubungi Anda untuk konfirmasi.`);
                    closeAppointmentModal();
                } else {
                    let errorMessage = 'Terjadi kesalahan saat membuat janji temu';
                    
                    if (response.status === 419) {
                        errorMessage = 'Token keamanan telah kedaluwarsa. Silakan refresh halaman dan coba lagi.';
                    } else if (response.status === 401) {
                        errorMessage = 'Anda tidak memiliki akses. Silakan login kembali.';
                    } else if (response.status === 422) {
                        try {
                            const errorData = await response.json();
                            errorMessage = errorData.message || 'Data yang dikirim tidak valid.';
                            if (errorData.errors) {
                                const firstError = Object.values(errorData.errors)[0];
                                errorMessage += '\n' + firstError[0];
                            }
                        } catch (e) {
                            errorMessage = 'Data yang dikirim tidak valid.';
                        }
                    } else {
                        try {
                            const errorData = await response.json();
                            errorMessage = errorData.message || errorMessage;
                        } catch (e) {
                            errorMessage = `Error ${response.status}: ${response.statusText}`;
                        }
                    }
                    
                    alert(errorMessage);
                }
            } catch (error) {
                console.error('Error creating appointment:', error);
                alert('Terjadi kesalahan saat membuat janji temu');
            } finally {
                // Reset button
                if (submitBtn) {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            }
        }

        // Navigate to collector location
        function navigateToCollectorLocation(collectorId) {
            // Find collector data from multiple sources
            const collector = allCollectorsData.find(c => c.id === collectorId) || 
                            nearestCollectors.find(c => c.id === collectorId) ||
                            collectors.find(c => c.id === collectorId);
            
            if (!collector) {
                alert('Data pengepul tidak ditemukan');
                return;
            }
            
            console.log('Navigating to collector:', collector);
            
            // Get coordinates from various possible fields
            let lat, lng;
            
            if (collector.collector_coordinates && typeof collector.collector_coordinates === 'object') {
                lat = collector.collector_coordinates.lat;
                lng = collector.collector_coordinates.lng;
            } else if (collector.lokasi_koordinat && typeof collector.lokasi_koordinat === 'object') {
                lat = collector.lokasi_koordinat.lat;
                lng = collector.lokasi_koordinat.lng;
            } else {
                lat = collector.latitude || collector.lat || collector.koordinat_latitude;
                lng = collector.longitude || collector.lng || collector.lon || collector.koordinat_longitude;
            }
            
            console.log('Extracted coordinates:', { lat, lng });
            
            if (lat && lng) {
                const latFloat = parseFloat(lat);
                const lngFloat = parseFloat(lng);
                
                if (!isNaN(latFloat) && !isNaN(lngFloat) && latFloat !== 0 && lngFloat !== 0) {
                    // Open Google Maps with directions
                    const mapsUrl = `https://www.google.com/maps/dir/?api=1&destination=${latFloat},${lngFloat}&travelmode=driving`;
                    console.log('Opening Google Maps:', mapsUrl);
                    window.open(mapsUrl, '_blank');
                    return;
                }
            }
            
            // Fallback to address if coordinates not available
            if (collector.alamat) {
                const mapsUrl = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(collector.alamat)}`;
                console.log('Opening Google Maps with address:', mapsUrl);
                window.open(mapsUrl, '_blank');
            } else {
                alert('Koordinat dan alamat lokasi pengepul tidak tersedia');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Check if user is logged in
            if (!getToken()) {
                alert('Anda harus login terlebih dahulu untuk mengakses halaman ini');
                window.location.href = '/login';
                return;
            }
            
            // Initialize with fish farms tab
            switchTab('fish-farms');
        });

        async function loadFishFarms() {
            try {
                const token = getToken();
                if (!token) {
                    alert('Anda harus login terlebih dahulu');
                    window.location.href = '/login';
                    return;
                }

                // Load all fish farms (not just user's own)
                const response = await makeAPIRequest('/api/fish-farms');

                if (response.ok) {
                    const result = await safeParseJSON(response);
                    fishFarms = result.data.data || [];
                    
                    // Get current user info to determine ownership
                    const userResponse = await makeAPIRequest('/api/user');
                    
                    if (userResponse.ok) {
                        const userData = await safeParseJSON(userResponse);
                        currentUserId = userData.data?.id || userData.id;
                    }
                    
                    displayFishFarms(fishFarms);
                } else {
                    // Try to get error message safely
                    let errorMessage = 'Failed to load fish farms';
                    try {
                        const errorData = await safeParseJSON(response);
                        errorMessage = errorData.message || errorMessage;
                    } catch (e) {
                        console.warn('Could not parse error response:', e);
                    }
                    throw new Error(errorMessage);
                }
            } catch (error) {
                console.error('Error loading fish farms:', error);
                displayFishFarmsError();
            }
        }

        function displayFishFarms(farms) {
            const container = document.getElementById('fishFarmsContainer');
            
            if (farms.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-fish"></i>
                        <h3>Belum Ada Tambak</h3>
                        <p>Daftarkan tambak pertama Anda untuk mulai menerima penjemputan</p>
                        <a href="{{ route('fish-farms.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Daftarkan Tambak
                        </a>
                    </div>
                `;
                return;
            }

            container.innerHTML = farms.map(farm => `
                <div class="card farm-card" onclick="viewFishFarm(${farm.id})" style="cursor: pointer;">
                    <div class="card-header">
                        <div class="card-image">
                            ${farm.foto ? `<img src="/storage/${farm.foto}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 12px;">` : '<i class="fas fa-fish"></i>'}
                        </div>
                        <div class="card-info">
                            <h3>${farm.nama}</h3>
                            <p><i class="fas fa-fish"></i> ${farm.jenis_ikan}</p>
                            <span class="status-badge status-${farm.status}">${farm.status}</span>
                        </div>
                    </div>
                    
                    <div class="card-details">
                        <div class="detail-item">
                            <span class="detail-label">Bibit</span>
                            <span class="detail-value">${farm.banyak_bibit.toLocaleString()} ekor</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Luas</span>
                            <span class="detail-value">${farm.luas_tambak} m²</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Estimasi Produksi</span>
                            <span class="detail-value">${(farm.banyak_bibit * 0.4).toFixed(0)} kg</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Alamat</span>
                            <span class="detail-value">${farm.alamat.substring(0, 50)}...</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Pemilik</span>
                            <span class="detail-value">${farm.user?.name || 'Pemilik lain'}</span>
                        </div>
                    </div>
                    
                    <div class="card-actions" onclick="event.stopPropagation();">
                        <button class="btn btn-primary" onclick="contactOwner(${farm.id})">
                            <i class="fas fa-phone"></i> Hubungi Pemilik Tambak
                        </button>
                        ${farm.user_id === currentUserId ? `
                            <button class="btn btn-warning" onclick="editFishFarm(${farm.id})">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-danger" onclick="deleteFishFarm(${farm.id})">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        ` : `
                            <button class="btn btn-info" onclick="viewFishFarm(${farm.id})">
                                <i class="fas fa-eye"></i> Lihat Detail
                            </button>
                        `}
                    </div>
                </div>
            `).join('');
        }

        function displayFishFarmsError() {
            document.getElementById('fishFarmsContainer').innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h3>Gagal Memuat Data</h3>
                    <p>Terjadi kesalahan saat memuat data tambak</p>
                    <button class="btn btn-primary" onclick="loadFishFarms()">
                        <i class="fas fa-refresh"></i> Coba Lagi
                    </button>
                </div>
            `;
        }

        function contactOwner(farmId) {
            const farm = fishFarms.find(f => f.id === farmId);
            if (!farm) {
                alert('Tambak tidak ditemukan');
                return;
            }

            const owner = farm.user;
            if (!owner) {
                alert('Informasi pemilik tidak tersedia');
                return;
            }

            // Format WhatsApp number with +62 prefix and remove leading 0
            function formatWhatsAppNumber(phoneNumber) {
                if (!phoneNumber) return '';
                
                // Remove all non-numeric characters
                let cleanNumber = phoneNumber.replace(/[^0-9]/g, '');
                
                // Remove leading 0 if present
                if (cleanNumber.startsWith('0')) {
                    cleanNumber = cleanNumber.substring(1);
                }
                
                // Add +62 prefix if not already present
                if (!cleanNumber.startsWith('62')) {
                    cleanNumber = '62' + cleanNumber;
                }
                
                return cleanNumber;
            }

            const whatsappNumber = formatWhatsAppNumber(farm.no_telepon);

            // Create contact modal content
            const contactInfo = `
                <div class="contact-info">
                    <h3><i class="fas fa-phone"></i> Hubungi Pemilik Tambak</h3>
                    <div class="contact-details">
                        <h4>${farm.nama}</h4>
                        <p><strong>Pemilik:</strong> ${owner.name}</p>
                        <p><strong>Alamat:</strong> ${farm.alamat}</p>
                        <p><strong>Jenis Ikan:</strong> ${farm.jenis_ikan}</p>
                        ${(() => {
                            let lat, lng;
                            if (farm.lokasi_koordinat && typeof farm.lokasi_koordinat === 'object') {
                                lat = farm.lokasi_koordinat.lat;
                                lng = farm.lokasi_koordinat.lng;
                            } else {
                                lat = farm.latitude || farm.lat || farm.koordinat_latitude;
                                lng = farm.longitude || farm.lng || farm.lon || farm.koordinat_longitude;
                            }
                            
                            if (lat && lng && parseFloat(lat) !== 0 && parseFloat(lng) !== 0) {
                                return `<p><strong>Koordinat:</strong> ${lat}, ${lng}</p>`;
                            } else {
                                return '<p><strong>Koordinat:</strong> <span style="color: orange;">Akan dicari berdasarkan alamat</span></p>';
                            }
                        })()}
                        <p><small>Updated: ${new Date().toLocaleString()}</small></p>
                    </div>
                    <div class="contact-actions">
                        ${farm.no_telepon ? `
                            <a href="https://wa.me/+${whatsappNumber}" target="_blank" class="btn btn-success" style="background-color: #25D366;">
                                <i class="fab fa-whatsapp"></i> WhatsApp
                            </a>
                        ` : ''}
                        <button class="btn btn-primary" onclick="navigateToFarmLocation(${farm.id})">
                            <i class="fas fa-map-marker-alt"></i> Menuju Lokasi
                        </button>
                        <button class="btn btn-secondary" onclick="closeDetailModal()">
                            <i class="fas fa-times"></i> Tutup
                        </button>
                    </div>
                </div>
            `;

            // Show contact modal
            showContactModal(contactInfo);
        }

        function showContactModal(content) {
            const modal = document.getElementById('detailModal');
            const title = document.getElementById('detailModalTitle');
            const body = document.getElementById('detailModalBody');

            title.innerHTML = '<i class="fas fa-phone"></i> Hubungi Pemilik';
            body.innerHTML = content;
            modal.style.display = 'block';
        }

        async function navigateToFarmLocation(farmId) {
            try {
                // Find the farm in our current data
                const farm = fishFarms.find(f => f.id === farmId);
                if (!farm) {
                    alert('Data tambak tidak ditemukan');
                    return;
                }

                // Debug: Log farm data to see what's available
                console.log('Farm data:', farm);
                console.log('All farm keys:', Object.keys(farm));
                console.log('lokasi_koordinat:', farm.lokasi_koordinat);
                
                // Koordinat disimpan dalam field lokasi_koordinat sebagai object dengan lat dan lng
                let lat, lng;
                
                if (farm.lokasi_koordinat && typeof farm.lokasi_koordinat === 'object') {
                    lat = farm.lokasi_koordinat.lat;
                    lng = farm.lokasi_koordinat.lng;
                } else {
                    // Fallback ke field terpisah jika ada
                    lat = farm.latitude || farm.lat || farm.koordinat_latitude || farm.coord_lat || farm.location_lat;
                    lng = farm.longitude || farm.lng || farm.lon || farm.koordinat_longitude || farm.coord_lng || farm.location_lng;
                }

                console.log('Raw coordinates from farm:', { 
                    lokasi_koordinat: farm.lokasi_koordinat,
                    latitude: farm.latitude, 
                    longitude: farm.longitude,
                    extractedLat: lat,
                    extractedLng: lng
                });
                console.log('Extracted coordinates:', { lat, lng });

                // PRIORITAS: Gunakan koordinat jika ada, bahkan jika alamat juga tersedia
                if (lat && lng) {
                    const latFloat = parseFloat(lat);
                    const lngFloat = parseFloat(lng);
                    
                    console.log('Parsed coordinates:', { latFloat, lngFloat });

                    if (!isNaN(latFloat) && !isNaN(lngFloat) && latFloat !== 0 && lngFloat !== 0) {
                        console.log(`✅ USING COORDINATES: ${latFloat}, ${lngFloat}`);
                        // URL format dengan pin/marker untuk koordinat yang tepat
                        const googleMapsUrl = `https://www.google.com/maps?q=${latFloat},${lngFloat}&hl=id`;
                        console.log('Google Maps URL with pin:', googleMapsUrl);
                        window.open(googleMapsUrl, '_blank');
                        return;
                    } else {
                        console.log('❌ Invalid coordinates - values are 0, NaN, or invalid');
                    }
                } else {
                    console.log('❌ No coordinates found in any field');
                }

                // Fallback: Use address ONLY if coordinates really not available
                console.log('⚠️ FALLING BACK TO ADDRESS');
                if (farm.alamat) {
                    console.log(`Using farm address: ${farm.alamat}`);
                    const googleMapsUrl = `https://maps.google.com/maps?q=${encodeURIComponent(farm.alamat)}&hl=id`;
                    console.log('Address Google Maps URL:', googleMapsUrl);
                    window.open(googleMapsUrl, '_blank');
                } else {
                    alert('Lokasi tambak tidak tersedia');
                }

            } catch (error) {
                console.error('Error navigating to farm location:', error);
                alert('Terjadi kesalahan saat membuka lokasi');
            }
        }

        async function navigateToCollectorLocation(collectorId) {
            try {
                // Find the collector in our current data from multiple sources
                const collector = allCollectorsData.find(c => c.id === collectorId) || 
                                nearestCollectors.find(c => c.id === collectorId) ||
                                collectors.find(c => c.id === collectorId);
                
                if (!collector) {
                    alert('Data pengepul tidak ditemukan');
                    return;
                }

                console.log('Collector data:', collector);
                console.log('All collector keys:', Object.keys(collector));

                // Get coordinates from various possible fields
                let lat, lng;
                
                if (collector.collector_coordinates && typeof collector.collector_coordinates === 'object') {
                    lat = collector.collector_coordinates.lat;
                    lng = collector.collector_coordinates.lng;
                } else if (collector.lokasi_koordinat && typeof collector.lokasi_koordinat === 'object') {
                    lat = collector.lokasi_koordinat.lat;
                    lng = collector.lokasi_koordinat.lng;
                } else {
                    lat = collector.latitude || collector.lat || collector.koordinat_latitude;
                    lng = collector.longitude || collector.lng || collector.lon || collector.koordinat_longitude;
                }

                console.log('Extracted coordinates:', { 
                    lokasi_koordinat: collector.lokasi_koordinat,
                    collector_coordinates: collector.collector_coordinates,
                    latitude: collector.latitude, 
                    longitude: collector.longitude,
                    extractedLat: lat,
                    extractedLng: lng
                });

                // Use coordinates if available
                if (lat && lng) {
                    const latFloat = parseFloat(lat);
                    const lngFloat = parseFloat(lng);
                    
                    console.log('Parsed coordinates:', { latFloat, lngFloat });

                    if (!isNaN(latFloat) && !isNaN(lngFloat) && latFloat !== 0 && lngFloat !== 0) {
                        console.log(`✅ USING COORDINATES: ${latFloat}, ${lngFloat}`);
                        // URL format with directions for better navigation
                        const googleMapsUrl = `https://www.google.com/maps/dir/?api=1&destination=${latFloat},${lngFloat}&travelmode=driving`;
                        console.log('Google Maps URL with directions:', googleMapsUrl);
                        window.open(googleMapsUrl, '_blank');
                        return;
                    } else {
                        console.log('Invalid coordinates - using address fallback');
                    }
                } else {
                    console.log('No coordinates found - using address fallback');
                }

                // Fallback: Use address if coordinates not available
                if (collector.alamat) {
                    console.log(`Navigating to collector address: ${collector.alamat}`);
                    const googleMapsUrl = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(collector.alamat)}`;
                    console.log('Address Google Maps URL:', googleMapsUrl);
                    window.open(googleMapsUrl, '_blank');
                } else {
                    alert('Lokasi pengepul tidak tersedia');
                }

            } catch (error) {
                console.error('Error navigating to collector location:', error);
                alert('Terjadi kesalahan saat membuka lokasi');
            }
        }

        function editFishFarm(farmId) {
            window.location.href = `/fish-farms/${farmId}/edit`;
        }

        function viewFishFarm(farmId) {
            const farm = fishFarms.find(f => f.id === farmId);
            if (farm) {
                showDetailModal('fish-farm', farm);
            }
        }

        function showDetailModal(type, data) {
            const modal = document.getElementById('detailModal');
            const title = document.getElementById('detailModalTitle');
            const body = document.getElementById('detailModalBody');

            if (type === 'fish-farm') {
                title.innerHTML = '<i class="fas fa-fish"></i> Detail Tambak Ikan';
                body.innerHTML = generateFishFarmDetailHTML(data);
            }

            modal.style.display = 'block';
        }

        function generateFishFarmDetailHTML(farm) {
            return `
                <div class="detail-section">
                    <h4>📋 Informasi Dasar</h4>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <span class="detail-label">Nama Tambak</span>
                            <span class="detail-value">${farm.nama || 'Belum diisi'}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Status</span>
                            <span class="status-badge-detail status-${farm.status || 'unknown'}">${(farm.status || 'Tidak diset').toUpperCase()}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Pemilik</span>
                            <span class="detail-value">${farm.user?.name || 'Tidak ada'}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">No. Telepon</span>
                            <span class="detail-value">${farm.no_telepon || 'Belum diisi'}</span>
                        </div>
                    </div>
                </div>

                <div class="detail-section">
                    <h4>🐟 Detail Produksi</h4>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <span class="detail-label">Jenis Ikan</span>
                            <span class="detail-value">${farm.jenis_ikan || 'Belum diisi'}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Jumlah Bibit</span>
                            <span class="detail-value">${farm.banyak_bibit ? farm.banyak_bibit.toLocaleString() + ' ekor' : 'Belum diisi'}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Luas Tambak</span>
                            <span class="detail-value">${farm.luas_tambak ? farm.luas_tambak.toLocaleString() + ' m²' : 'Belum diisi'}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Estimasi Produksi</span>
                            <span class="detail-value">${farm.banyak_bibit ? (farm.banyak_bibit * 0.4).toFixed(0) + ' kg' : 'Belum diisi'}</span>
                        </div>
                    </div>
                </div>

                <div class="detail-section">
                    <h4>📍 Lokasi</h4>
                    <div class="detail-item">
                        <span class="detail-label">Alamat</span>
                        <span class="detail-value">${farm.alamat || 'Belum diisi'}</span>
                    </div>
                </div>

                ${farm.deskripsi ? `
                <div class="detail-section">
                    <h4>📝 Deskripsi</h4>
                    <div class="detail-item">
                        <span class="detail-value">${farm.deskripsi}</span>
                    </div>
                </div>
                ` : ''}

                ${farm.foto ? `
                <div class="detail-section">
                    <h4>📸 Foto Tambak</h4>
                    <div class="detail-image">
                        <img src="/storage/${farm.foto}" alt="Foto Tambak">
                    </div>
                </div>
                ` : ''}
            `;
        }

        function closeDetailModal() {
            document.getElementById('detailModal').style.display = 'none';
        }

        async function deleteFishFarm(farmId) {
            if (!confirm('Apakah Anda yakin ingin menghapus tambak ini?')) {
                return;
            }

            try {
                const response = await fetch(`/api/fish-farms/${farmId}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': 'Bearer ' + getToken(),
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    alert('Tambak berhasil dihapus');
                    loadFishFarms();
                } else {
                    alert('Gagal menghapus tambak');
                }
            } catch (error) {
                console.error('Error deleting fish farm:', error);
                alert('Terjadi kesalahan saat menghapus tambak');
            }
        }

        function searchFishFarms() {
            const query = document.getElementById('fishFarmSearch').value.toLowerCase();
            const filtered = fishFarms.filter(farm => 
                farm.nama.toLowerCase().includes(query) ||
                farm.jenis_ikan.toLowerCase().includes(query) ||
                farm.alamat.toLowerCase().includes(query)
            );
            displayFishFarms(filtered);
        }

        // Collectors Functions
        async function loadCollectors() {
            try {
                const token = getToken();
                if (!token) {
                    alert('Anda harus login terlebih dahulu');
                    window.location.href = '/login';
                    return;
                }

                const response = await fetch('/api/collectors', {
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    const result = await safeParseJSON(response);
                    console.log('Collectors API response:', result);
                    // Handle paginated response - data is in result.data.data
                    collectors = result.data?.data || result.data || [];
                    console.log('Processed collectors:', collectors);
                    displayCollectors(collectors);
                } else {
                    console.error('Collectors API error:', response.status, response.statusText);
                    displayCollectorsError();
                }
            } catch (error) {
                console.error('Error loading collectors:', error);
                displayCollectorsError();
            }
        }

        function displayCollectors(collectorsData) {
            const container = document.getElementById('collectorsContainer');
            
            // Ensure collectorsData is an array
            if (!Array.isArray(collectorsData)) {
                console.error('collectorsData is not an array:', collectorsData);
                displayCollectorsError();
                return;
            }
            
            if (collectorsData.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-users"></i>
                        <h3>Belum Ada Pengepul</h3>
                        <p>Daftarkan usaha pengepul Anda untuk mulai melayani petani</p>
                        <a href="/collectors/create" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Daftarkan Usaha Pengepul
                        </a>
                    </div>
                `;
                return;
            }

            container.innerHTML = collectorsData.map(collector => `
                <div class="card collector-card" onclick="showCollectorDetail(${collector.id})" style="cursor: pointer;">
                    <div class="card-header">
                        <div class="card-image">
                            ${collector.foto ? `<img src="/storage/${collector.foto}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 12px;">` : '<i class="fas fa-users"></i>'}
                        </div>
                        <div class="card-info">
                            <h3>${collector.nama_usaha || collector.nama || 'Nama tidak tersedia'}</h3>
                            <p><i class="fas fa-map-marker-alt"></i> ${collector.alamat || 'Alamat tidak tersedia'}</p>
                            <span class="status-badge status-${collector.status || 'active'}">${collector.status || 'Aktif'}</span>
                        </div>
                    </div>
                    
                    <div class="card-details">
                        <div class="detail-item">
                            <span class="detail-label">Kontak</span>
                            <span class="detail-value">${collector.no_telepon || 'Tidak tersedia'}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Rate per KG</span>
                            <span class="detail-value">Rp ${collector.rate_harga_per_kg ? parseInt(collector.rate_harga_per_kg).toLocaleString() : 'Tidak tersedia'}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Kapasitas Max</span>
                            <span class="detail-value">${collector.kapasitas_maksimal ? collector.kapasitas_maksimal + ' kg' : 'Tidak tersedia'}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Email</span>
                            <span class="detail-value">${collector.user?.email || collector.email || 'Tidak tersedia'}</span>
                        </div>
                    </div>

                    <div class="card-actions" onclick="event.stopPropagation();">
                        <button class="btn btn-success" onclick="contactCollectorFixed('${collector.no_telepon || ''}', ${JSON.stringify(collector.nama_usaha || collector.nama || 'Pengepul')})">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                        </button>
                        <button class="btn btn-info" onclick="navigateToCollectorLocation(${collector.id})">
                            <i class="fas fa-directions"></i> Menuju Lokasi
                        </button>
                    </div>
                </div>
            `).join('');
        }

        function displayCollectorsError() {
            document.getElementById('collectorsContainer').innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h3>Gagal Memuat Data</h3>
                    <p>Terjadi kesalahan saat memuat data pengepul</p>
                    <button class="btn btn-primary" onclick="loadCollectors()">
                        <i class="fas fa-refresh"></i> Coba Lagi
                    </button>
                </div>
            `;
        }

        function searchCollectors() {
            const query = document.getElementById('collectorSearch').value.toLowerCase();
            const filtered = collectors.filter(collector => 
                (collector.nama && collector.nama.toLowerCase().includes(query)) ||
                (collector.alamat && collector.alamat.toLowerCase().includes(query)) ||
                (collector.no_telepon && collector.no_telepon.includes(query))
            );
            displayCollectors(filtered);
        }

        async function searchNearestCollectors() {
            try {
                const container = document.getElementById('nearestCollectorsContainer');
                container.innerHTML = `
                    <div class="loading">
                        <i class="fas fa-spinner fa-spin fa-2x"></i>
                        <p>Mencari pengepul terdekat...</p>
                    </div>
                `;

                // Get user's current location with high accuracy
                const options = {
                    enableHighAccuracy: true,
                    timeout: 15000,
                    maximumAge: 0 // Force fresh location for accurate distance calculation
                };

                navigator.geolocation.getCurrentPosition(async (position) => {
                    const { latitude, longitude, accuracy } = position.coords;
                    
                    console.log('Current location for nearest collectors:', { 
                        latitude, 
                        longitude, 
                        accuracy: accuracy ? Math.round(accuracy) + 'm' : 'unknown' 
                    });
                    
                    // Update userLocation global variable
                    userLocation = { latitude, longitude };
                    
                    // Update user location in backend for future reference
                    const token = getToken();
                    try {
                        await makeAPIRequest('/api/user', {
                            method: 'PUT',
                            body: JSON.stringify({
                                latitude: latitude,
                                longitude: longitude
                            })
                        });
                    } catch (updateError) {
                        console.warn('Failed to update user location in backend:', updateError);
                    }
                    
                    // Get max distance filter
                    const maxDistance = document.getElementById('maxDistanceFilter')?.value || 50;
                    
                    const response = await makeAPIRequest(`/api/collectors/nearest?lat=${latitude}&lng=${longitude}&max_distance=${maxDistance}`);

                    if (response.ok) {
                        const result = await safeParseJSON(response);
                        console.log('Nearest collectors API response:', result);
                        
                        const collectorsData = result.data?.data || result.data || [];
                        
                        // Store all collectors data globally for other functions
                        allCollectorsData = collectorsData;
                        nearestCollectors = collectorsData;
                        
                        console.log(`Found ${collectorsData.length} collectors within ${maxDistance}km`);
                        displayNearestCollectors(collectorsData);
                    } else {
                        const errorData = await safeParseJSON(response);
                        console.error('API Error:', errorData);
                        displayNearestCollectorsError();
                    }
                }, (error) => {
                    console.error('Geolocation error:', error);
                    let errorMessage = 'Tidak dapat mengakses lokasi Anda. ';
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            errorMessage += 'Silakan izinkan akses lokasi di browser dan coba lagi.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMessage += 'Pastikan GPS aktif dan sinyal baik.';
                            break;
                        case error.TIMEOUT:
                            errorMessage += 'Waktu permintaan lokasi habis. Coba lagi dengan koneksi yang lebih baik.';
                            break;
                        default:
                            errorMessage += 'Terjadi kesalahan yang tidak diketahui.';
                            break;
                    }
                    alert(errorMessage);
                    displayNearestCollectorsError();
                }, options);

            } catch (error) {
                console.error('Error searching nearest collectors:', error);
                displayNearestCollectorsError();
            }
        }

        function displayNearestCollectors(collectorsData) {
            const container = document.getElementById('nearestCollectorsContainer');
            
            // Ensure collectorsData is an array
            if (!Array.isArray(collectorsData)) {
                console.error('collectorsData is not an array:', collectorsData);
                displayNearestCollectorsError();
                return;
            }
            
            console.log('Displaying nearest collectors:', collectorsData);
            
            if (collectorsData.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-search"></i>
                        <h3>Tidak Ada Pengepul Terdekat</h3>
                        <p>Tidak ditemukan pengepul di sekitar lokasi Anda dalam radius pencarian.</p>
                        <button class="btn btn-primary" onclick="expandSearchRadius()">
                            <i class="fas fa-expand-arrows-alt"></i> Perluas Radius Pencarian
                        </button>
                    </div>
                `;
                return;
            }

            // Store collectors data globally for other functions to use
            allCollectorsData = collectorsData;

            container.innerHTML = collectorsData.map((collector, index) => {
                
                // Debug collector data structure
                console.log(`Collector ${index}:`, {
                    full_object: collector,
                    id: collector.id,
                    distance: collector.distance,
                    distance_formatted: collector.distance_formatted,
                    coordinates: collector.collector_coordinates
                });
                
                // Validate collector ID
                if (!collector.id) {
                    console.warn(`Collector at index ${index} has no ID:`, collector);
                }
                
                return `
                <div class="collector-list-item" onclick="showCollectorDetail(${collector.id})" style="cursor: pointer;" title="Klik untuk melihat detail pengepul">
                    <div class="list-item-content">
                        <div class="rank-number">#${index + 1}</div>
                        <div class="collector-avatar">
                            ${collector.foto ? 
                                `<img src="/storage/${collector.foto}" alt="${collector.nama_usaha || 'Pengepul'}" />` : 
                                '<div class="default-avatar"><i class="fas fa-truck"></i></div>'
                            }
                        </div>
                        <div class="collector-info">
                            <h4>${collector.nama_usaha || collector.nama || 'Nama Usaha Tidak Tersedia'} <i class="fas fa-eye" style="color: #2196f3; font-size: 0.8em; margin-left: 8px;" title="Klik untuk detail"></i></h4>
                            <p class="owner"><i class="fas fa-user"></i> ${collector.user?.name || 'Pemilik tidak diketahui'}</p>
                            <p class="location"><i class="fas fa-map-marker-alt"></i> ${collector.alamat || 'Alamat tidak tersedia'}</p>
                            <p class="rate"><i class="fas fa-money-bill-wave"></i> Rp ${(collector.rate_per_kg || collector.rate_harga_per_kg) ? parseInt(collector.rate_per_kg || collector.rate_harga_per_kg).toLocaleString('id-ID') : 'Nego'}/kg</p>
                        </div>
                        <div class="collector-status">
                            <div class="distance-badge ${collector.distance && collector.distance < 1 ? 'meters' : 'kilometers'}">
                                ${collector.distance_formatted || (collector.distance !== undefined ? 
                                    (collector.distance < 1 ? Math.round(collector.distance * 1000) + ' m' : collector.distance.toFixed(1) + ' km') 
                                    : 'Menghitung...')}
                            </div>
                            <div class="status-indicator ${collector.status === 'aktif' ? 'active' : 'inactive'}">
                                ${collector.status === 'aktif' ? 'AKTIF' : 'TIDAK AKTIF'}
                            </div>
                        </div>
                    </div>
                    <div class="quick-actions" onclick="event.stopPropagation();">
                        <button class="btn-quick btn-wa" onclick="contactCollectorFixed('${collector.no_telepon || ''}', '${(collector.nama_usaha || 'Pengepul').replace(/'/g, '\\\'')}')" title="WhatsApp">
                            <i class="fab fa-whatsapp"></i>
                        </button>
                        <button class="btn-quick btn-location" onclick="navigateToCollectorLocation(${collector.id})" title="Lokasi">
                            <i class="fas fa-directions"></i>
                        </button>
                        <button class="btn-quick btn-appointment" onclick="openAppointmentModal(${collector.id})" title="Buat Janji" ${!collector.id ? 'disabled style="opacity: 0.5; cursor: not-allowed;"' : ''}>
                            <i class="fas fa-calendar-plus"></i>
                        </button>
                    </div>
                </div>
            `}).join('');
        }

        function expandSearchRadius() {
            const currentRadius = parseInt(document.getElementById('maxDistanceFilter')?.value || 100);
            const newRadius = Math.min(currentRadius + 50, 500); // Max 500km
            
            if (document.getElementById('maxDistanceFilter')) {
                document.getElementById('maxDistanceFilter').value = newRadius;
            }
            
            alert(`Memperluas radius pencarian menjadi ${newRadius} km...`);
            loadNearestCollectors();
        }

        function contactCollector(phoneNumber, collectorName) {
            if (!phoneNumber) {
                alert('Nomor telepon pengepul tidak tersedia');
                return;
            }
            
            // Clean phone number
            let cleanPhone = phoneNumber.replace(/\D/g, '');
            
            // Add country code if not present
            if (cleanPhone.startsWith('0')) {
                cleanPhone = '62' + cleanPhone.substring(1);
            } else if (!cleanPhone.startsWith('62')) {
                cleanPhone = '62' + cleanPhone;
            }
            
            const message = encodeURIComponent(`Halo ${collectorName}, saya tertarik dengan layanan pengepulan ikan Anda. Bisakah kita diskusi lebih lanjut?`);
            const whatsappUrl = `https://wa.me/${cleanPhone}?text=${message}`;
            
            window.open(whatsappUrl, '_blank');
        }

        function displayNearestCollectorsError() {
            document.getElementById('nearestCollectorsContainer').innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h3>Gagal Mencari Pengepul</h3>
                    <p>Terjadi kesalahan saat mencari pengepul terdekat</p>
                    <button class="btn btn-primary" onclick="searchNearestCollectors()">
                        <i class="fas fa-refresh"></i> Coba Lagi
                    </button>
                </div>
            `;
        }

        function contactCollector(collectorId) {
            const collector = collectors.find(c => c.id === collectorId) || 
                             nearestCollectors.find(c => c.id === collectorId);
            
            if (!collector) {
                alert('Data pengepul tidak ditemukan');
                return;
            }

            if (collector.no_telepon) {
                // Format WhatsApp number
                function formatWhatsAppNumber(phoneNumber) {
                    if (!phoneNumber) return '';
                    let cleanNumber = phoneNumber.replace(/[^0-9]/g, '');
                    if (cleanNumber.startsWith('0')) {
                        cleanNumber = cleanNumber.substring(1);
                    }
                    if (!cleanNumber.startsWith('62')) {
                        cleanNumber = '62' + cleanNumber;
                    }
                    return cleanNumber;
                }

                const whatsappNumber = formatWhatsAppNumber(collector.no_telepon);
                const collectorName = collector.nama || 'Pengepul';
                const message = `Halo, saya tertarik dengan layanan pengepul ikan ${collectorName}. Bisakah kita diskusi lebih lanjut?`;
                const whatsappUrl = `https://wa.me/${whatsappNumber}?text=${encodeURIComponent(message)}`;
                window.open(whatsappUrl, '_blank');
            } else {
                alert('Nomor telepon pengepul tidak tersedia');
            }
        }

        function editCollector(collectorId) {
            window.location.href = `/collectors/${collectorId}/edit`;
        }

        async function deleteCollector(collectorId) {
            if (!confirm('Apakah Anda yakin ingin menghapus data pengepul ini?')) {
                return;
            }

            try {
                const response = await fetch(`/api/collectors/${collectorId}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': 'Bearer ' + getToken(),
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    alert('Data pengepul berhasil dihapus');
                    loadCollectors();
                } else {
                    alert('Gagal menghapus data pengepul');
                }
            } catch (error) {
                console.error('Error deleting collector:', error);
                alert('Terjadi kesalahan saat menghapus data pengepul');
            }
        }

        // Appointment Modal Functions
        function showAppointmentModal(collector) {
            const modal = document.getElementById('appointmentModal');
            document.getElementById('modalCollectorName').textContent = collector.nama;
            document.getElementById('modalCollectorRate').textContent = `Rp ${parseInt(collector.rate_per_kg || collector.rate_harga_per_kg || 0).toLocaleString()}/kg`;
            document.getElementById('collectorId').value = collector.id;
            
            // Set minimum date to tomorrow
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            document.getElementById('tanggalPenjemputan').min = tomorrow.toISOString().split('T')[0];
            
            modal.style.display = 'block';
        }

        function closeAppointmentModal() {
            document.getElementById('appointmentModal').style.display = 'none';
            document.getElementById('appointmentForm').reset();
        }

        async function submitFishFarmAppointment() {
            const form = document.getElementById('appointmentForm');
            const formData = new FormData(form);
            
            const data = {
                collector_id: formData.get('collector_id'),
                tanggal_penjemputan: formData.get('tanggal_penjemputan'),
                perkiraan_berat: parseFloat(formData.get('perkiraan_berat')),
                harga_per_kg: parseFloat(formData.get('harga_per_kg')),
                catatan: formData.get('catatan')
            };

            try {
                const token = getToken();
                if (!token) {
                    alert('Anda harus login terlebih dahulu');
                    return;
                }

                // Get the first fish farm (for now, allow selection later)
                if (fishFarms.length === 0) {
                    alert('Tidak ada tambak yang tersedia');
                    return;
                }

                const fishFarmId = fishFarms[0].id; // Use first fish farm for now
                
                const response = await fetch(`/api/fish-farms/${fishFarmId}/appointments`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': getCSRFToken()
                    },
                    body: JSON.stringify(data)
                });

                if (response.ok) {
                    const result = await safeParseJSON(response);
                    if (result.success) {
                        alert('Janji penjemputan berhasil dibuat!');
                        closeAppointmentModal();
                    } else {
                        throw new Error(result.message || 'Gagal membuat janji');
                    }
                } else {
                    // Handle specific error codes
                    let errorMessage = 'Gagal membuat janji';
                    
                    if (response.status === 419) {
                        errorMessage = 'Token keamanan telah kedaluwarsa. Silakan refresh halaman dan coba lagi.';
                    } else if (response.status === 401) {
                        errorMessage = 'Anda tidak memiliki akses. Silakan login kembali.';
                    } else if (response.status === 422) {
                        try {
                            const errorData = await safeParseJSON(response);
                            errorMessage = errorData.message || 'Data yang dikirim tidak valid.';
                            if (errorData.errors) {
                                const firstError = Object.values(errorData.errors)[0];
                                errorMessage += '\n' + firstError[0];
                            }
                        } catch (e) {
                            errorMessage = 'Data yang dikirim tidak valid.';
                        }
                    } else {
                        try {
                            const errorData = await safeParseJSON(response);
                            errorMessage = errorData.message || errorMessage;
                        } catch (e) {
                            console.warn('Could not parse error response:', e);
                            errorMessage = `HTTP ${response.status}: ${response.statusText}`;
                        }
                    }
                    throw new Error(errorMessage);
                }
            } catch (error) {
                console.error('Error creating appointment:', error);
                alert('Terjadi kesalahan: ' + error.message);
            }
        }

        // Nearest Collectors Functions
        async function loadNearestCollectors() {
            try {
                const token = getToken();
                if (!token) {
                    alert('Anda harus login terlebih dahulu');
                    window.location.href = '/login';
                    return;
                }

                // First test debug endpoint to check auth and role
                console.log('Testing debug endpoint...');
                const debugResponse = await fetch('/api/collectors/debug', {
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json'
                    }
                });

                if (debugResponse.ok) {
                    const debugData = await safeParseJSON(debugResponse);
                    console.log('Debug data:', debugData);
                    
                    if (!debugData.authenticated) {
                        alert('Authentication failed. Please log in again.');
                        window.location.href = '/login';
                        return;
                    }
                    
                    if (!debugData.is_pemilik_tambak && !debugData.is_admin) {
                        document.getElementById('nearestCollectorsContainer').innerHTML = `
                            <div class="empty-state">
                                <i class="fas fa-lock"></i>
                                <h3>Akses Terbatas</h3>
                                <p>Fitur ini hanya tersedia untuk pemilik tambak. Role Anda: ${debugData.user_role || 'Unknown'}</p>
                            </div>
                        `;
                        return;
                    }
                    
                    if (!debugData.has_coordinates) {
                        document.getElementById('nearestCollectorsContainer').innerHTML = `
                            <div class="empty-state">
                                <i class="fas fa-map-marker-alt"></i>
                                <h3>Lokasi Diperlukan</h3>
                                <p>Untuk menggunakan fitur pencarian pengepul terdekat, silakan update lokasi Anda terlebih dahulu.</p>
                                <button class="btn btn-primary" onclick="getCurrentLocation()">
                                    <i class="fas fa-location-arrow"></i> Update Lokasi
                                </button>
                            </div>
                        `;
                        return;
                    }
                }

                // Build query parameters
                const params = new URLSearchParams();
                
                const maxDistance = document.getElementById('maxDistanceFilter').value;
                if (maxDistance) params.append('max_distance', maxDistance);
                
                const fishType = document.getElementById('nearestFishTypeFilter').value;
                if (fishType) params.append('fish_type', fishType);
                
                const minRate = document.getElementById('minRateFilter').value;
                if (minRate) params.append('min_rate', minRate);
                
                const maxRate = document.getElementById('maxRateFilter').value;
                if (maxRate) params.append('max_rate', maxRate);

                console.log('Calling nearest collectors endpoint...');
                const response = await fetch(`/api/collectors/nearest?${params}`, {
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json'
                    }
                });

                const container = document.getElementById('nearestCollectorsContainer');

                if (response.ok) {
                    const result = await safeParseJSON(response);
                    console.log('Nearest collectors response:', result);
                    nearestCollectors = result.data.data || [];
                    userLocation = result.data.user_location;
                    
                    displayNearestCollectors(nearestCollectors);
                    updateLocationInfo(userLocation, result.data.search_radius);
                } else {
                    const errorData = await safeParseJSON(response);
                    console.error('API Error:', response.status, errorData);
                    
                    if (response.status === 403) {
                        container.innerHTML = `
                            <div class="empty-state">
                                <i class="fas fa-lock"></i>
                                <h3>Akses Terbatas</h3>
                                <p>Fitur ini hanya tersedia untuk pemilik tambak. Silakan daftar sebagai pemilik tambak untuk menggunakan fitur pencarian pengepul terdekat.</p>
                            </div>
                        `;
                    } else if (response.status === 400) {
                        container.innerHTML = `
                            <div class="empty-state">
                                <i class="fas fa-map-marker-alt"></i>
                                <h3>Lokasi Diperlukan</h3>
                                <p>Untuk menggunakan fitur pencarian pengepul terdekat, silakan update lokasi Anda terlebih dahulu.</p>
                                <button class="btn btn-primary" onclick="getCurrentLocation()">
                                    <i class="fas fa-location-arrow"></i> Update Lokasi
                                </button>
                            </div>
                        `;
                    } else if (response.status === 404) {
                        container.innerHTML = `
                            <div class="empty-state">
                                <i class="fas fa-search"></i>
                                <h3>Endpoint Tidak Ditemukan</h3>
                                <p>Fitur pencarian pengepul terdekat belum tersedia. Silakan gunakan tab "Cari Pengepul" untuk melihat semua pengepul.</p>
                                <button class="btn btn-secondary" onclick="switchTab('collectors')">
                                    <i class="fas fa-truck"></i> Lihat Semua Pengepul
                                </button>
                            </div>
                        `;
                    } else {
                        container.innerHTML = `
                            <div class="empty-state">
                                <i class="fas fa-exclamation-triangle"></i>
                                <h3>Gagal Memuat Data</h3>
                                <p>Status: ${response.status}<br>
                                Error: ${errorData.message || 'Terjadi kesalahan saat memuat pengepul terdekat'}</p>
                                <button class="btn btn-secondary" onclick="loadNearestCollectors()">
                                    <i class="fas fa-refresh"></i> Coba Lagi
                                </button>
                                <button class="btn btn-info" onclick="console.log('Debug info:', {status: ${response.status}, error: errorData}); alert('Check browser console for details')">
                                    <i class="fas fa-bug"></i> Debug Info
                                </button>
                            </div>
                        `;
                    }
                }
            } catch (error) {
                console.error('Error loading nearest collectors:', error);
                document.getElementById('nearestCollectorsContainer').innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-exclamation-triangle"></i>
                        <h3>Terjadi Kesalahan</h3>
                        <p>Gagal memuat pengepul terdekat. Silakan coba lagi.</p>
                        <button class="btn btn-secondary" onclick="loadNearestCollectors()">
                            <i class="fas fa-refresh"></i> Coba Lagi
                        </button>
                    </div>
                `;
            }
        }

        // This function is already defined above as displayNearestCollectors(collectorsData) 
        // Removed duplicate function to avoid conflicts

        function updateLocationInfo(coordinates, searchRadius) {
            const locationInfo = document.getElementById('locationInfo');
            const locationText = document.getElementById('userLocationText');
            
            if (coordinates && coordinates.latitude && coordinates.longitude) {
                locationText.innerHTML = `
                    <strong>Koordinat:</strong> ${coordinates.latitude.toFixed(6)}, ${coordinates.longitude.toFixed(6)}<br>
                    <strong>Radius Pencarian:</strong> ${searchRadius}
                `;
                locationInfo.style.display = 'block';
            } else {
                locationInfo.style.display = 'none';
            }
        }

        async function getCurrentLocation() {
            if (!navigator.geolocation) {
                alert('Geolocation tidak didukung oleh browser Anda');
                return;
            }

            // Show loading indicator
            const loadingBtn = event?.target;
            if (loadingBtn) {
                loadingBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengambil Lokasi...';
                loadingBtn.disabled = true;
            }

            const options = {
                enableHighAccuracy: true,
                timeout: 15000, // Increased timeout
                maximumAge: 60000 // 1 minute cache
            };

            navigator.geolocation.getCurrentPosition(
                async (position) => {
                    const { latitude, longitude, accuracy } = position.coords;
                    
                    console.log('Location obtained:', { latitude, longitude, accuracy });
                    
                    try {
                        const token = getToken();
                        if (!token) {
                            alert('Anda harus login terlebih dahulu');
                            return;
                        }

                        const response = await fetch('/api/user', {
                            method: 'PUT',
                            headers: {
                                'Authorization': 'Bearer ' + token,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                latitude: latitude,
                                longitude: longitude
                            })
                        });

                        if (response.ok) {
                            userLocation = { latitude, longitude };
                            console.log('User location updated successfully:', userLocation);
                            
                            // Show success message with coordinates
                            alert(`Lokasi berhasil diperbarui!\nKoordinat: ${latitude.toFixed(6)}, ${longitude.toFixed(6)}\nAkurasi: ${accuracy ? Math.round(accuracy) + 'm' : 'Unknown'}\n\nSekarang Anda dapat mencari pengepul terdekat.`);
                            
                            // Automatically load nearest collectors
                            await loadNearestCollectors();
                        } else {
                            const errorData = await safeParseJSON(response);
                            console.error('Failed to update location:', errorData);
                            alert('Gagal memperbarui lokasi: ' + (errorData.message || 'Terjadi kesalahan server'));
                        }
                    } catch (error) {
                        console.error('Error updating location:', error);
                        alert('Terjadi kesalahan saat memperbarui lokasi: ' + error.message);
                    } finally {
                        // Reset button
                        if (loadingBtn) {
                            loadingBtn.innerHTML = '<i class="fas fa-location-arrow"></i> Update Lokasi';
                            loadingBtn.disabled = false;
                        }
                    }
                },
                (error) => {
                    console.error('Geolocation error:', error);
                    let errorMessage = 'Gagal mendapatkan lokasi: ';
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            errorMessage += 'Akses lokasi ditolak. Silakan izinkan akses lokasi di browser Anda dan coba lagi.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMessage += 'Informasi lokasi tidak tersedia. Pastikan GPS aktif dan sinyal baik.';
                            break;
                        case error.TIMEOUT:
                            errorMessage += 'Waktu permintaan lokasi habis. Coba lagi dengan koneksi yang lebih baik.';
                            break;
                        default:
                            errorMessage += 'Terjadi kesalahan yang tidak diketahui.';
                            break;
                    }
                    alert(errorMessage);
                    
                    // Reset button
                    if (loadingBtn) {
                        loadingBtn.innerHTML = '<i class="fas fa-location-arrow"></i> Update Lokasi';
                        loadingBtn.disabled = false;
                    }
                },
                options
            );
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const appointmentModal = document.getElementById('appointmentModal');
            const detailModal = document.getElementById('detailModal');
            const collectorModal = document.getElementById('collectorRegistrationModal');
            
            if (event.target === appointmentModal) {
                closeAppointmentModal();
            }
            
            if (event.target === detailModal) {
                closeDetailModal();
            }
            
            if (event.target === collectorModal) {
                closeCollectorRegistrationModal();
            }
        }

        // Collector Registration Modal Functions
        function showCollectorRegistrationModal() {
            document.getElementById('collectorRegistrationModal').style.display = 'block';
            // Focus pada modal untuk keyboard navigation
            document.getElementById('collectorRegistrationModal').focus();
        }

        function closeCollectorRegistrationModal() {
            document.getElementById('collectorRegistrationModal').style.display = 'none';
        }

        function proceedToCollectorRegistration() {
            // Close modal and redirect to registration page
            closeCollectorRegistrationModal();
            window.location.href = '/collectors/create';
        }

        // Fish Farm Registration Modal Functions
        function showFishFarmRegistrationModal() {
            document.getElementById('fishFarmRegistrationModal').style.display = 'block';
            // Focus pada modal untuk keyboard navigation
            document.getElementById('fishFarmRegistrationModal').focus();
        }

        function closeFishFarmRegistrationModal() {
            document.getElementById('fishFarmRegistrationModal').style.display = 'none';
        }

        function proceedToFishFarmRegistration() {
            // Close modal and redirect to registration page
            closeFishFarmRegistrationModal();
            window.location.href = '/fish-farms/create';
        }

        // Handle ESC key to close modals
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const collectorModal = document.getElementById('collectorRegistrationModal');
                const fishFarmModal = document.getElementById('fishFarmRegistrationModal');
                const appointmentModal = document.getElementById('appointmentModal');
                const detailModal = document.getElementById('detailModal');
                
                if (collectorModal && collectorModal.style.display === 'block') {
                    closeCollectorRegistrationModal();
                } else if (fishFarmModal && fishFarmModal.style.display === 'block') {
                    closeFishFarmRegistrationModal();
                } else if (appointmentModal && appointmentModal.style.display === 'block') {
                    closeAppointmentModal();
                } else if (detailModal && detailModal.style.display === 'block') {
                    closeDetailModal();
                }
            }
        });

        // Modal functions for collector details
        let currentCollectorId = null;

        function showCollectorDetail(collectorId) {
            currentCollectorId = collectorId;
            // Find collector from multiple sources
            const collector = allCollectorsData.find(c => c.id === collectorId) || 
                            nearestCollectors.find(c => c.id === collectorId) || 
                            collectors.find(c => c.id === collectorId);
            
            if (!collector) {
                alert('Data pengepul tidak ditemukan');
                return;
            }
            
            console.log('Showing collector detail:', collector);

            // Populate modal header
            document.getElementById('collectorDetailName').innerHTML = `
                <i class="fas fa-truck"></i> 
                ${collector.nama_usaha || collector.nama || 'Detail Pengepul'}
            `;
            
            const detailContent = document.getElementById('collectorDetailContent');
            detailContent.innerHTML = `
                <div class="collector-detail-container">
                    <!-- Collector Photo Section -->
                    ${collector.foto ? `
                    <div class="detail-section photo-section">
                        <div class="collector-photo">
                            <img src="/storage/${collector.foto}" alt="${collector.nama_usaha || 'Pengepul'}" />
                        </div>
                    </div>
                    ` : ''}
                    
                    <!-- Basic Information -->
                    <div class="detail-section">
                        <h4><i class="fas fa-info-circle"></i> Informasi Dasar</h4>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <span class="detail-label">Nama Usaha:</span>
                                <span class="detail-value">${collector.nama_usaha || collector.nama || 'Tidak tersedia'}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Pemilik:</span>
                                <span class="detail-value">${collector.user?.name || 'Tidak tersedia'}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Status:</span>
                                <span class="status-badge status-${collector.status || 'aktif'}">${collector.status === 'aktif' ? 'AKTIF' : 'TIDAK AKTIF'}</span>
                            </div>
                            ${collector.distance ? `
                            <div class="detail-item">
                                <span class="detail-label">Jarak dari Anda:</span>
                                <span class="distance-badge large">${collector.distance_formatted || collector.distance.toFixed(1) + ' km'}</span>
                            </div>
                            ` : ''}
                        </div>
                    </div>
                    
                    <!-- Contact Information -->
                    <div class="detail-section">
                        <h4><i class="fas fa-phone"></i> Informasi Kontak</h4>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <span class="detail-label">Nomor Telepon:</span>
                                <span class="detail-value contact-info">
                                    ${collector.no_telepon || 'Tidak tersedia'}
                                    ${collector.no_telepon ? `<button class="btn-small btn-success" onclick="contactCollector('${collector.no_telepon}', '${collector.nama_usaha || 'Pengepul'}')"><i class="fab fa-whatsapp"></i> WhatsApp</button>` : ''}
                                </span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Email:</span>
                                <span class="detail-value">${collector.email || collector.user?.email || 'Tidak tersedia'}</span>
                            </div>
                            <div class="detail-item full-width">
                                <span class="detail-label">Alamat Lengkap:</span>
                                <span class="detail-value address">
                                    ${collector.alamat || 'Alamat tidak tersedia'}
                                    ${collector.alamat ? `<button class="btn-small btn-info" onclick="navigateToCollectorLocation(${collector.id})"><i class="fas fa-directions"></i> Menuju Lokasi</button>` : ''}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Business Information -->
                    <div class="detail-section">
                        <h4><i class="fas fa-business-time"></i> Informasi Bisnis</h4>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <span class="detail-label">Harga per KG:</span>
                                <span class="detail-value price-highlight">
                                    Rp ${(collector.rate_per_kg || collector.rate_harga_per_kg) ? 
                                        parseInt(collector.rate_per_kg || collector.rate_harga_per_kg).toLocaleString('id-ID') : 
                                        'Hubungi untuk negosiasi'
                                    }
                                </span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Kapasitas Maksimal:</span>
                                <span class="detail-value">
                                    ${(collector.kapasitas_maximum || collector.kapasitas_maksimal) ? 
                                        (collector.kapasitas_maximum || collector.kapasitas_maksimal) + ' kg/hari' : 
                                        'Tidak dibatasi'
                                    }
                                </span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Jam Operasional:</span>
                                <span class="detail-value">${collector.jam_operasional || 'Hubungi langsung untuk konfirmasi'}</span>
                            </div>
                            <div class="detail-item full-width">
                                <span class="detail-label">Jenis Ikan yang Diterima:</span>
                                <div class="fish-types">
                                    ${collector.jenis_ikan_diterima ? 
                                        (Array.isArray(collector.jenis_ikan_diterima) ? 
                                            collector.jenis_ikan_diterima.map(fish => `<span class="fish-tag">${fish}</span>`).join('') :
                                            `<span class="fish-tag">${collector.jenis_ikan_diterima}</span>`
                                        ) : 
                                        '<span class="fish-tag">Semua jenis ikan</span>'
                                    }
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    ${collector.deskripsi ? `
                    <div class="detail-section">
                        <h4><i class="fas fa-file-text"></i> Deskripsi & Informasi Tambahan</h4>
                        <div class="description-content">
                            <p>${collector.deskripsi}</p>
                        </div>
                    </div>
                    ` : ''}
                    
                    <!-- Coordinates Information (if available) -->
                    ${collector.collector_coordinates ? `
                    <div class="detail-section">
                        <h4><i class="fas fa-map-marked-alt"></i> Informasi Lokasi</h4>
                        <div class="coordinates-info">
                            <p><strong>Koordinat:</strong> ${collector.collector_coordinates.lat.toFixed(6)}, ${collector.collector_coordinates.lng.toFixed(6)}</p>
                            <div class="location-actions">
                                <button class="btn btn-info" onclick="openLocationInMaps(${collector.collector_coordinates.lat}, ${collector.collector_coordinates.lng})">
                                    <i class="fas fa-external-link-alt"></i> Buka di Google Maps
                                </button>
                                <button class="btn btn-secondary" onclick="copyCoordinates('${collector.collector_coordinates.lat}, ${collector.collector_coordinates.lng}')">
                                    <i class="fas fa-copy"></i> Salin Koordinat
                                </button>
                            </div>
                        </div>
                    </div>
                    ` : ''}
                </div>
            `;

            // Update appointment button visibility
            const appointmentBtn = document.getElementById('appointmentBtn');
            if (appointmentBtn) {
                if (nearestCollectors.find(c => c.id === collectorId)) {
                    appointmentBtn.style.display = 'inline-block';
                    appointmentBtn.onclick = () => showAppointmentForm(collectorId);
                } else {
                    appointmentBtn.style.display = 'none';
                }
            }

            // Show modal
            document.getElementById('collectorDetailModal').style.display = 'block';
        }

        function openLocationInMaps(lat, lng) {
            const googleMapsUrl = `https://www.google.com/maps?q=${lat},${lng}&hl=id`;
            window.open(googleMapsUrl, '_blank');
        }

        function copyCoordinates(coordinates) {
            navigator.clipboard.writeText(coordinates).then(() => {
                alert('Koordinat berhasil disalin ke clipboard!');
            }).catch(() => {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = coordinates;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                alert('Koordinat berhasil disalin ke clipboard!');
            });
        }

        function closeCollectorDetailModal() {
            document.getElementById('collectorDetailModal').style.display = 'none';
            currentCollectorId = null;
        }

        function showAppointmentForm() {
            if (!currentCollectorId) {
                alert('Pengepul tidak ditemukan');
                return;
            }

            closeCollectorDetailModal();
            
            // Populate fish farm select
            const fishFarmSelect = document.getElementById('fish_farm_select');
            fishFarmSelect.innerHTML = '<option value="">-- Pilih Tambak --</option>';
            
            fishFarms.forEach(farm => {
                const option = document.createElement('option');
                option.value = farm.id;
                option.textContent = farm.nama;
                fishFarmSelect.appendChild(option);
            });

            // Set collector ID in hidden field
            document.getElementById('appointment_collector_id').value = currentCollectorId;

            // Set minimum date to tomorrow
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            document.getElementById('appointment_date').min = tomorrow.toISOString().split('T')[0];

            // Show appointment modal
            document.getElementById('appointmentModal').style.display = 'block';
        }

        function closeAppointmentModal() {
            document.getElementById('appointmentModal').style.display = 'none';
            document.getElementById('appointmentForm').reset();
        }

        // Distance calculation function
        function calculateDistance(lat1, lon1, lat2, lon2) {
            const R = 6371; // Earth's radius in kilometers
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                    Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                    Math.sin(dLon/2) * Math.sin(dLon/2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            return R * c;
        }

        // Update makeAppointment function to use the new modal
        function makeAppointment(collectorId) {
            currentCollectorId = collectorId;
            showAppointmentForm();
        }
    </script>

    <!-- Detail Modal for Fish Farm and Collector -->
    <div id="detailModal" class="modal">
        <div class="modal-content detail-modal">
            <div class="modal-header">
                <h3 id="detailModalTitle"><i class="fas fa-info-circle"></i> Detail</h3>
                <span class="close" onclick="closeDetailModal()">&times;</span>
            </div>
            
            <div class="modal-body" id="detailModalBody">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>

    <style>
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background-color: white;
            margin: 5% auto;
            border-radius: 15px;
            max-width: 500px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            animation: modalSlideIn 0.3s ease-out;
        }

        .detail-modal {
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
        }

        @keyframes modalSlideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            color: #2c3e50;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .close {
            font-size: 1.5rem;
            font-weight: bold;
            cursor: pointer;
            color: #aaa;
            transition: color 0.3s;
        }

        .close:hover {
            color: #000;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .collector-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            color: white;
        }

        .collector-details h4 {
            margin: 0 0 0.5rem 0;
            font-size: 1.2rem;
        }

        .collector-details p {
            margin: 0;
            opacity: 0.9;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #2c3e50;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.8rem;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 1.5rem;
        }

        .form-actions .btn {
            padding: 0.8rem 1.5rem;
        }

        /* Detail Modal Styles */
        .detail-section {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid #667eea;
        }

        .detail-section h4 {
            color: #667eea;
            margin-bottom: 1rem;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .detail-item {
            background: white;
            padding: 1rem;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }

        .detail-label {
            font-size: 0.85rem;
            color: #6c757d;
            font-weight: 500;
            display: block;
            margin-bottom: 0.5rem;
        }

        .detail-value {
            font-size: 1rem;
            color: #212529;
            font-weight: 600;
        }

        .status-badge-detail {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-aktif { background: #d4edda; color: #155724; }
        .status-nonaktif { background: #f8d7da; color: #721c24; }
        .status-maintenance { background: #fff3cd; color: #856404; }

        .detail-image {
            text-align: center;
            margin: 1rem 0;
        }

        .detail-image img {
            max-width: 100%;
            max-height: 300px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        /* Clickable Card Styles */
        .farm-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .farm-card {
            transition: all 0.3s ease;
        }

        .farm-card:hover .card-header h3 {
            color: #667eea;
        }

        /* Contact Modal Styles */
        .contact-info {
            text-align: center;
            padding: 1rem;
        }

        .contact-info h3 {
            color: #1e40af;
            margin-bottom: 1.5rem;
        }

        .contact-details {
            margin: 1.5rem 0;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #1e40af;
        }

        .contact-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 1.5rem;
        }

        .contact-actions .btn {
            min-width: 120px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        /* Enhanced Collector Card Styles */
        .collector-card.detailed-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
            overflow: hidden;
            position: relative;
        }

        .collector-card.detailed-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(102, 126, 234, 0.15);
            border-color: #667eea;
        }

        .collector-card .ranking-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            z-index: 2;
        }

        .collector-card .card-header {
            padding: 1.5rem;
            background: linear-gradient(135deg, #f8f9fa, #ffffff);
            border-bottom: 1px solid #e9ecef;
            position: relative;
        }

        .collector-card .card-image {
            float: left;
            width: 80px;
            height: 80px;
            margin-right: 1rem;
            border-radius: 12px;
            overflow: hidden;
            background: #f8f9fa;
        }

        .collector-card .default-image {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            font-size: 1.8rem;
        }

        .collector-card .card-info h3 {
            margin: 0 0 0.5rem 0;
            color: #2c3e50;
            font-size: 1.3rem;
            font-weight: 600;
        }

        .collector-card .owner-name {
            color: #6c757d;
            font-size: 0.9rem;
            margin: 0.3rem 0;
        }

        .collector-card .location {
            color: #6c757d;
            font-size: 0.9rem;
            margin: 0.3rem 0;
        }

        .collector-card .distance-info {
            margin-top: 0.8rem;
        }

        .collector-card .status-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .collector-card .status-badge.active {
            background: #d4edda;
            color: #155724;
        }

        .collector-card .status-badge.inactive {
            background: #f8d7da;
            color: #721c24;
        }

        .collector-card .card-body {
            padding: 1.5rem;
        }

        .collector-card .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .collector-card .detail-item.full-width {
            grid-column: 1 / -1;
        }

        .collector-card .detail-item {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            border-left: 3px solid #667eea;
        }

        .collector-card .detail-label {
            font-size: 0.8rem;
            color: #6c757d;
            font-weight: 500;
            display: block;
            margin-bottom: 0.3rem;
        }

        .collector-card .detail-value {
            font-size: 0.95rem;
            color: #2c3e50;
            font-weight: 600;
        }

        .collector-card .detail-value.price {
            color: #28a745;
            font-size: 1.05rem;
        }

        .collector-card .detail-value.description {
            font-size: 0.9rem;
            font-weight: 400;
            line-height: 1.4;
        }

        .distance-badge.primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .collector-card .card-actions {
            padding: 1rem 1.5rem;
            background: #f8f9fa;
            border-top: 1px solid #e9ecef;
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .collector-card .card-actions .btn {
            flex: 1;
            min-width: 100px;
            padding: 0.6rem 1rem;
            font-size: 0.85rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        /* Enhanced Modal Styles for Collector Detail */
        .modal-content.modal-large {
            max-width: 900px;
            max-height: 95vh;
        }

        .collector-detail-container {
            max-height: 70vh;
            overflow-y: auto;
            padding-right: 10px;
        }

        .collector-detail-container::-webkit-scrollbar {
            width: 6px;
        }

        .collector-detail-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .collector-detail-container::-webkit-scrollbar-thumb {
            background: #667eea;
            border-radius: 10px;
        }

        .photo-section {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .collector-photo img {
            max-width: 300px;
            max-height: 200px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        }

        .contact-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .address {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .btn-small {
            padding: 0.3rem 0.6rem;
            font-size: 0.75rem;
            border-radius: 4px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            margin-left: 0.5rem;
        }

        .price-highlight {
            color: #28a745;
            font-size: 1.1rem;
            font-weight: 700;
        }

        .fish-types {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .fish-tag {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .distance-badge.large {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-weight: 600;
            font-size: 1rem;
        }

        .coordinates-info {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            border-left: 4px solid #17a2b8;
        }

        .location-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
            flex-wrap: wrap;
        }

        .description-content {
            background: white;
            padding: 1rem;
            border-radius: 8px;
            border-left: 4px solid #6f42c1;
            line-height: 1.6;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .collector-card .detail-grid {
                grid-template-columns: 1fr;
            }
            
            .collector-card .card-actions {
                flex-direction: column;
            }
            
            .collector-card .card-actions .btn {
                flex: none;
            }
            
            .modal-content.modal-large {
                max-width: 95%;
                margin: 2% auto;
            }
        }
    </style>

    <script>
        // Function to show collector detail in modal (updated version)
        function showCollectorDetail(collectorId) {
            currentCollectorId = collectorId;
            // Find collector from multiple sources
            const collector = allCollectorsData.find(c => c.id === collectorId) || 
                            nearestCollectors.find(c => c.id === collectorId) || 
                            collectors.find(c => c.id === collectorId);
            
            if (!collector) {
                alert('Data pengepul tidak ditemukan');
                return;
            }
            
            console.log('Showing collector detail:', collector);

            // Populate modal header
            document.getElementById('collectorDetailName').innerHTML = `
                <i class="fas fa-truck"></i> 
                ${collector.nama_usaha || collector.nama || 'Detail Pengepul'}
            `;
            
            const detailContent = document.getElementById('collectorDetailContent');
            detailContent.innerHTML = `
                <div class="collector-detail-container">
                    <!-- Collector Photo Section -->
                    ${collector.foto ? `
                    <div class="detail-section photo-section">
                        <div class="collector-photo">
                            <img src="/storage/${collector.foto}" alt="${collector.nama_usaha || 'Pengepul'}" />
                        </div>
                    </div>
                    ` : ''}
                    
                    <!-- Basic Information -->
                    <div class="detail-section">
                        <h4><i class="fas fa-info-circle"></i> Informasi Dasar</h4>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <span class="detail-label">Nama Usaha:</span>
                                <span class="detail-value">${collector.nama_usaha || collector.nama || 'Tidak tersedia'}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Pemilik:</span>
                                <span class="detail-value">${collector.user?.name || 'Tidak tersedia'}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Status:</span>
                                <span class="status-badge status-${collector.status || 'aktif'}">${collector.status === 'aktif' ? 'AKTIF' : 'TIDAK AKTIF'}</span>
                            </div>
                            ${collector.distance ? `
                            <div class="detail-item">
                                <span class="detail-label">Jarak dari Anda:</span>
                                <span class="distance-badge large">${collector.distance_formatted || collector.distance.toFixed(1) + ' km'}</span>
                            </div>
                            ` : ''}
                        </div>
                    </div>
                    
                    <!-- Contact Information -->
                    <div class="detail-section">
                        <h4><i class="fas fa-phone"></i> Informasi Kontak</h4>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <span class="detail-label">Nomor Telepon:</span>
                                <span class="detail-value contact-info">
                                    ${collector.no_telepon || 'Tidak tersedia'}
                                    ${collector.no_telepon ? `<button class="btn-small btn-success" onclick="contactCollectorFixed('${collector.no_telepon}', '${(collector.nama_usaha || 'Pengepul').replace(/'/g, '\\\'')}')" style="margin-left: 10px;"><i class="fab fa-whatsapp"></i> WhatsApp</button>` : ''}
                                </span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Email:</span>
                                <span class="detail-value">${collector.email || collector.user?.email || 'Tidak tersedia'}</span>
                            </div>
                            <div class="detail-item full-width">
                                <span class="detail-label">Alamat Lengkap:</span>
                                <span class="detail-value address">
                                    ${collector.alamat || 'Alamat tidak tersedia'}
                                    ${collector.alamat ? `<button class="btn-small btn-info" onclick="navigateToCollectorLocation(${collector.id})" style="margin-left: 10px;"><i class="fas fa-directions"></i> Menuju Lokasi</button>` : ''}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Business Information -->
                    <div class="detail-section">
                        <h4><i class="fas fa-business-time"></i> Informasi Bisnis</h4>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <span class="detail-label">Harga per KG:</span>
                                <span class="detail-value price-highlight">
                                    Rp ${(collector.rate_per_kg || collector.rate_harga_per_kg) ? 
                                        parseInt(collector.rate_per_kg || collector.rate_harga_per_kg).toLocaleString('id-ID') : 
                                        'Hubungi untuk negosiasi'
                                    }
                                </span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Kapasitas Maksimal:</span>
                                <span class="detail-value">
                                    ${(collector.kapasitas_maximum || collector.kapasitas_maksimal) ? 
                                        (collector.kapasitas_maximum || collector.kapasitas_maksimal) + ' kg/hari' : 
                                        'Tidak dibatasi'
                                    }
                                </span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Jam Operasional:</span>
                                <span class="detail-value">${collector.jam_operasional || 'Hubungi langsung untuk konfirmasi'}</span>
                            </div>
                            <div class="detail-item full-width">
                                <span class="detail-label">Jenis Ikan yang Diterima:</span>
                                <div class="fish-types">
                                    ${collector.jenis_ikan_diterima ? 
                                        (Array.isArray(collector.jenis_ikan_diterima) ? 
                                            collector.jenis_ikan_diterima.map(fish => `<span class="fish-tag">${fish}</span>`).join('') :
                                            `<span class="fish-tag">${collector.jenis_ikan_diterima}</span>`
                                        ) : 
                                        '<span class="fish-tag">Semua jenis ikan</span>'
                                    }
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    ${collector.deskripsi ? `
                    <div class="detail-section">
                        <h4><i class="fas fa-file-text"></i> Deskripsi & Informasi Tambahan</h4>
                        <div class="description-content">
                            <p>${collector.deskripsi}</p>
                        </div>
                    </div>
                    ` : ''}
                    
                    <!-- Coordinates Information (if available) -->
                    ${collector.collector_coordinates || collector.lokasi_koordinat ? `
                    <div class="detail-section">
                        <h4><i class="fas fa-map-marked-alt"></i> Informasi Lokasi</h4>
                        <div class="coordinates-info">
                            ${collector.collector_coordinates ? `
                                <p><strong>Koordinat:</strong> ${collector.collector_coordinates.lat.toFixed(6)}, ${collector.collector_coordinates.lng.toFixed(6)}</p>
                            ` : collector.lokasi_koordinat ? `
                                <p><strong>Koordinat:</strong> ${collector.lokasi_koordinat.lat.toFixed(6)}, ${collector.lokasi_koordinat.lng.toFixed(6)}</p>
                            ` : ''}
                            <div class="location-actions">
                                <button class="btn btn-info" onclick="navigateToCollectorLocation(${collector.id})">
                                    <i class="fas fa-external-link-alt"></i> Buka di Google Maps
                                </button>
                                ${collector.collector_coordinates ? `
                                    <button class="btn btn-secondary" onclick="copyCoordinates('${collector.collector_coordinates.lat}, ${collector.collector_coordinates.lng}')">
                                        <i class="fas fa-copy"></i> Salin Koordinat
                                    </button>
                                ` : collector.lokasi_koordinat ? `
                                    <button class="btn btn-secondary" onclick="copyCoordinates('${collector.lokasi_koordinat.lat}, ${collector.lokasi_koordinat.lng}')">
                                        <i class="fas fa-copy"></i> Salin Koordinat
                                    </button>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                    ` : ''}
                </div>
            `;

            // Update appointment button visibility
            const appointmentBtn = document.getElementById('appointmentBtn');
            if (appointmentBtn) {
                appointmentBtn.style.display = 'inline-block';
                appointmentBtn.onclick = () => openAppointmentModal(collectorId);
            }

            // Show modal
            document.getElementById('collectorDetailModal').style.display = 'block';
        }
                            </div>
                            <div class="detail-info">
                                <h3>${collector.nama_usaha || collector.nama || 'Nama Usaha Tidak Tersedia'}</h3>
                                <p class="owner"><i class="fas fa-user"></i> ${collector.user?.name || 'Pemilik tidak diketahui'}</p>
                                <div class="status-badge ${collector.status === 'aktif' ? 'active' : 'inactive'}">
                                    ${collector.status === 'aktif' ? 'AKTIF' : 'TIDAK AKTIF'}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="detail-grid">
                        <div class="detail-item">
                            <span class="detail-label"><i class="fas fa-map-marker-alt"></i> Alamat</span>
                            <span class="detail-value">${collector.alamat || 'Alamat tidak tersedia'}</span>
                        </div>
                        
                        <div class="detail-item">
                            <span class="detail-label"><i class="fas fa-route"></i> Jarak</span>
                            <span class="detail-value">${collector.distance_formatted || (collector.distance ? 
                                (collector.distance < 1 ? Math.round(collector.distance * 1000) + ' m' : collector.distance.toFixed(1) + ' km') 
                                : 'Menghitung...')}</span>
                        </div>

                        <div class="detail-item">
                            <span class="detail-label"><i class="fas fa-phone"></i> Kontak</span>
                            <span class="detail-value">${collector.no_telepon || 'Tidak tersedia'}</span>
                        </div>
                        
                        <div class="detail-item">
                            <span class="detail-label"><i class="fas fa-money-bill-wave"></i> Rate/KG</span>
                            <span class="detail-value price">Rp ${(collector.rate_per_kg || collector.rate_harga_per_kg) ? 
                                parseInt(collector.rate_per_kg || collector.rate_harga_per_kg).toLocaleString('id-ID') : 'Nego'}</span>
                        </div>
                        
                        <div class="detail-item">
                            <span class="detail-label"><i class="fas fa-weight-hanging"></i> Kapasitas</span>
                            <span class="detail-value">${(collector.kapasitas_maximum || collector.kapasitas_maksimal) ? 
                                (collector.kapasitas_maximum || collector.kapasitas_maksimal) + ' kg' : 'Tidak dibatasi'}</span>
                        </div>
                        
                        <div class="detail-item">
                            <span class="detail-label"><i class="fas fa-clock"></i> Jam Operasional</span>
                            <span class="detail-value">${collector.jam_operasional || 'Hubungi langsung'}</span>
                        </div>
                        
                        <div class="detail-item full-width">
                            <span class="detail-label"><i class="fas fa-fish"></i> Jenis Ikan Diterima</span>
                            <span class="detail-value">${
                                collector.jenis_ikan_diterima 
                                    ? (Array.isArray(collector.jenis_ikan_diterima) 
                                        ? collector.jenis_ikan_diterima.join(', ') 
                                        : collector.jenis_ikan_diterima)
                                    : 'Semua jenis ikan'
                            }</span>
                        </div>
                        
                        ${collector.deskripsi ? `
                        <div class="detail-item full-width">
                            <span class="detail-label"><i class="fas fa-info-circle"></i> Deskripsi</span>
                            <span class="detail-value description">${collector.deskripsi}</span>
                        </div>
                        ` : ''}
                    </div>

                    <div class="detail-actions">
                        <button class="btn btn-success" onclick="contactCollectorFixed('${collector.no_telepon || ''}', '${(collector.nama_usaha || 'Pengepul').replace(/'/g, '\\\'')}')" title="Hubungi via WhatsApp">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                        </button>
                        <button class="btn btn-info" onclick="navigateToCollectorLocation(${collector.id})" title="Buka lokasi di Maps">
                            <i class="fas fa-directions"></i> Menuju Lokasi
                        </button>
                        <button class="btn btn-primary" onclick="showAppointmentFormFixed(${collector.id})" title="Buat janji penjemputan">
                            <i class="fas fa-calendar-plus"></i> Buat Janji
                        </button>
                    </div>
                </div>
            `;

            document.getElementById('collectorDetailContent').innerHTML = detailContent;
            document.getElementById('collectorDetailModal').style.display = 'block';
        }

        function openLocationInMaps(lat, lng) {
            const googleMapsUrl = `https://www.google.com/maps?q=${lat},${lng}&hl=id`;
            window.open(googleMapsUrl, '_blank');
        }

        function copyCoordinates(coordinates) {
            if (navigator.clipboard) {
                navigator.clipboard.writeText(coordinates).then(() => {
                    alert('Koordinat berhasil disalin ke clipboard: ' + coordinates);
                }).catch(err => {
                    console.error('Failed to copy: ', err);
                    alert('Gagal menyalin koordinat');
                });
            } else {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = coordinates;
                document.body.appendChild(textArea);
                textArea.select();
                try {
                    document.execCommand('copy');
                    alert('Koordinat berhasil disalin ke clipboard: ' + coordinates);
                } catch (err) {
                    alert('Gagal menyalin koordinat');
                }
                document.body.removeChild(textArea);
            }
        }

        // Function to close collector detail modal
        function closeCollectorDetailModal() {
            document.getElementById('collectorDetailModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('collectorDetailModal');
            if (event.target === modal) {
                closeCollectorDetailModal();
            }
            
            const appointmentModal = document.getElementById('appointmentModal');
            if (event.target === appointmentModal) {
                closeAppointmentModal();
            }
        }
    </script>
</body>
</html>
