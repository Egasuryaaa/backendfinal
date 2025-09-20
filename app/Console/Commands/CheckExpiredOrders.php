<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class CheckExpiredOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:check-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and cancel expired manual payment orders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for expired orders...');

        $expiredOrders = Order::where('status', 'menunggu')
                             ->where('metode_pembayaran', 'manual')
                             ->whereNotNull('payment_deadline')
                             ->where('payment_deadline', '<', now())
                             ->get();

        $cancelledCount = 0;

        foreach ($expiredOrders as $order) {
            try {
                $order->cancelDueToTimeout();
                $cancelledCount++;

                $this->line("Cancelled order #{$order->nomor_pesanan} - Payment deadline: {$order->payment_deadline}");

            } catch (\Exception $e) {
                $this->error("Failed to cancel order #{$order->nomor_pesanan}: " . $e->getMessage());
                Log::error('Failed to cancel expired order', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        $this->info("Process completed. Cancelled {$cancelledCount} expired orders.");

        return 0;
    }
}
