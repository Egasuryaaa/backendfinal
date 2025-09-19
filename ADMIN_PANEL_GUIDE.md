# Panduan Admin Filament IwakMart

## Apa yang Sudah Diperbaiki

### 1. Resource Visibility

-   ✅ **AppointmentResource**: Sekarang tersedia di admin panel dengan navigation group "Manajemen Janji Temu"
-   ✅ **ProductResource**: Sekarang tersedia di admin panel dengan navigation group "Manajemen Produk"
-   ✅ Semua resource sekarang memiliki navigation group yang konsisten

### 2. Navigation Structure

Struktur navigasi admin sekarang terorganisir dalam grup:

-   **Manajemen User**: User management
-   **Manajemen Tambak**: Fish Farm management
-   **Manajemen Usaha**: Collector management
-   **Manajemen Produk**: Product & Category management
-   **Manajemen Pesanan**: Order management
-   **Manajemen Janji Temu**: Appointment management
-   **Pelanggan & Ulasan**: Review management

### 3. Access Control

-   ✅ Perbaikan method `canViewAny()` di AppointmentResource dan ProductResource
-   ✅ Admin sekarang dapat mengakses semua resource tanpa batasan
-   ✅ Custom login page memvalidasi akses admin

## Cara Mengakses Admin Panel

### 1. URL

```
http://localhost:8000/admin/login
```

### 2. Kredensial Admin

Gunakan salah satu dari kredensial berikut:

**Option 1 - Default Admin (dari AdminSeeder):**

```
Email: admin@iwakmart.com
Password: admin123
```

**Option 2 - Buat user admin manual:**

```sql
-- Jalankan di database
INSERT INTO users (name, email, password, role, active, email_verified_at, created_at, updated_at)
VALUES ('Super Admin', 'admin@example.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1, NOW(), NOW(), NOW());
-- Password di atas adalah hash untuk 'password'
```

### 3. Resource yang Tersedia

Setelah login sebagai admin, Anda dapat mengelola:

-   👥 **Users**: Manajemen semua pengguna sistem
-   🏢 **Fish Farms**: Manajemen tambak ikan
-   🏪 **Collectors**: Manajemen pengepul
-   🛍️ **Products**: Manajemen produk dan kategori
-   📦 **Orders**: Manajemen pesanan
-   📅 **Appointments**: Manajemen janji penjemputan
-   ⭐ **Reviews**: Manajemen ulasan

## Features Admin Panel

### Dashboard

-   Overview statistik sistem
-   Quick access ke resource utama

### User Management

-   CRUD operations untuk semua user
-   Filter berdasarkan role
-   Status aktivasi user

### Product Management

-   CRUD operations untuk produk
-   Upload multiple images
-   Kategori management
-   Stock tracking

### Appointment Management

-   View semua appointment
-   Update status appointment
-   Filter berdasarkan status dan tanggal
-   Detail lengkap appointment

### Order Management

-   View semua pesanan
-   Update status pesanan
-   Payment tracking

## Troubleshooting

### Jika Resource Tidak Muncul

1. Clear cache:

```bash
php artisan cache:clear
php artisan config:clear
php artisan filament:clear-cached-components
```

2. Pastikan user memiliki role 'admin':

```sql
UPDATE users SET role = 'admin' WHERE email = 'your-email@example.com';
```

3. Restart Laravel server:

```bash
php artisan serve
```

### Jika Login Gagal

1. Pastikan user aktif:

```sql
UPDATE users SET active = 1 WHERE email = 'your-email@example.com';
```

2. Pastikan password benar atau reset:

```sql
-- Hash untuk 'password123'
UPDATE users SET password = '$2y$12$LQv3c1yqBozqgW0FyqBs3enNHMdEpjdSr7fR9Q3aFbXdgJ5cT1HJ6' WHERE email = 'your-email@example.com';
```

## Security Notes

-   Admin panel hanya dapat diakses oleh user dengan role 'admin'
-   Login validation memastikan hanya admin yang dapat masuk
-   Semua action admin dilog untuk audit trail
