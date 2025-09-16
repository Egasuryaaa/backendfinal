<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\XenditService;

class TestXenditCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xendit:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Xendit API connection and functionality';

    protected $xenditService;

    public function __construct(XenditService $xenditService)
    {
        parent::__construct();
        $this->xenditService = $xenditService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Xendit API Connection...');
        $this->newLine();

        // Test 1: Connection Test
        $this->info('1. Testing API Connection...');
        $connectionResult = $this->xenditService->testConnection();

        if ($connectionResult['success']) {
            $this->info('✅ Connection successful');
        } else {
            $this->error('❌ Connection failed: ' . $connectionResult['error']);
        }
        $this->newLine();

        // Test 2: Test creating a sample invoice
        $this->info('2. Testing Invoice Creation...');

        $testData = [
            'external_id' => 'TEST_' . time(),
            'amount' => 50000,
            'description' => 'Test Invoice from IwakMart',
            'customer_name' => 'Test Customer',
            'customer_email' => 'test@example.com',
            'customer_phone' => '+628123456789',
            'payment_method' => 'bank_transfer',
            'payment_channel' => 'BCA',
        ];

        $invoiceResult = $this->xenditService->createPayment($testData);

        if ($invoiceResult['success']) {
            $this->info('✅ Invoice created successfully');
            $this->info('Invoice ID: ' . $invoiceResult['data']['invoice_id']);
            $this->info('Payment URL: ' . $invoiceResult['data']['payment_url']);
            $this->info('External ID: ' . $invoiceResult['data']['external_id']);
            $this->info('Amount: Rp ' . number_format($invoiceResult['data']['amount'], 0, ',', '.'));

            // Test 3: Get invoice status
            $this->newLine();
            $this->info('3. Testing Invoice Status Check...');

            $statusResult = $this->xenditService->getInvoiceStatus($invoiceResult['data']['invoice_id']);

            if ($statusResult['success']) {
                $this->info('✅ Status check successful');
                $this->info('Status: ' . $statusResult['data']['status']);
                $this->info('Paid Amount: Rp ' . number_format($statusResult['data']['paid_amount'], 0, ',', '.'));
            } else {
                $this->error('❌ Status check failed: ' . $statusResult['error']);
            }

        } else {
            $this->error('❌ Invoice creation failed: ' . $invoiceResult['error']);
        }

        $this->newLine();

        // Test 4: Get supported payment methods
        $this->info('4. Testing Payment Methods...');
        $methodsResult = $this->xenditService->getSupportedPaymentMethods();

        if ($methodsResult['success']) {
            $this->info('✅ Payment methods retrieved');
            $methods = $methodsResult['data'];

            foreach ($methods as $type => $channels) {
                if (is_array($channels)) {
                    $this->info("- {$type}: " . implode(', ', $channels));
                } else {
                    $this->info("- {$type}: " . ($channels ? 'Enabled' : 'Disabled'));
                }
            }
        } else {
            $this->error('❌ Failed to get payment methods: ' . $methodsResult['error']);
        }

        $this->newLine();
        $this->info('Xendit API Test completed!');

        return 0;
    }
}
