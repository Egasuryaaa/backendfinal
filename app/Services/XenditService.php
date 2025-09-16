<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class XenditService
{
    private $secretKey;
    private $baseUrl;

    public function __construct()
    {
        $this->secretKey = config('xendit.secret_key');
        $this->baseUrl = 'https://api.xendit.co';

        // Debug: Log the API key being used (mask it for security)
        Log::info('XenditService initialized', [
            'api_key_prefix' => substr($this->secretKey, 0, 20) . '...',
            'api_key_length' => strlen($this->secretKey),
            'base_url' => $this->baseUrl
        ]);
    }

    /**
     * Create payment with specific method
     * Always use Invoice API for unified Xendit payment page
     */
    public function createPayment($data)
    {
        return $this->createInvoice($data);
    }

    /**
     * Create invoice via Xendit API (Main method for all payment types)
     * This creates a unified payment page that supports all payment methods
     */
    public function createInvoice($data)
    {
        try {
            $invoiceData = [
                'external_id' => $data['external_id'],
                'amount' => $data['amount'],
                'description' => $data['description'] ?? 'Payment for Order ' . $data['external_id'],
                'invoice_duration' => config('xendit.invoice_expiry', 86400), // 24 hours default
                'currency' => config('xendit.currency', 'IDR'),
                'reminder_time' => 1, // Send reminder 1 hour before expiry
                'customer' => [
                    'given_names' => $data['customer_name'] ?? 'Customer',
                    'email' => $data['customer_email'] ?? 'customer@example.com',
                    'mobile_number' => $data['customer_phone'] && $data['customer_phone'] !== '' ? $data['customer_phone'] : '+6281234567890',
                ],
                'customer_notification_preference' => [
                    'invoice_created' => ['email'],
                    'invoice_reminder' => ['email'],
                    'invoice_paid' => ['email'],
                ],
                'success_redirect_url' => $data['success_redirect_url'] ?? config('app.frontend_url') . '/payment/success',
                'failure_redirect_url' => $data['failure_redirect_url'] ?? config('app.frontend_url') . '/payment/failed',
            ];

            // Configure payment methods based on user selection
            if (isset($data['payment_method']) && isset($data['payment_channel'])) {
                $paymentMethods = $this->getPaymentMethodsForInvoice($data['payment_method'], $data['payment_channel']);
                if (!empty($paymentMethods)) {
                    $invoiceData['payment_methods'] = $paymentMethods;
                }
            }

            Log::info('Creating Xendit Invoice', [
                'invoice_data' => $invoiceData,
                'external_id' => $data['external_id']
            ]);

            $response = Http::withBasicAuth($this->secretKey, '')
                ->timeout(30)
                ->post($this->baseUrl . '/v2/invoices', $invoiceData);

            Log::info('Xendit Invoice API Response', [
                'status' => $response->status(),
                'headers' => $response->headers(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                $responseData = $response->json();

                Log::info('Xendit Invoice Created Successfully', [
                    'invoice_id' => $responseData['id'],
                    'external_id' => $responseData['external_id'],
                    'invoice_url' => $responseData['invoice_url']
                ]);

                return [
                    'success' => true,
                    'data' => [
                        'payment_id' => $responseData['id'],
                        'invoice_id' => $responseData['id'],
                        'external_id' => $responseData['external_id'],
                        'status' => 'pending',
                        'payment_url' => $responseData['invoice_url'],
                        'invoice_url' => $responseData['invoice_url'],
                        'amount' => $responseData['amount'],
                        'expired_at' => $responseData['expiry_date'],
                        'currency' => $responseData['currency'],
                        'description' => $responseData['description'],
                    ]
                ];
            } else {
                $error = $response->json();
                Log::error('Xendit Invoice Creation Failed', [
                    'status' => $response->status(),
                    'error' => $error,
                    'external_id' => $data['external_id']
                ]);

                return [
                    'success' => false,
                    'error' => $error['message'] ?? 'Failed to create invoice',
                    'error_code' => $error['error_code'] ?? null,
                    'details' => $error
                ];
            }

        } catch (Exception $e) {
            Log::error('Xendit Service Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'external_id' => $data['external_id'] ?? 'unknown'
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get payment methods configuration for invoice
     * Maps our internal payment method names to Xendit's expected format
     */
    private function getPaymentMethodsForInvoice($paymentMethod, $paymentChannel)
    {
        $methods = [];

        try {
            switch ($paymentMethod) {
                case 'bank_transfer':
                    // Bank transfer uses bank codes directly
                    $bankCodes = ['BCA', 'BRI', 'BNI', 'MANDIRI', 'BSI', 'CIMB', 'PERMATA'];
                    if (in_array($paymentChannel, $bankCodes)) {
                        $methods[] = $paymentChannel;
                    } else {
                        // Fallback to all supported banks
                        $methods = $bankCodes;
                    }
                    break;

                case 'e_wallet':
                    // E-wallet payment methods
                    $ewalletCodes = ['OVO', 'DANA', 'LINKAJA', 'SHOPEEPAY'];
                    if (in_array($paymentChannel, $ewalletCodes)) {
                        $methods[] = $paymentChannel;
                    } else {
                        // Fallback to all supported e-wallets
                        $methods = $ewalletCodes;
                    }
                    break;

                case 'retail':
                    // Retail payment methods
                    $retailCodes = ['ALFAMART', 'INDOMARET'];
                    if (in_array($paymentChannel, $retailCodes)) {
                        $methods[] = $paymentChannel;
                    } else {
                        $methods = $retailCodes;
                    }
                    break;

                case 'qris':
                    $methods[] = 'QR_CODE';
                    break;

                case 'credit_card':
                    $methods[] = 'CREDIT_CARD';
                    break;

                default:
                    // If no specific method, allow all major payment methods
                    $methods = ['BCA', 'BRI', 'BNI', 'MANDIRI', 'OVO', 'DANA', 'LINKAJA', 'QR_CODE', 'CREDIT_CARD'];
                    break;
            }

            Log::info('Payment methods configured for invoice', [
                'payment_method' => $paymentMethod,
                'payment_channel' => $paymentChannel,
                'configured_methods' => $methods
            ]);

        } catch (Exception $e) {
            Log::error('Error configuring payment methods', [
                'error' => $e->getMessage(),
                'payment_method' => $paymentMethod,
                'payment_channel' => $paymentChannel
            ]);

            // Fallback to common payment methods
            $methods = ['BCA', 'BRI', 'BNI', 'OVO', 'DANA', 'QR_CODE'];
        }

        return $methods;
    }

    /**
     * Get invoice status
     */
    public function getInvoiceStatus($invoiceId)
    {
        try {
            Log::info('Getting invoice status', ['invoice_id' => $invoiceId]);

            $response = Http::withBasicAuth($this->secretKey, '')
                ->timeout(30)
                ->get($this->baseUrl . '/v2/invoices/' . $invoiceId);

            if ($response->successful()) {
                $data = $response->json();

                Log::info('Invoice status retrieved successfully', [
                    'invoice_id' => $invoiceId,
                    'status' => $data['status']
                ]);

                return [
                    'success' => true,
                    'data' => [
                        'id' => $data['id'],
                        'status' => $data['status'],
                        'amount' => $data['amount'],
                        'paid_amount' => $data['paid_amount'] ?? 0,
                        'paid_at' => $data['paid_at'],
                        'expiry_date' => $data['expiry_date'],
                        'invoice_url' => $data['invoice_url'],
                        'external_id' => $data['external_id'],
                    ]
                ];
            } else {
                $error = $response->json();
                Log::error('Failed to get invoice status', [
                    'invoice_id' => $invoiceId,
                    'status' => $response->status(),
                    'error' => $error
                ]);

                return [
                    'success' => false,
                    'error' => $error['message'] ?? 'Failed to get invoice status',
                    'error_code' => $error['error_code'] ?? null
                ];
            }

        } catch (Exception $e) {
            Log::error('Get Invoice Status Exception', [
                'invoice_id' => $invoiceId,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Cancel/Expire invoice
     */
    public function cancelInvoice($invoiceId)
    {
        try {
            Log::info('Cancelling invoice', ['invoice_id' => $invoiceId]);

            $response = Http::withBasicAuth($this->secretKey, '')
                ->timeout(30)
                ->post($this->baseUrl . '/v2/invoices/' . $invoiceId . '/expire!');

            if ($response->successful()) {
                $data = $response->json();

                Log::info('Invoice cancelled successfully', [
                    'invoice_id' => $invoiceId,
                    'status' => $data['status']
                ]);

                return [
                    'success' => true,
                    'data' => $data
                ];
            } else {
                $error = $response->json();
                Log::error('Failed to cancel invoice', [
                    'invoice_id' => $invoiceId,
                    'status' => $response->status(),
                    'error' => $error
                ]);

                return [
                    'success' => false,
                    'error' => $error['message'] ?? 'Failed to cancel invoice',
                    'error_code' => $error['error_code'] ?? null
                ];
            }

        } catch (Exception $e) {
            Log::error('Cancel Invoice Exception', [
                'invoice_id' => $invoiceId,
                'message' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Verify webhook callback signature
     */
    public function verifyWebhookSignature($callbackToken, $requestPayload = null)
    {
        try {
            $verificationToken = config('xendit.webhook_verification_token');

            if (empty($verificationToken)) {
                Log::warning('Webhook verification token not configured');
                return config('app.env') !== 'production'; // Allow in development
            }

            $isValid = hash_equals($verificationToken, $callbackToken);

            Log::info('Webhook signature verification', [
                'is_valid' => $isValid,
                'token_length' => strlen($callbackToken ?? ''),
                'expected_length' => strlen($verificationToken)
            ]);

            return $isValid;

        } catch (Exception $e) {
            Log::error('Webhook verification error', [
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Get supported payment methods from Xendit
     */
    public function getSupportedPaymentMethods()
    {
        try {
            // Return configured payment methods for now
            // In production, you might want to fetch this from Xendit API
            return [
                'success' => true,
                'data' => [
                    'bank_transfer' => config('xendit.payment_methods.bank_transfer', []),
                    'e_wallet' => config('xendit.payment_methods.e_wallet', []),
                    'retail' => config('xendit.payment_methods.retail', []),
                    'qris' => config('xendit.payment_methods.qris', true),
                    'credit_card' => config('xendit.payment_methods.credit_card', true),
                ]
            ];

        } catch (Exception $e) {
            Log::error('Failed to get supported payment methods', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Format amount for Xendit (ensure it's integer for IDR)
     */
    private function formatAmount($amount)
    {
        // Xendit expects amount in the smallest currency unit
        // For IDR, this is already in Rupiah (no decimal)
        return (int) round($amount);
    }

    /**
     * Validate payment data before sending to Xendit
     */
    private function validatePaymentData($data)
    {
        $requiredFields = ['external_id', 'amount'];

        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                throw new Exception("Required field '{$field}' is missing or empty");
            }
        }

        // Validate amount
        if ($data['amount'] < 1000) {
            throw new Exception("Amount must be at least IDR 1,000");
        }

        // Validate external_id format
        if (strlen($data['external_id']) > 1000) {
            throw new Exception("External ID is too long (max 1000 characters)");
        }

        return true;
    }

    /**
     * Handle API rate limiting
     */
    private function handleRateLimit($response)
    {
        if ($response->status() === 429) {
            $retryAfter = $response->header('Retry-After') ?? 60;

            Log::warning('Xendit API rate limit hit', [
                'retry_after' => $retryAfter
            ]);

            throw new Exception("Rate limit exceeded. Please try again in {$retryAfter} seconds.");
        }
    }

    /**
     * Test Xendit API connection
     */
    public function testConnection()
    {
        try {
            // Test with a simple API call
            $response = Http::withBasicAuth($this->secretKey, '')
                ->timeout(10)
                ->get($this->baseUrl . '/balance');

            if ($response->successful()) {
                Log::info('Xendit API connection test successful');
                return [
                    'success' => true,
                    'message' => 'Connection to Xendit API successful'
                ];
            } else {
                Log::error('Xendit API connection test failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                return [
                    'success' => false,
                    'error' => 'Failed to connect to Xendit API'
                ];
            }

        } catch (Exception $e) {
            Log::error('Xendit API connection test exception', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
