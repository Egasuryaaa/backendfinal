<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>IwakMart - Daftarkan Usaha Pengepul</title>
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
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
            border-color: #f5576c;
            background: white;
            box-shadow: 0 0 0 3px rgba(245, 87, 108, 0.1);
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .form-grid-full {
            grid-column: 1 / -1;
        }

        .checkbox-group {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 0.5rem;
        }

        .checkbox-item {
            display: flex;
            align-items: center;
            padding: 0.75rem;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .checkbox-item:hover {
            border-color: #f5576c;
            background: #fdf2f3;
        }

        .checkbox-item input[type="checkbox"] {
            margin-right: 0.5rem;
        }

        .checkbox-item.checked {
            border-color: #f5576c;
            background: #fdf2f3;
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
            border: 2px dashed #f5576c;
            border-radius: 12px;
            background: #fdf2f3;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }

        .file-upload-label:hover {
            background: #fce8ea;
            border-color: #e74c3c;
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
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(245, 87, 108, 0.4);
        }

        .btn-secondary {
            background: #f8f9fa;
            color: #333;
            border: 2px solid #e9ecef;
        }

        .btn-location {
            background: #28a745;
            color: white;
            margin-top: 0.5rem;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 3rem;
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
            color: #f5576c;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .back-button:hover {
            transform: scale(1.1);
            color: #f5576c;
        }

        .alert {
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1rem;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .form-card {
                padding: 2rem 1.5rem;
            }
            
            .coordinate-input {
                grid-template-columns: 1fr;
            }
            
            .checkbox-group {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <a href="{{ route('collectors.index') }}" class="back-button" title="Kembali">
        <i class="fas fa-arrow-left"></i>
    </a>

    <div class="container">
        <div class="form-card">
            <div class="form-header">
                <h1><i class="fas fa-truck"></i> Daftarkan Usaha Pengepul</h1>
                <p>Daftarkan usaha pengepul Anda untuk menerima penjemputan ikan</p>
            </div>

            <div id="alertContainer"></div>

            <form id="collectorForm">
                <div class="form-grid">
                    <!-- Nama Usaha -->
                    <div class="form-group">
                        <label for="nama_usaha">Nama Usaha <span class="required">*</span></label>
                        <input type="text" id="nama_usaha" name="nama_usaha" class="form-control" placeholder="Contoh: CV Pengepul Ikan Sejahtera" required>
                    </div>

                    <!-- Nomor Telepon -->
                    <div class="form-group">
                        <label for="no_telepon">No. Telepon <span class="required">*</span></label>
                        <input type="tel" id="no_telepon" name="no_telepon" class="form-control" placeholder="Contoh: 081234567890" required>
                    </div>

                    <!-- Rate per KG -->
                    <div class="form-group">
                        <label for="rate_per_kg">Harga per Kilogram (Rp) <span class="required">*</span></label>
                        <input type="number" id="rate_per_kg" name="rate_per_kg" class="form-control" placeholder="Contoh: 25000" min="1000" step="100" required>
                    </div>

                    <!-- Kapasitas Maximum -->
                    <div class="form-group">
                        <label for="kapasitas_maximum">Kapasitas Maksimum (kg/hari) <span class="required">*</span></label>
                        <input type="number" id="kapasitas_maximum" name="kapasitas_maximum" class="form-control" placeholder="Contoh: 1000" min="10" step="10" required>
                    </div>

                    <!-- Jam Operasional -->
                    <div class="form-group form-grid-full">
                        <label for="jam_operasional">Jam Operasional <span class="required">*</span></label>
                        <input type="text" id="jam_operasional" name="jam_operasional" class="form-control" placeholder="Contoh: Senin-Sabtu 08:00-16:00" required>
                    </div>

                    <!-- Alamat -->
                    <div class="form-group form-grid-full">
                        <label for="alamat">Alamat Lengkap <span class="required">*</span></label>
                        <textarea id="alamat" name="alamat" class="form-control" rows="3" placeholder="Masukkan alamat lengkap usaha..." required></textarea>
                    </div>

                    <!-- Jenis Ikan yang Diterima -->
                    <div class="form-group form-grid-full">
                        <label>Jenis Ikan yang Diterima <span class="required">*</span></label>
                        <div class="checkbox-group">
                            <div class="checkbox-item">
                                <input type="checkbox" id="ikan_lele" name="jenis_ikan_diterima" value="Lele">
                                <label for="ikan_lele">Lele</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="ikan_nila" name="jenis_ikan_diterima" value="Nila">
                                <label for="ikan_nila">Nila</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="ikan_mujair" name="jenis_ikan_diterima" value="Mujair">
                                <label for="ikan_mujair">Mujair</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="ikan_gurame" name="jenis_ikan_diterima" value="Gurame">
                                <label for="ikan_gurame">Gurame</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="ikan_patin" name="jenis_ikan_diterima" value="Patin">
                                <label for="ikan_patin">Patin</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="ikan_bandeng" name="jenis_ikan_diterima" value="Bandeng">
                                <label for="ikan_bandeng">Bandeng</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="ikan_mas" name="jenis_ikan_diterima" value="Mas">
                                <label for="ikan_mas">Mas</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="ikan_bawal" name="jenis_ikan_diterima" value="Bawal">
                                <label for="ikan_bawal">Bawal</label>
                            </div>
                        </div>
                    </div>

                    <!-- Lokasi Koordinat -->
                    <div class="form-group form-grid-full">
                        <label>Lokasi Koordinat Usaha <span class="required">*</span></label>
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

                    <!-- Foto Usaha -->
                    <div class="form-group form-grid-full">
                        <label for="foto">Foto Usaha</label>
                        <div class="file-upload">
                            <input type="file" id="foto" name="foto" accept="image/*" onchange="previewImage(this)">
                            <label for="foto" class="file-upload-label">
                                <div>
                                    <div style="font-size: 2rem; color: #f5576c; margin-bottom: 0.5rem;">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                    </div>
                                    <div style="color: #f5576c; font-weight: 500;">
                                        Klik untuk upload foto usaha<br>
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
                        <label for="deskripsi">Deskripsi Usaha <span class="required">*</span></label>
                        <textarea id="deskripsi" name="deskripsi" class="form-control" rows="4" placeholder="Ceritakan tentang usaha Anda, fasilitas, pengalaman, kualitas layanan, dll..." required></textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('collectors.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <span class="loading" style="display: none;"><i class="fas fa-spinner fa-spin"></i></span>
                        <span class="btn-text"><i class="fas fa-save"></i> Daftarkan Usaha</span>
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
            setupCheckboxHandlers();
        });

        // Setup checkbox handlers for visual feedback
        function setupCheckboxHandlers() {
            const checkboxItems = document.querySelectorAll('.checkbox-item');
            checkboxItems.forEach(item => {
                const checkbox = item.querySelector('input[type="checkbox"]');
                
                item.addEventListener('click', function(e) {
                    if (e.target.type !== 'checkbox') {
                        checkbox.checked = !checkbox.checked;
                    }
                    item.classList.toggle('checked', checkbox.checked);
                });
                
                checkbox.addEventListener('change', function() {
                    item.classList.toggle('checked', this.checked);
                });
            });
        }

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
                mapTypeId: 'roadmap'
            });

            marker = new google.maps.Marker({
                position: defaultLocation,
                map: map,
                draggable: true,
                title: 'Lokasi Usaha Pengepul'
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
        document.getElementById('collectorForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const loading = document.querySelector('.loading');
            const btnText = document.querySelector('.btn-text');
            const submitBtn = document.querySelector('button[type="submit"]');
            
            // Show loading state
            loading.style.display = 'inline-block';
            btnText.style.display = 'none';
            submitBtn.disabled = true;

            try {
                // Validate at least one fish type is selected
                const selectedFishTypes = Array.from(document.querySelectorAll('input[name="jenis_ikan_diterima"]:checked')).map(cb => cb.value);
                if (selectedFishTypes.length === 0) {
                    throw new Error('Pilih minimal satu jenis ikan yang diterima');
                }

                const formData = new FormData();
                
                // Append all form fields with correct database field names
                formData.append('nama', document.getElementById('nama_usaha').value);
                formData.append('no_telepon', document.getElementById('no_telepon').value);
                formData.append('rate_harga_per_kg', document.getElementById('rate_per_kg').value);
                formData.append('kapasitas_maksimal', document.getElementById('kapasitas_maximum').value);
                
                // Handle jam operasional - split into start and end times
                const jamOperasional = document.getElementById('jam_operasional').value;
                // Try to extract start and end times from input like "08:00-16:00"
                const timeMatch = jamOperasional.match(/(\d{2}:\d{2})\s*-\s*(\d{2}:\d{2})/);
                if (timeMatch) {
                    formData.append('jam_operasional_mulai', timeMatch[1]);
                    formData.append('jam_operasional_selesai', timeMatch[2]);
                } else {
                    // Default fallback
                    formData.append('jam_operasional_mulai', '08:00');
                    formData.append('jam_operasional_selesai', '17:00');
                }
                
                formData.append('alamat', document.getElementById('alamat').value);
                formData.append('deskripsi', document.getElementById('deskripsi').value);
                
                // Append coordinates as comma-separated string
                const latitude = document.getElementById('latitude').value;
                const longitude = document.getElementById('longitude').value;
                if (latitude && longitude) {
                    formData.append('lokasi_koordinat', `${latitude},${longitude}`);
                } else {
                    formData.append('lokasi_koordinat', '');
                }
                
                // Append selected fish types as JSON array
                formData.append('jenis_ikan_diterima', JSON.stringify(selectedFishTypes));
                
                // Append photo if selected
                const photoFile = document.getElementById('foto').files[0];
                if (photoFile) {
                    formData.append('foto', photoFile);
                }

                const response = await fetch('/api/collectors', {
                    method: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + getToken(),
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                });

                const result = await response.json();

                if (response.ok) {
                    showAlert('Usaha pengepul berhasil didaftarkan!', 'success');
                    setTimeout(() => {
                        window.location.href = '/collectors';
                    }, 2000);
                } else {
                    throw new Error(result.message || 'Terjadi kesalahan');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('Gagal mendaftarkan usaha: ' + error.message, 'error');
            } finally {
                // Hide loading state
                loading.style.display = 'none';
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
                    ${message}
                </div>
            `;
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                alertContainer.innerHTML = '';
            }, 5000);
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