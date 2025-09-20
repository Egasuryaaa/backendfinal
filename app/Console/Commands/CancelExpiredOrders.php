<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class CancelExpiredOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:cancel-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel orders that have passed their payment deadline';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for expired orders...');

        try {
            $expiredOrders = Order::where('status', 'menunggu')
                                 ->where('metode_pembayaran', 'manual')
                                 ->whereNotNull('payment_deadline')
                                 ->where('payment_deadline', '<', now())
                                 ->get();

            $cancelledCount = 0;
            foreach ($expiredOrders as $order) {
                $this->info("Cancelling order #{$order->nomor_pesanan} (expired at {$order->payment_deadline})");
                $order->cancelDueToTimeout();
                $cancelledCount++;
            }

            if ($cancelledCount > 0) {
                $this->info("Successfully cancelled {$cancelledCount} expired orders.");
                Log::info("Auto-cancelled {$cancelledCount} expired orders");
            } else {
                $this->info("No expired orders found.");
            }

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("Error checking expired orders: " . $e->getMessage());
            Log::error('Error in orders:cancel-expired command: ' . $e->getMessage());

            return Command::FAILURE;
        }
    }
}
