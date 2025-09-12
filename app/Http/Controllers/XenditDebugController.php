<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Xendit\Xendit;
use Xendit\Invoice;
use Exception;
use Illuminate\Support\Facades\Log;

class XenditDebugController extends Controller
{
    public function __construct()
    {
        Xendit::setApiKey(config('xendit.secret_key'));
    }

    /**
     * Test Xendit connection and create a simple invoice
     */
    public function testInvoice(Request $request): JsonResponse
    {
        try {
            // Generate test external ID
            $externalId = 'TEST_' . time();

            // Create a simple test invoice
            $invoiceData = [
                'external_id' => $externalId,
                'amount' => 50000,
                'description' => 'Test Invoice - Iwak Mart',
                'invoice_duration' => 3600, // 1 hour
                'customer' => [
                    'given_names' => 'Test Customer',
                    'email' => 'test@example.com',
                    'mobile_number' => '+6281234567890'
                ],
                'success_redirect_url' => config('app.frontend_url', 'http://localhost:3000') . '/payment/success',
                'failure_redirect_url' => config('app.frontend_url', 'http://localhost:3000') . '/payment/failed',
                'currency' => 'IDR',
                'items' => [
                    [
                        'name' => 'Test Product',
                        'quantity' => 1,
                        'price' => 50000,
                        'category' => 'Test Category'
                    ]
                ],
                'payment_methods' => ['BCA', 'BRI', 'OVO', 'DANA']
            ];

            Log::info('Creating test Xendit invoice', [
                'external_id' => $externalId,
                'amount' => $invoiceData['amount'],
                'secret_key' => substr(config('xendit.secret_key'), 0, 20) . '...'
            ]);

            // Create invoice
            $invoice = Invoice::create($invoiceData);

            Log::info('Test Xendit invoice created successfully', [
                'invoice_id' => $invoice['id'],
                'invoice_url' => $invoice['invoice_url'],
                'external_id' => $externalId,
                'status' => $invoice['status']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Test invoice created successfully',
                'data' => [
                    'invoice_id' => $invoice['id'],
                    'external_id' => $externalId,
                    'amount' => $invoice['amount'],
                    'status' => $invoice['status'],
                    'invoice_url' => $invoice['invoice_url'],
                    'expired_at' => $invoice['expiry_date'],
                    'payment_methods' => $invoice['available_banks'] ?? [],
                    'xendit_response' => $invoice
                ]
            ]);

        } catch (Exception $e) {
            Log::error('Test Xendit invoice creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'secret_key' => substr(config('xendit.secret_key'), 0, 20) . '...'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create test invoice',
                'error' => $e->getMessage(),
                'debug_info' => [
                    'secret_key_configured' => !empty(config('xendit.secret_key')),
                    'secret_key_preview' => substr(config('xendit.secret_key'), 0, 20) . '...',
                    'xendit_env' => config('xendit.environment'),
                    'app_env' => config('app.env')
                ]
            ], 500);
        }
    }

    /**
     * Get invoice details by ID
     */
    public function getInvoice(Request $request, $invoiceId): JsonResponse
    {
        try {
            $invoice = Invoice::retrieve($invoiceId);

            return response()->json([
                'success' => true,
                'data' => $invoice
            ]);

        } catch (Exception $e) {
            Log::error('Failed to retrieve invoice', [
                'invoice_id' => $invoiceId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve invoice',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test Xendit configuration
     */
    public function testConfig(): JsonResponse
    {
        return response()->json([
            'xendit_config' => [
                'secret_key_configured' => !empty(config('xendit.secret_key')),
                'secret_key_preview' => substr(config('xendit.secret_key'), 0, 30) . '...',
                'public_key_configured' => !empty(config('xendit.public_key')),
                'public_key_preview' => substr(config('xendit.public_key'), 0, 30) . '...',
                'webhook_token_configured' => !empty(config('xendit.webhook_verification_token')),
                'callback_url' => config('xendit.callback_url'),
                'environment' => config('xendit.environment'),
                'invoice_expiry' => config('xendit.invoice_expiry'),
                'app_url' => config('app.url'),
                'frontend_url' => config('app.frontend_url'),
            ]
        ]);
    }

    /**
     * Create corrected invoice with proper data
     */
    public function createCorrectedInvoice(Request $request): JsonResponse
    {
        try {
            $externalId = 'CORRECTED_' . time();
            
            // Proper invoice data structure
            $invoiceData = [
                'external_id' => $externalId,
                'amount' => 100000, // Fixed amount
                'description' => 'Corrected Test Invoice - Iwak Mart',
                'invoice_duration' => 86400, // 24 hours
                'customer' => [
                    'given_names' => 'Test Customer',
                    'surname' => 'Iwak Mart',
                    'email' => 'customer@iwakmart.com',
                    'mobile_number' => '+6281234567890'
                ],
                'customer_notification_preference' => [
                    'invoice_created' => ['email'],
                    'invoice_reminder' => ['email'],
                    'invoice_paid' => ['email'],
                    'invoice_expired' => ['email']
                ],
                'success_redirect_url' => 'http://localhost:3000/payment/success',
                'failure_redirect_url' => 'http://localhost:3000/payment/failed',
                'currency' => 'IDR',
                'items' => [
                    [
                        'name' => 'Test Product Iwak',
                        'quantity' => 1,
                        'price' => 100000,
                        'category' => 'Seafood'
                    ]
                ]
            ];

            // Don't specify payment_methods to allow all methods
            Log::info('Creating corrected Xendit invoice', $invoiceData);

            $invoice = Invoice::create($invoiceData);

            Log::info('Corrected Xendit invoice created', [
                'invoice_id' => $invoice['id'],
                'invoice_url' => $invoice['invoice_url']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Corrected invoice created successfully',
                'data' => [
                    'invoice_id' => $invoice['id'],
                    'external_id' => $externalId,
                    'amount' => $invoice['amount'],
                    'status' => $invoice['status'],
                    'invoice_url' => $invoice['invoice_url'],
                    'expiry_date' => $invoice['expiry_date'],
                    'available_banks' => $invoice['available_banks'] ?? [],
                    'available_retail_outlets' => $invoice['available_retail_outlets'] ?? [],
                    'available_ewallets' => $invoice['available_ewallets'] ?? []
                ]
            ]);

        } catch (Exception $e) {
            Log::error('Corrected invoice creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create corrected invoice',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
