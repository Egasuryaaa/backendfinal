<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class UpdateSellerBankAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seller:update-bank {userId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update seller bank account information';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('userId');

        $seller = User::find($userId);

        if (!$seller) {
            $this->error("User with ID {$userId} not found");
            return 1;
        }

        if (!$seller->isSeller()) {
            $this->error("User is not a seller");
            return 1;
        }

        $seller->update([
            'bank_name' => 'BCA',
            'account_number' => '1234567890',
            'account_holder_name' => 'Toko Ikan Segar Dapa'
        ]);

        $this->info("Seller bank account updated successfully:");
        $this->line("Name: " . $seller->name);
        $this->line("Bank: " . $seller->bank_name);
        $this->line("Account: " . $seller->account_number);
        $this->line("Holder: " . $seller->account_holder_name);

        return 0;
    }
}
