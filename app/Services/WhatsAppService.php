<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\FishFarm;
use App\Models\Collector;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Send WhatsApp notification for fish farm appointment
     */
    public function sendFishFarmAppointmentSummary(Appointment $appointment)
    {
        try {
            if (!$appointment->fishFarm || !$appointment->collector) {
                Log::warning('Missing fish farm or collector data for appointment', ['appointment_id' => $appointment->id]);
                return false;
            }

            $fishFarm = $appointment->fishFarm;
            $collector = $appointment->collector;
            $farmer = $fishFarm->user;
            $collectorOwner = $collector->user;

            // Send to farmer
            $farmerMessage = $this->buildFarmerAppointmentMessage($appointment, $fishFarm, $collector);
            $this->sendMessage($farmer->phone ?? $fishFarm->no_telepon, $farmerMessage);

            // Send to collector
            $collectorMessage = $this->buildCollectorAppointmentMessage($appointment, $fishFarm, $collector);
            $this->sendMessage($collectorOwner->phone ?? $collector->no_telepon, $collectorMessage);

            // Mark as sent
            $appointment->update(['whatsapp_sent' => true]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send WhatsApp appointment summary', [
                'appointment_id' => $appointment->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send appointment status update notification
     */
    public function sendAppointmentStatusUpdate(Appointment $appointment, $oldStatus, $newStatus)
    {
        try {
            if (!$appointment->fishFarm || !$appointment->collector) {
                return false;
            }

            $fishFarm = $appointment->fishFarm;
            $collector = $appointment->collector;
            $farmer = $fishFarm->user;
            $collectorOwner = $collector->user;

            $message = $this->buildStatusUpdateMessage($appointment, $oldStatus, $newStatus);

            // Send to both parties
            $this->sendMessage($farmer->phone ?? $fishFarm->no_telepon, $message);
            $this->sendMessage($collectorOwner->phone ?? $collector->no_telepon, $message);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send WhatsApp status update', [
                'appointment_id' => $appointment->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send appointment completion notification
     */
    public function sendAppointmentCompletion(Appointment $appointment)
    {
        try {
            if (!$appointment->fishFarm || !$appointment->collector) {
                return false;
            }

            $fishFarm = $appointment->fishFarm;
            $collector = $appointment->collector;
            $farmer = $fishFarm->user;
            $collectorOwner = $collector->user;

            $message = $this->buildCompletionMessage($appointment);

            // Send to both parties
            $this->sendMessage($farmer->phone ?? $fishFarm->no_telepon, $message);
            $this->sendMessage($collectorOwner->phone ?? $collector->no_telepon, $message);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send WhatsApp completion notification', [
                'appointment_id' => $appointment->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Build farmer appointment message
     */
    private function buildFarmerAppointmentMessage(Appointment $appointment, FishFarm $fishFarm, Collector $collector)
    {
        $farmOwnerName = $fishFarm->user->name ?? 'Pemilik Tambak';
        $farmName = $fishFarm->nama_tambak ?? $fishFarm->nama ?? 'Tambak';
        $fishType = $fishFarm->jenis_ikan ?? '-';
        $estimatedWeight = $appointment->estimated_weight ?? $appointment->perkiraan_berat ?? 0;
        $pricePerKg = $appointment->price_per_kg ?? $appointment->harga_per_kg ?? 0;
        $totalEstimate = $estimatedWeight * $pricePerKg;
        $collectorName = $collector->nama_bisnis ?? $collector->nama_usaha ?? 'Pengepul';
        $collectorPhone = $collector->no_telepon ?? $collector->user->no_telepon ?? '-';
        $collectorAddress = $collector->alamat ?? '-';
        
        // Format date and time
        $appointmentDate = \Carbon\Carbon::parse($appointment->tanggal_janji)->format('d/m/Y');
        $appointmentTime = $appointment->waktu_janji ?? '09:00';

        $message = "🐟 *IWAKMART - Janji Penjemputan Ikan*\n\n";
        $message .= "Halo *{$farmOwnerName}*,\n\n";
        $message .= "Janji penjemputan ikan Anda telah dibuat!\n\n";
        $message .= "📋 *Detail Penjemputan:*\n";
        $message .= "• Tambak: {$farmName}\n";
        $message .= "• Jenis Ikan: {$fishType}\n";
        $message .= "• Perkiraan Berat: {$estimatedWeight} kg\n";
        $message .= "• Harga per KG: Rp " . number_format($pricePerKg, 0, ',', '.') . "\n";
        $message .= "• Total Estimasi: Rp " . number_format($totalEstimate, 0, ',', '.') . "\n\n";
        $message .= "🏢 *Pengepul:*\n";
        $message .= "• Nama: {$collectorName}\n";
        $message .= "• Telepon: {$collectorPhone}\n";
        $message .= "• Alamat: {$collectorAddress}\n\n";
        $message .= "📅 *Tanggal:* {$appointmentDate}\n";
        $message .= "🕐 *Waktu:* {$appointmentTime}\n\n";
        
        if ($appointment->pesan_pemilik ?? $appointment->catatan) {
            $message .= "📝 *Catatan:* " . ($appointment->pesan_pemilik ?? $appointment->catatan) . "\n\n";
        }
        
        $message .= "Status saat ini: *{$appointment->status}*\n\n";
        $message .= "Terima kasih telah menggunakan IwakMart! 🐠";

        return $message;
    }

    /**
     * Build collector appointment message
     */
    private function buildCollectorAppointmentMessage(Appointment $appointment, FishFarm $fishFarm, Collector $collector)
    {
        $collectorOwnerName = $collector->user->name ?? 'Pengepul';
        $farmName = $fishFarm->nama_tambak ?? $fishFarm->nama ?? 'Tambak';
        $farmOwnerName = $fishFarm->user->name ?? 'Pemilik Tambak';
        $farmPhone = $fishFarm->no_telepon ?? $fishFarm->user->phone ?? '-';
        $farmAddress = $fishFarm->alamat ?? '-';
        $fishType = $fishFarm->jenis_ikan ?? '-';
        $estimatedWeight = $appointment->estimated_weight ?? $appointment->perkiraan_berat ?? 0;
        $pricePerKg = $appointment->price_per_kg ?? $appointment->harga_per_kg ?? 0;
        $totalEstimate = $estimatedWeight * $pricePerKg;
        
        // Format date and time
        $appointmentDate = \Carbon\Carbon::parse($appointment->tanggal_janji)->format('d/m/Y');
        $appointmentTime = $appointment->waktu_janji ?? '09:00';

        $message = "🐟 *IWAKMART - Janji Penjemputan Baru*\n\n";
        $message .= "Halo *{$collectorOwnerName}*,\n\n";
        $message .= "Ada permintaan penjemputan ikan baru!\n\n";
        $message .= "🏊 *Detail Tambak:*\n";
        $message .= "• Nama Tambak: {$farmName}\n";
        $message .= "• Pemilik: {$farmOwnerName}\n";
        $message .= "• Telepon: {$farmPhone}\n";
        $message .= "• Alamat: {$farmAddress}\n\n";
        $message .= "🐠 *Detail Ikan:*\n";
        $message .= "• Jenis: {$fishType}\n";
        $message .= "• Perkiraan Berat: {$estimatedWeight} kg\n";
        $message .= "• Harga per KG: Rp " . number_format($pricePerKg, 0, ',', '.') . "\n";
        $message .= "• Total Estimasi: Rp " . number_format($totalEstimate, 0, ',', '.') . "\n\n";
        $message .= "📅 *Tanggal:* {$appointmentDate}\n";
        $message .= "🕐 *Waktu:* {$appointmentTime}\n\n";
        
        if ($appointment->pesan_pemilik ?? $appointment->catatan) {
            $message .= "📝 *Catatan Pemilik Tambak:* " . ($appointment->pesan_pemilik ?? $appointment->catatan) . "\n\n";
        }
        
        $message .= "Status: *{$appointment->status}*\n";
        $message .= "Silakan buka aplikasi IwakMart untuk merespons janji temu ini.\n\n";
        $message .= "Terima kasih! 🐠";

        return $message;
    }

    /**
     * Build status update message
     */
    private function buildStatusUpdateMessage(Appointment $appointment, $oldStatus, $newStatus)
    {
        $message = "🔄 *ITAK MART - Update Status Penjemputan*\n\n";
        $message .= "Status penjemputan telah diperbarui:\n\n";
        $message .= "📋 *Detail:*\n";
        $message .= "• Tambak: {$appointment->fishFarm->nama}\n";
        $message .= "• Pengepul: {$appointment->collector->nama_usaha}\n";
        $message .= "• Tanggal: " . $appointment->tanggal->format('d F Y, H:i') . "\n\n";
        $message .= "• Status Lama: *{$oldStatus}*\n";
        $message .= "• Status Baru: *{$newStatus}*\n\n";
        
        if ($appointment->catatan_collector) {
            $message .= "📝 *Catatan Pengepul:* {$appointment->catatan_collector}\n\n";
        }
        
        $message .= "Cek aplikasi IwakMart untuk detail lengkap! 🐠";

        return $message;
    }

    /**
     * Build completion message
     */
    private function buildCompletionMessage(Appointment $appointment)
    {
        $farmName = $appointment->fishFarm->nama_tambak ?? $appointment->fishFarm->nama ?? 'Tambak';
        $collectorName = $appointment->collector->nama_bisnis ?? $appointment->collector->nama_usaha ?? 'Pengepul';
        $fishType = $appointment->fishFarm->jenis_ikan ?? '-';
        $actualWeight = $appointment->berat_aktual ?? 0;
        $fishQuality = $appointment->kualitas_ikan ?? 'Baik';
        $finalPrice = $appointment->price_per_kg ?? $appointment->harga_per_kg ?? 0;
        $totalPayment = $appointment->total_aktual ?? 0;
        $completionDate = $appointment->tanggal_selesai ? \Carbon\Carbon::parse($appointment->tanggal_selesai)->format('d/m/Y H:i') : \Carbon\Carbon::now()->format('d/m/Y H:i');

        $message = "✅ *IWAKMART - Penjemputan Selesai*\n\n";
        $message .= "Penjemputan ikan telah selesai!\n\n";
        $message .= "📋 *Ringkasan Transaksi:*\n";
        $message .= "• Tambak: {$farmName}\n";
        $message .= "• Pengepul: {$collectorName}\n";
        $message .= "• Tanggal Selesai: {$completionDate}\n\n";
        $message .= "🐠 *Detail Ikan:*\n";
        $message .= "• Jenis: {$fishType}\n";
        $message .= "• Berat Aktual: {$actualWeight} kg\n";
        $message .= "• Kualitas: {$fishQuality}\n";
        $message .= "• Harga Final: Rp " . number_format($finalPrice, 0, ',', '.') . "/kg\n";
        $message .= "• Total Pembayaran: Rp " . number_format($totalPayment, 0, ',', '.') . "\n\n";
        
        if ($appointment->catatan_selesai) {
            $message .= "📝 *Catatan:* {$appointment->catatan_selesai}\n\n";
        }
        
        $message .= "Terima kasih telah menggunakan IwakMart! 🐠\n";
        $message .= "Sampai jumpa di transaksi berikutnya! 🙏";

        return $message;
    }

    /**
     * Send WhatsApp message via Fonnte API
     */
    private function sendMessage($phoneNumber, $message)
    {
        try {
            // Skip if no phone number
            if (empty($phoneNumber)) {
                Log::warning('WhatsApp: Empty phone number provided');
                return false;
            }

            // Clean phone number - keep local format without 62 prefix
            $cleanPhone = preg_replace('/[^0-9]/', '', $phoneNumber);
            
            // Ensure phone starts with 0 for Indonesian local format
            if (!str_starts_with($cleanPhone, '0')) {
                // If it starts with 62, remove it and add 0
                if (str_starts_with($cleanPhone, '62')) {
                    $cleanPhone = '0' . substr($cleanPhone, 2);
                } else {
                    // If it doesn't start with 0 or 62, assume it needs 0 prefix
                    $cleanPhone = '0' . $cleanPhone;
                }
            }

            // Log the message attempt
            Log::info('WhatsApp: Attempting to send message', [
                'original_phone' => $phoneNumber,
                'formatted_phone' => $cleanPhone,
                'message_preview' => substr($message, 0, 100) . '...'
            ]);

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => config('services.whatsapp.api_url'),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array(
                    'target' => $cleanPhone, // Now using local format like 082257108680
                    'message' => $message,
                    'countryCode' => '62', // Country code as separate parameter
                ),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: ' . config('services.whatsapp.token')
                ),
            ));

            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $error = curl_error($curl);
            curl_close($curl);

            if ($error) {
                Log::error('WhatsApp: cURL Error', [
                    'error' => $error,
                    'phone' => $cleanPhone
                ]);
                return false;
            }

            $responseData = json_decode($response, true);

            Log::info('WhatsApp: API Response', [
                'http_code' => $httpCode,
                'response' => $responseData,
                'phone' => $cleanPhone
            ]);

            // Consider it successful if HTTP code is 200
            return $httpCode === 200;

        } catch (\Exception $e) {
            Log::error('WhatsApp: Failed to send message', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }
}
