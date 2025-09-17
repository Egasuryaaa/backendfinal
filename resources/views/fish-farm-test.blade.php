<!DOCTYPE html>
<html>
<head>
    <title>Fish Farm Creation Test</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>Fish Farm Creation Test</h1>
    <div id="result"></div>
    
    <script src="/js/auth.js"></script>
    <script>
        async function testFishFarmCreation() {
            try {
                // Test login first
                const loginResponse = await fetch('/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        email: 'budi.farmer@iwakmart.com',
                        password: 'password123'
                    })
                });
                
                const loginResult = await loginResponse.json();
                
                if (!loginResponse.ok) {
                    throw new Error('Login failed: ' + loginResult.message);
                }
                
                console.log('Login successful:', loginResult);
                setAuthToken(loginResult.data.token, false);
                
                // Test fish farm creation
                const formData = new FormData();
                formData.append('nama', 'Test Tambak');
                formData.append('jenis_ikan', 'Lele');
                formData.append('banyak_bibit', '1000');
                formData.append('luas_tambak', '500');
                formData.append('no_telepon', '081234567890');
                formData.append('alamat', 'Jl. Test No. 123');
                formData.append('deskripsi', 'Test tambak description');
                formData.append('lokasi_koordinat[lat]', '-6.2088');
                formData.append('lokasi_koordinat[lng]', '106.8456');
                
                const createResponse = await fetch('/api/fish-farms', {
                    method: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + getToken(),
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                });
                
                const createResult = await createResponse.json();
                
                if (createResponse.ok) {
                    document.getElementById('result').innerHTML = `
                        <h2 style="color: green;">✅ Fish Farm Creation Success!</h2>
                        <p>Fish Farm ID: ${createResult.data.id}</p>
                        <p>Name: ${createResult.data.nama}</p>
                        <p>Fish Type: ${createResult.data.jenis_ikan}</p>
                        <p>Location: ${JSON.stringify(createResult.data.lokasi_koordinat)}</p>
                    `;
                } else {
                    document.getElementById('result').innerHTML = `
                        <h2 style="color: red;">❌ Fish Farm Creation Failed</h2>
                        <p>Status: ${createResponse.status}</p>
                        <p>Error: ${createResult.message || 'Unknown error'}</p>
                        <pre>${JSON.stringify(createResult, null, 2)}</pre>
                    `;
                }
                
            } catch (error) {
                document.getElementById('result').innerHTML = `
                    <h2 style="color: red;">❌ Test Failed</h2>
                    <p>Error: ${error.message}</p>
                `;
                console.error('Test error:', error);
            }
        }
        
        // Run test on page load
        window.onload = testFishFarmCreation;
    </script>
</body>
</html>
