# Fitur Location Picker untuk Appointment

## Overview

Fitur ini memungkinkan admin dan user untuk memilih lokasi pertemuan khusus ketika membuat appointment di Filament admin panel. Lokasi akan disimpan dalam format JSON di kolom `meeting_location`.

## Komponen yang Ditambahkan

### 1. Database Schema

-   **Migration**: `2025_08_23_120000_add_location_coordinates_to_tables.php`
-   **Kolom baru**: `meeting_location` (JSON) di tabel `appointments`

### 2. Model Update

-   **File**: `app/Models/Appointment.php`
-   **Perubahan**:
    -   Menambahkan `meeting_location` ke `$fillable`
    -   Menambahkan cast `meeting_location` => `array`

### 3. Custom Filament Component

-   **File**: `app/Filament/Forms/Components/GoogleMapsLocationPicker.php`
-   **View**: `resources/views/filament/forms/components/google-maps-location-picker.blade.php`
-   **Fitur**:
    -   Menggunakan OpenStreetMap dengan Leaflet.js
    -   Search lokasi dengan geocoding
    -   Drag & drop marker
    -   Responsive design
    -   Validasi koordinat

### 4. Filament Resource Update

-   **File**: `app/Filament/Resources/AppointmentResource.php`
-   **Perubahan**:
    -   Menambahkan section "Lokasi Pertemuan" dalam form
    -   Menambahkan kolom indikator lokasi pertemuan di table
    -   Import komponen GoogleMapsLocationPicker

## Cara Penggunaan

### Di Filament Admin Panel:

1. Buka halaman **Janji Temu** > **Create** atau **Edit**
2. Scroll ke section **"Lokasi Pertemuan"**
3. Klik pada peta untuk memilih lokasi
4. Atau gunakan search box untuk mencari lokasi
5. Marker bisa di-drag untuk penyesuaian posisi
6. Lokasi akan tersimpan otomatis dalam format JSON

### Data Format:

```json
{
    "lat": -7.1192,
    "lng": 112.4186,
    "address": "Jl. Contoh, Lamongan, Jawa Timur"
}
```

## Konfigurasi

### Environment Variables (.env):

```bash
# Opsional - untuk geocoding yang lebih akurat
GOOGLE_MAPS_API_KEY=your_api_key_here
```

### Default Settings:

-   **Center**: Lamongan (-7.1192, 112.4186)
-   **Zoom**: 12
-   **Height**: 300px
-   **Searchable**: true

## Custom Configuration:

```php
GoogleMapsLocationPicker::make('meeting_location')
    ->label('Pilih Lokasi Pertemuan')
    ->center(-7.1192, 112.4186) // Custom center
    ->zoom(15)                   // Custom zoom level
    ->searchable(true)           // Enable/disable search
    ->height('400px')            // Custom height
```

## Fitur Peta:

-   ✅ Click to place marker
-   ✅ Drag marker to adjust position
-   ✅ Search locations (OpenStreetMap Nominatim)
-   ✅ Responsive design
-   ✅ Error handling
-   ✅ Multiple tile server fallback
-   ✅ Loading states
-   ✅ Coordinate validation

## Browser Support:

-   Chrome/Edge: ✅
-   Firefox: ✅
-   Safari: ✅
-   Mobile browsers: ✅

## Troubleshooting:

### Peta tidak muncul:

1. Periksa console browser untuk error JavaScript
2. Pastikan koneksi internet stabil
3. Clear browser cache

### Tiles tidak loading:

-   Komponen sudah menggunakan fallback tile server
-   Akan otomatis switch ke server alternatif jika primary gagal

### Search tidak bekerja:

-   Search menggunakan OpenStreetMap Nominatim API
-   Tidak memerlukan API key
-   Periksa koneksi internet

## Migration Command:

Jika migration belum dijalankan:

```bash
php artisan migrate
```

## Testing:

1. Buka Filament admin panel
2. Navigate ke Appointments
3. Create/Edit appointment
4. Test location picker functionality
5. Verify data tersimpan dengan benar

## Future Enhancements:

-   [ ] Integration dengan Google Places API
-   [ ] Offline map support
-   [ ] Custom map styles
-   [ ] Bulk location import
-   [ ] Location history/favorites
