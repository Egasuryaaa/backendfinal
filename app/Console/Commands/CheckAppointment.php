<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use App\Models\Collector;

class CheckAppointment extends Command
{
    protected $signature = 'check:appointment {id}';
    protected $description = 'Check appointment details';

    public function handle()
    {
        $id = $this->argument('id');
        
        $appointment = Appointment::find($id);
        
        if (!$appointment) {
            $this->error("Appointment {$id} not found");
            return;
        }
        
        $this->info("Appointment {$id} details:");
        $this->info("Status: {$appointment->status}");
        $this->info("Collector ID: {$appointment->collector_id}");
        
        if ($appointment->collector) {
            $this->info("Collector User ID: {$appointment->collector->user_id}");
            $this->info("Collector Name: {$appointment->collector->nama}");
        } else {
            $this->info("Collector: Not found");
        }
        
        // Check all collectors for current user (assuming user_id = 1 for testing)
        $this->info("\nAll collectors:");
        $collectors = Collector::all();
        foreach ($collectors as $collector) {
            $this->info("Collector ID: {$collector->id}, User ID: {$collector->user_id}, Name: {$collector->nama}");
        }
        
        // Check all appointments
        $this->info("\nAll appointments:");
        $appointments = Appointment::all();
        foreach ($appointments as $apt) {
            $this->info("Appointment ID: {$apt->id}, Status: {$apt->status}, Collector ID: {$apt->collector_id}");
        }
    }
}
