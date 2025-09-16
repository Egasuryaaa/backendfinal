<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>IwakMart - Daftarkan Tambak Ikan</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem 0;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .form-card {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .form-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-header h1 {
            color: #333;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            font-weight: 700;
        }

        .form-header p {
            color: #666;
            font-size: 1.1rem;
        }

        .form-group {
            margin-bottom: 2rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .required {
            color: #e74c3c;
        }

        .form-control {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-control.error {
            border-color: #e74c3c;
            background: #fdf2f2;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .form-grid-full {
            grid-column: 1 / -1;
        }

        .file-upload {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }

        .file-upload input[type=file] {
            position: absolute;
            left: -9999px;
        }

        .file-upload-label {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            border: 2px dashed #667eea;
            border-radius: 12px;
            background: #f8f9ff;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }

        .file-upload-label:hover {
            background: #e6edff;
            border-color: #5a67d8;
        }

        .file-upload-icon {
            font-size: 2rem;
            color: #667eea;
            margin-bottom: 0.5rem;
        }

        .file-upload-text {
            color: #667eea;
            font-weight: 500;
        }

        .coordinate-input {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .map-container {
            height: 300px;
            border-radius: 12px;
            overflow: hidden;
            border: 2px solid #e9ecef;
            margin-top: 1rem;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
        }

        .btn {
            padding: 1rem 2rem;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #f8f9fa;
            color: #333;
            border: 2px solid #e9ecef;
        }

        .btn-secondary:hover {
            background: #e9ecef;
        }

        .btn-location {
            background: #28a745;
            color: white;
            margin-top: 0.5rem;
        }

        .btn-location:hover {
            background: #218838;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 3rem;
        }

        .alert {
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1rem;
            border: 1px solid transparent;
        }

        .alert-success {
            background: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }

        .alert-error {
            background: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
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
            display: none;
        }

        .loading.show {
            display: inline-block;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .form-card {
                padding: 2rem 1.5rem;
            }
            
            .form-header h1 {
                font-size: 2rem;
            }
            
            .coordinate-input {
                grid-template-columns: 1fr;
            }
            
            .form-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <a href="{{ route('fish-farms.index') }}" class="back-button" title="Kembali">
        <i class="fas fa-arrow-left"></i>
    </a>

    <div class="container">
        <div class="form-card">
            <div class="form-header">
                <h1><i class="fas fa-fish"></i> Daftarkan Tambak Ikan</h1>
                <p>Isi informasi tambak Anda untuk terhubung dengan pengepul</p>
            </div>

            <div id="alertContainer"></div>

            <form id="fishFarmForm">
                <div class="form-grid">
                    <!-- Nama Tambak -->
                    <div class="form-group">
                        <label for="nama">Nama Tambak <span class="required">*</span></label>
                        <input type="text" id="nama" name="nama" class="form-control" placeholder="Contoh: Tambak Mina Sejahtera" required>
                    </div>

                    <!-- Jenis Ikan -->
                    <div class="form-group">
                        <label for="jenis_ikan">Jenis Ikan <span class="required">*</span></label>
                        <select id="jenis_ikan" name="jenis_ikan" class="form-control" required>
                            <option value="">Pilih Jenis Ikan</option>
                            <option value="Lele">Lele</option>
                            <option value="Nila">Nila</option>
                            <option value="Mujair">Mujair</option>
                            <option value="Gurame">Gurame</option>
                            <option value="Patin">Patin</option>
                            <option value="Bandeng">Bandeng</option>
                            <option value="Mas">Mas</option>
                            <option value="Bawal">Bawal</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <!-- Banyak Bibit -->
                    <div class="form-group">
                        <label for="banyak_bibit">Jumlah Bibit (ekor) <span class="required">*</span></label>
                        <input type="number" id="banyak_bibit" name="banyak_bibit" class="form-control" placeholder="Contoh: 5000" min="1" required>
                    </div>

                    <!-- Luas Tambak -->
                    <div class="form-group">
                        <label for="luas_tambak">Luas Tambak (m²) <span class="required">*</span></label>
                        <input type="number" id="luas_tambak" name="luas_tambak" class="form-control" placeholder="Contoh: 1000" min="1" step="0.01" required>
                    </div>

                    <!-- No Telepon -->
                    <div class="form-group">
                        <label for="no_telepon">No. Telepon <span class="required">*</span></label>
                        <input type="tel" id="no_telepon" name="no_telepon" class="form-control" placeholder="Contoh: 081234567890" required>
                    </div>

                    <!-- Alamat -->
                    <div class="form-group form-grid-full">
                        <label for="alamat">Alamat Lengkap <span class="required">*</span></label>
                        <textarea id="alamat" name="alamat" class="form-control" rows="3" placeholder="Masukkan alamat lengkap tambak..." required></textarea>
                    </div>

                    <!-- Lokasi Koordinat -->
                    <div class="form-group form-grid-full">
                        <label>Lokasi Koordinat Tambak <span class="required">*</span></label>
                        <div class="coordinate-input">
                            <input type="number" id="latitude" name="latitude" class="form-control" placeholder="Latitude" step="any" required>
                            <input type="number" id="longitude" name="longitude" class="form-control" placeholder="Longitude" step="any" required>
                        </div>
                        <button type="button" class="btn btn-location" onclick="getCurrentLocation()">
                            <i class="fas fa-map-marker-alt"></i> Gunakan Lokasi Saat Ini
                        </button>
                        <div class="map-container" id="mapContainer">
                            <div>
                                <i class="fas fa-map fa-3x" style="margin-bottom: 1rem; opacity: 0.5;"></i>
                                <p>Peta akan muncul setelah koordinat diisi</p>
                            </div>
                        </div>
                    </div>

                    <!-- Foto Tambak -->
                    <div class="form-group form-grid-full">
                        <label for="foto">Foto Tambak</label>
                        <div class="file-upload">
                            <input type="file" id="foto" name="foto" accept="image/*" onchange="previewImage(this)">
                            <label for="foto" class="file-upload-label">
                                <div>
                                    <div class="file-upload-icon">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                    </div>
                                    <div class="file-upload-text">
                                        Klik untuk upload foto tambak<br>
                                        <small>Format: JPG, PNG (Max: 2MB)</small>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <div id="imagePreview" style="margin-top: 1rem; display: none;">
                            <img id="previewImg" style="max-width: 200px; border-radius: 8px;">
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="form-group form-grid-full">
                        <label for="deskripsi">Deskripsi Tambak</label>
                        <textarea id="deskripsi" name="deskripsi" class="form-control" rows="4" placeholder="Ceritakan tentang tambak Anda, sistem budidaya, target panen, dll..."></textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('fish-farms.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <span class="loading"><i class="fas fa-spinner fa-spin"></i></span>
                        <span class="btn-text"><i class="fas fa-save"></i> Daftarkan Tambak</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="/js/auth.js"></script>
    <script>
        let map, marker;

        document.addEventListener('DOMContentLoaded', function() {
            loadGoogleMaps();
        });

        // Load Google Maps API
        function loadGoogleMaps() {
            if (typeof google !== 'undefined' && google.maps) {
                initMap();
                return;
            }

            const script = document.createElement('script');
            script.src = 'https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&callback=initMap';
            script.async = true;
            script.defer = true;
            document.head.appendChild(script);
        }

        // Initialize map
        function initMap() {
            const defaultLocation = { lat: -6.2088, lng: 106.8456 }; // Jakarta
            
            map = new google.maps.Map(document.getElementById('mapContainer'), {
                zoom: 10,
                center: defaultLocation,
                mapTypeId: 'hybrid'
            });

            marker = new google.maps.Marker({
                position: defaultLocation,
                map: map,
                draggable: true,
                title: 'Lokasi Tambak'
            });

            // Update coordinates when marker is dragged
            marker.addListener('dragend', function() {
                const position = marker.getPosition();
                document.getElementById('latitude').value = position.lat();
                document.getElementById('longitude').value = position.lng();
            });

            // Update marker when coordinates are manually entered
            document.getElementById('latitude').addEventListener('input', updateMarkerFromCoordinates);
            document.getElementById('longitude').addEventListener('input', updateMarkerFromCoordinates);
        }

        // Update marker position from coordinate inputs
        function updateMarkerFromCoordinates() {
            const lat = parseFloat(document.getElementById('latitude').value);
            const lng = parseFloat(document.getElementById('longitude').value);

            if (!isNaN(lat) && !isNaN(lng)) {
                const position = { lat: lat, lng: lng };
                marker.setPosition(position);
                map.setCenter(position);
            }
        }

        // Get current location
        function getCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    
                    document.getElementById('latitude').value = lat;
                    document.getElementById('longitude').value = lng;
                    
                    if (marker) {
                        const newPosition = { lat: lat, lng: lng };
                        marker.setPosition(newPosition);
                        map.setCenter(newPosition);
                        map.setZoom(15);
                    }
                    
                    showAlert('Lokasi berhasil diperoleh!', 'success');
                }, function(error) {
                    showAlert('Gagal mendapatkan lokasi: ' + error.message, 'error');
                });
            } else {
                showAlert('Geolocation tidak didukung oleh browser ini.', 'error');
            }
        }

        // Preview uploaded image
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImg').src = e.target.result;
                    document.getElementById('imagePreview').style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Handle form submission
        document.getElementById('fishFarmForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const loading = document.querySelector('.loading');
            const btnText = document.querySelector('.btn-text');
            const submitBtn = document.querySelector('button[type="submit"]');
            
            // Show loading state
            loading.classList.add('show');
            btnText.style.display = 'none';
            submitBtn.disabled = true;

            try {
                const formData = new FormData();
                
                // Append all form fields
                formData.append('nama', document.getElementById('nama').value);
                formData.append('jenis_ikan', document.getElementById('jenis_ikan').value);
                formData.append('banyak_bibit', document.getElementById('banyak_bibit').value);
                formData.append('luas_tambak', document.getElementById('luas_tambak').value);
                formData.append('no_telepon', document.getElementById('no_telepon').value);
                formData.append('alamat', document.getElementById('alamat').value);
                formData.append('deskripsi', document.getElementById('deskripsi').value);
                
                // Append coordinates as separate lat/lng fields
                const latitude = document.getElementById('latitude').value;
                const longitude = document.getElementById('longitude').value;
                if (latitude && longitude) {
                    formData.append('lokasi_koordinat[lat]', latitude);
                    formData.append('lokasi_koordinat[lng]', longitude);
                }
                
                // Append photo if selected
                const photoFile = document.getElementById('foto').files[0];
                if (photoFile) {
                    formData.append('foto', photoFile);
                }

                const response = await fetch('/api/fish-farms', {
                    method: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + getToken(),
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                });

                if (response.ok) {
                    const result = await safeParseJSON(response);
                    showAlert('Tambak berhasil didaftarkan!', 'success');
                    setTimeout(() => {
                        window.location.href = '/fish-farms';
                    }, 2000);
                } else {
                    // Handle validation errors
                    if (response.status === 422) {
                        try {
                            const result = await safeParseJSON(response);
                            if (result.errors) {
                                let errorMessage = 'Validation errors:<br>';
                                for (let field in result.errors) {
                                    errorMessage += `• ${result.errors[field].join(', ')}<br>`;
                                }
                                showAlert(errorMessage, 'error');
                            } else {
                                showAlert(result.message || 'Validation failed', 'error');
                            }
                        } catch (e) {
                            console.warn('Could not parse validation error response:', e);
                            showAlert('Validation failed - please check your input', 'error');
                        }
                    } else {
                        // Handle other errors
                        let errorMessage = 'Terjadi kesalahan';
                        try {
                            const result = await safeParseJSON(response);
                            errorMessage = result.message || errorMessage;
                        } catch (e) {
                            console.warn('Could not parse error response:', e);
                            errorMessage = `HTTP ${response.status}: ${response.statusText}`;
                        }
                        throw new Error(errorMessage);
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('Gagal mendaftarkan tambak: ' + error.message, 'error');
            } finally {
                // Hide loading state
                loading.classList.remove('show');
                btnText.style.display = 'inline-flex';
                submitBtn.disabled = false;
            }
        });

        // Show alert function
        function showAlert(message, type) {
            const alertContainer = document.getElementById('alertContainer');
            const alertClass = type === 'success' ? 'alert-success' : 'alert-error';
            
            alertContainer.innerHTML = `
                <div class="alert ${alertClass}">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                    <span>${message}</span>
                </div>
            `;
            
            // Auto hide after 8 seconds for errors (might be longer), 5 seconds for success
            setTimeout(() => {
                alertContainer.innerHTML = '';
            }, type === 'error' ? 8000 : 5000);
        }

        // Fallback if Google Maps fails to load
        window.initMap = function() {
            try {
                initMap();
            } catch (error) {
                console.log('Google Maps not available, using fallback');
                document.getElementById('mapContainer').innerHTML = `
                    <div style="text-align: center; padding: 2rem;">
                        <i class="fas fa-map fa-2x" style="color: #666; margin-bottom: 1rem;"></i>
                        <p>Masukkan koordinat secara manual atau gunakan tombol "Lokasi Saat Ini"</p>
                    </div>
                `;
            }
        };
    </script>
</body>
</html>