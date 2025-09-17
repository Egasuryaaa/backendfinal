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
            $this->sendMessage($farmer->no_telepon ?? $fishFarm->no_telepon, $farmerMessage);

            // Send to collector
            $collectorMessage = $this->buildCollectorAppointmentMessage($appointment, $fishFarm, $collector);
            $this->sendMessage($collectorOwner->no_telepon ?? $collector->no_telepon, $collectorMessage);

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
            $this->sendMessage($farmer->no_telepon ?? $fishFarm->no_telepon, $message);
            $this->sendMessage($collectorOwner->no_telepon ?? $collector->no_telepon, $message);

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
            $this->sendMessage($farmer->no_telepon ?? $fishFarm->no_telepon, $message);
            $this->sendMessage($collectorOwner->no_telepon ?? $collector->no_telepon, $message);

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
        $message = "🐟 *ITAK MART - Janji Penjemputan Ikan*\n\n";
        $message .= "Halo *{$fishFarm->user->name}*,\n\n";
        $message .= "Janji penjemputan ikan Anda telah dibuat!\n\n";
        $message .= "📋 *Detail Penjemputan:*\n";
        $message .= "• Tambak: {$fishFarm->nama}\n";
        $message .= "• Jenis Ikan: {$fishFarm->jenis_ikan}\n";
        $message .= "• Perkiraan Berat: {$appointment->perkiraan_berat} kg\n";
        $message .= "• Harga per KG: Rp " . number_format($appointment->harga_per_kg, 0, ',', '.') . "\n";
        $message .= "• Total Estimasi: Rp " . number_format($appointment->total_estimasi, 0, ',', '.') . "\n\n";
        $message .= "🏢 *Pengepul:*\n";
        $message .= "• Nama: {$collector->nama_usaha}\n";
        $message .= "• Telepon: {$collector->no_telepon}\n";
        $message .= "• Alamat: {$collector->alamat}\n\n";
        $message .= "📅 *Tanggal Penjemputan:* " . $appointment->tanggal->format('d F Y, H:i') . "\n\n";
        
        if ($appointment->catatan) {
            $message .= "📝 *Catatan:* {$appointment->catatan}\n\n";
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
        $message = "🐟 *ITAK MART - Janji Penjemputan Baru*\n\n";
        $message .= "Halo *{$collector->user->name}*,\n\n";
        $message .= "Ada permintaan penjemputan ikan baru!\n\n";
        $message .= "🏊 *Detail Tambak:*\n";
        $message .= "• Nama Tambak: {$fishFarm->nama}\n";
        $message .= "• Pemilik: {$fishFarm->user->name}\n";
        $message .= "• Telepon: {$fishFarm->no_telepon}\n";
        $message .= "• Alamat: {$fishFarm->alamat}\n\n";
        $message .= "🐠 *Detail Ikan:*\n";
        $message .= "• Jenis: {$fishFarm->jenis_ikan}\n";
        $message .= "• Perkiraan Berat: {$appointment->perkiraan_berat} kg\n";
        $message .= "• Harga per KG: Rp " . number_format($appointment->harga_per_kg, 0, ',', '.') . "\n";
        $message .= "• Total Estimasi: Rp " . number_format($appointment->total_estimasi, 0, ',', '.') . "\n\n";
        $message .= "📅 *Tanggal Penjemputan:* " . $appointment->tanggal->format('d F Y, H:i') . "\n\n";
        
        if ($appointment->catatan) {
            $message .= "📝 *Catatan Petani:* {$appointment->catatan}\n\n";
        }
        
        $message .= "Status: *{$appointment->status}*\n";
        $message .= "Silakan konfirmasi melalui aplikasi IwakMart.\n\n";
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
        $message = "✅ *ITAK MART - Penjemputan Selesai*\n\n";
        $message .= "Penjemputan ikan telah selesai!\n\n";
        $message .= "📋 *Ringkasan Transaksi:*\n";
        $message .= "• Tambak: {$appointment->fishFarm->nama}\n";
        $message .= "• Pengepul: {$appointment->collector->nama_usaha}\n";
        $message .= "• Tanggal Selesai: " . $appointment->tanggal_selesai->format('d F Y, H:i') . "\n\n";
        $message .= "🐠 *Detail Ikan:*\n";
        $message .= "• Jenis: {$appointment->fishFarm->jenis_ikan}\n";
        $message .= "• Berat Aktual: {$appointment->berat_aktual} kg\n";
        $message .= "• Kualitas: {$appointment->kualitas_ikan}\n";
        $message .= "• Harga Final: Rp " . number_format($appointment->harga_final, 0, ',', '.') . "/kg\n";
        $message .= "• Total Pembayaran: Rp " . number_format($appointment->total_aktual, 0, ',', '.') . "\n\n";
        
        if ($appointment->catatan_selesai) {
            $message .= "📝 *Catatan:* {$appointment->catatan_selesai}\n\n";
        }
        
        $message .= "Terima kasih telah menggunakan IwakMart! 🐠\n";
        $message .= "Sampai jumpa di transaksi berikutnya! 🙏";

        return $message;
    }

    /**
     * Send WhatsApp message (mock implementation)
     * In production, integrate with actual WhatsApp API like Twilio, WhatsApp Business API, etc.
     */
    private function sendMessage($phoneNumber, $message)
    {
        try {
            // Clean phone number
            $cleanPhone = preg_replace('/[^0-9]/', '', $phoneNumber);
            
            // Convert to international format if needed
            if (substr($cleanPhone, 0, 1) === '0') {
                $cleanPhone = '62' . substr($cleanPhone, 1);
            }

            // Log the message for development/testing
            Log::info('WhatsApp Message Sent', [
                'phone' => $cleanPhone,
                'message' => $message,
                'timestamp' => now()
            ]);

            // In production, uncomment and configure actual WhatsApp API
            /*
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.whatsapp.token'),
                'Content-Type' => 'application/json'
            ])->post(config('services.whatsapp.url'), [
                'messaging_product' => 'whatsapp',
                'to' => $cleanPhone,
                'type' => 'text',
                'text' => [
                    'body' => $message
                ]
            ]);

            return $response->successful();
            */

            return true; // Return true for development
        } catch (\Exception $e) {
            Log::error('Failed to send WhatsApp message', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}
