<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\XenditService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaymentController extends Controller
{
    protected $xenditService;

    public function __construct(XenditService $xenditService)
    {
        $this->xenditService = $xenditService;
    }

    /**
     * Get available payment methods
     */
    public function getPaymentMethods(): JsonResponse
    {
        try {
            $paymentMethods = [
                [
                    'id' => 'bank_transfer',
                    'name' => 'Bank Transfer',
                    'description' => 'Transfer melalui bank dengan virtual account',
                    'icon' => 'bank',
                    'type' => 'bank_transfer',
                    'is_active' => true,
                    'channels' => [
                        ['code' => 'BCA', 'name' => 'Bank BCA', 'icon' => 'bca.png'],
                        ['code' => 'BRI', 'name' => 'Bank BRI', 'icon' => 'bri.png'],
                        ['code' => 'BNI', 'name' => 'Bank BNI', 'icon' => 'bni.png'],
                        ['code' => 'MANDIRI', 'name' => 'Bank Mandiri', 'icon' => 'mandiri.png'],
                        ['code' => 'BSI', 'name' => 'Bank BSI', 'icon' => 'bsi.png'],
                        ['code' => 'CIMB', 'name' => 'Bank CIMB Niaga', 'icon' => 'cimb.png'],
                        ['code' => 'PERMATA', 'name' => 'Bank Permata', 'icon' => 'permata.png'],
                    ]
                ],
                [
                    'id' => 'e_wallet',
                    'name' => 'E-Wallet',
                    'description' => 'Bayar dengan dompet digital',
                    'icon' => 'wallet',
                    'type' => 'e_wallet',
                    'is_active' => true,
                    'channels' => [
                        ['code' => 'OVO', 'name' => 'OVO', 'icon' => 'ovo.png'],
                        ['code' => 'DANA', 'name' => 'DANA', 'icon' => 'dana.png'],
                        ['code' => 'LINKAJA', 'name' => 'LinkAja', 'icon' => 'linkaja.png'],
                        ['code' => 'SHOPEEPAY', 'name' => 'ShopeePay', 'icon' => 'shopeepay.png'],
                    ]
                ],
                [
                    'id' => 'retail',
                    'name' => 'Retail Store',
                    'description' => 'Bayar di toko retail seperti Alfamart/Indomaret',
                    'icon' => 'store',
                    'type' => 'retail',
                    'is_active' => true,
                    'channels' => [
                        ['code' => 'ALFAMART', 'name' => 'Alfamart', 'icon' => 'alfamart.png'],
                        ['code' => 'INDOMARET', 'name' => 'Indomaret', 'icon' => 'indomaret.png'],
                    ]
                ],
                [
                    'id' => 'qris',
                    'name' => 'QRIS',
                    'description' => 'Scan QR Code untuk bayar',
                    'icon' => 'qr',
                    'type' => 'qr_code',
                    'is_active' => true,
                    'channels' => [
                        ['code' => 'QRIS', 'name' => 'QRIS', 'icon' => 'qris.png'],
                    ]
                ],
                [
                    'id' => 'credit_card',
                    'name' => 'Credit Card',
                    'description' => 'Bayar dengan kartu kredit',
                    'icon' => 'credit-card',
                    'type' => 'credit_card',
                    'is_active' => true,
                    'channels' => [
                        ['code' => 'CREDIT_CARD', 'name' => 'Credit Card', 'icon' => 'creditcard.png'],
                    ]
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $paymentMethods
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get payment methods', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get payment methods',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create payment for an order
     */
    public function createPayment(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|string',
            'payment_method' => 'required|in:bank_transfer,e_wallet,retail,credit_card,qris',
            'payment_channel' => 'required|string',
            'amount' => 'required|numeric|min:1000',
            'metadata.customer_name' => 'nullable|string',
            'metadata.customer_email' => 'nullable|email',
            'metadata.customer_phone' => 'nullable|string',
            'metadata.order_number' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $orderId = $request->input('order_id');
            $amount = $request->input('amount');
            $paymentMethod = $request->input('payment_method');
            $paymentChannel = $request->input('payment_channel');
            $metadata = $request->input('metadata', []);

            // Generate unique external ID
            $externalId = $orderId . '_' . time() . '_' . rand(1000, 9999);

            // Prepare payment data for Xendit
            $paymentData = [
                'external_id' => $externalId,
                'amount' => $amount,
                'payment_method' => $paymentMethod,
                'payment_channel' => $paymentChannel,
                'customer_name' => $metadata['customer_name'] ?? 'Customer',
                'customer_email' => $metadata['customer_email'] ?? 'customer@example.com',
                'customer_phone' => $metadata['customer_phone'] ?? '+628123456789',
                'description' => 'Payment for Order ' . ($metadata['order_number'] ?? $orderId),
                'success_redirect_url' => config('app.frontend_url') . '/payment/success',
                'failure_redirect_url' => config('app.frontend_url') . '/payment/failed',
            ];

            Log::info('Creating payment via Xendit', [
                'external_id' => $externalId,
                'order_id' => $orderId,
                'amount' => $amount,
                'payment_method' => $paymentMethod,
                'payment_channel' => $paymentChannel
            ]);

            // Create payment using Xendit service
            $result = $this->xenditService->createPayment($paymentData);

            if ($result['success']) {
                $xenditData = $result['data'];

                // Store payment record in database
                $paymentRecord = [
                    'payment_id' => $xenditData['payment_id'],
                    'invoice_id' => $xenditData['invoice_id'] ?? null,
                    'external_id' => $externalId,
                    'order_id' => $orderId,
                    'amount' => $amount,
                    'status' => 'pending',
                    'payment_method' => $paymentMethod,
                    'payment_channel' => $paymentChannel,
                    'invoice_url' => $xenditData['invoice_url'] ?? null,
                    'payment_url' => $xenditData['payment_url'] ?? $xenditData['invoice_url'] ?? null,
                    'customer_name' => $paymentData['customer_name'],
                    'customer_email' => $paymentData['customer_email'],
                    'customer_phone' => $paymentData['customer_phone'],
                    'description' => $paymentData['description'],
                    'xendit_response' => json_encode($xenditData),
                    'expired_at' => isset($xenditData['expired_at']) ?
                        Carbon::parse($xenditData['expired_at']) :
                        now()->addHours(24),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // Insert to database
                DB::table('payments')->insert($paymentRecord);

                // Prepare response data
                $responseData = [
                    'payment_id' => $paymentRecord['payment_id'],
                    'invoice_id' => $paymentRecord['invoice_id'],
                    'external_id' => $externalId,
                    'order_id' => $orderId,
                    'amount' => $amount,
                    'status' => 'pending',
                    'payment_method' => $paymentMethod,
                    'payment_channel' => $paymentChannel,
                    'created_at' => now()->toISOString(),
                    'expires_at' => $paymentRecord['expired_at']->toISOString(),
                ];

                // IMPORTANT: Always include invoice_url for Xendit payment page
                if (!empty($paymentRecord['invoice_url'])) {
                    $responseData['payment_url'] = $paymentRecord['invoice_url'];
                    $responseData['invoice_url'] = $paymentRecord['invoice_url'];
                }

                // Add payment-specific data for manual instructions (optional)
                $this->addPaymentInstructions($responseData, $paymentMethod, $xenditData);

                DB::commit();

                Log::info('Payment created successfully', [
                    'payment_id' => $responseData['payment_id'],
                    'external_id' => $externalId,
                    'order_id' => $orderId,
                    'has_invoice_url' => !empty($paymentRecord['invoice_url'])
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Payment created successfully',
                    'data' => $responseData
                ]);

            } else {
                DB::rollback();

                Log::error('Xendit payment creation failed', [
                    'external_id' => $externalId,
                    'error' => $result['error']
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Payment creation failed',
                    'error' => $result['error']
                ], 500);
            }

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Payment creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Payment creation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add payment instructions to response data
     */
    private function addPaymentInstructions(&$responseData, $paymentMethod, $xenditData)
    {
        switch ($paymentMethod) {
            case 'bank_transfer':
                if (isset($xenditData['virtual_account'])) {
                    $responseData['virtual_account'] = $xenditData['virtual_account'];
                    $responseData['payment_instructions'] = 'Transfer ke nomor virtual account yang tertera';
                }
                break;

            case 'qris':
                if (isset($xenditData['qr_code']) || isset($xenditData['qr_string'])) {
                    $responseData['qr_code'] = $xenditData['qr_code'] ?? $xenditData['qr_string'];
                    $responseData['payment_instructions'] = 'Scan QR code dengan aplikasi pembayaran yang mendukung QRIS';
                }
                break;

            case 'retail':
                if (isset($xenditData['payment_code'])) {
                    $responseData['payment_code'] = $xenditData['payment_code'];
                    $responseData['payment_instructions'] = 'Tunjukkan payment code ke kasir untuk melakukan pembayaran';
                }
                break;

            case 'e_wallet':
                $responseData['payment_instructions'] = 'Klik tombol bayar untuk diarahkan ke aplikasi e-wallet';
                break;

            case 'credit_card':
                $responseData['payment_instructions'] = 'Klik tombol bayar untuk memasukkan data kartu kredit';
                break;
        }
    }

    /**
     * Get payment status
     */
    public function getPaymentStatus(Request $request, $paymentId): JsonResponse
    {
        try {
            // Get payment record from database
            $paymentRecord = DB::table('payments')
                ->where('payment_id', $paymentId)
                ->first();

            if (!$paymentRecord) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment not found'
                ], 404);
            }

            // Check if payment is expired
            if ($paymentRecord->expired_at) {
                $expiredAt = Carbon::parse($paymentRecord->expired_at);
                if (now()->gt($expiredAt) && $paymentRecord->status === 'pending') {
                    // Auto-expire the payment
                    DB::table('payments')
                        ->where('payment_id', $paymentId)
                        ->update([
                            'status' => 'expired',
                            'updated_at' => now()
                        ]);

                    $paymentRecord->status = 'expired';
                }
            }

            // Try to get latest status from Xendit
            $latestStatus = $paymentRecord->status;

            if ($paymentRecord->status === 'pending' && $paymentRecord->invoice_id) {
                $statusResult = $this->xenditService->getInvoiceStatus($paymentRecord->invoice_id);
                if ($statusResult['success']) {
                    $xenditStatus = $statusResult['data']['status'] ?? 'PENDING';
                    $latestStatus = $this->mapXenditStatus($xenditStatus);

                    // Update database with latest status
                    if ($latestStatus !== $paymentRecord->status) {
                        $updateData = [
                            'status' => $latestStatus,
                            'updated_at' => now(),
                        ];

                        if ($latestStatus === 'paid') {
                            $updateData['paid_at'] = $statusResult['data']['paid_at'] ?? now();
                        }

                        DB::table('payments')
                            ->where('payment_id', $paymentId)
                            ->update($updateData);
                    }
                }
            }

            // Prepare response data
            $responseData = [
                'payment_id' => $paymentId,
                'external_id' => $paymentRecord->external_id,
                'order_id' => $paymentRecord->order_id,
                'amount' => (float) $paymentRecord->amount,
                'status' => strtoupper($latestStatus), // Return uppercase for Flutter compatibility
                'payment_method' => $paymentRecord->payment_method,
                'payment_channel' => $paymentRecord->payment_channel,
                'created_at' => $paymentRecord->created_at,
                'updated_at' => ($latestStatus !== $paymentRecord->status) ? now()->toISOString() : $paymentRecord->updated_at,
                'expires_at' => $paymentRecord->expired_at,
                'paid_at' => $paymentRecord->paid_at,
                'failure_reason' => null,
            ];

            // Add failure reason for failed payments
            if ($latestStatus === 'failed' || $latestStatus === 'expired') {
                $responseData['failure_reason'] = $latestStatus === 'expired' ?
                    'Payment expired' :
                    'Payment failed';
            }

            Log::info('Payment status checked', [
                'payment_id' => $paymentId,
                'status' => $latestStatus
            ]);

            return response()->json([
                'success' => true,
                'data' => $responseData
            ]);

        } catch (\Exception $e) {
            Log::error('Get payment status failed', [
                'payment_id' => $paymentId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get payment status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Map Xendit status to our internal status
     */
    private function mapXenditStatus($xenditStatus)
    {
        $statusMap = [
            'PENDING' => 'pending',
            'PAID' => 'paid',
            'SETTLED' => 'paid',
            'EXPIRED' => 'expired',
            'FAILED' => 'failed',
            'CANCELLED' => 'failed',
        ];

        return $statusMap[strtoupper($xenditStatus)] ?? 'pending';
    }

    /**
     * Cancel payment
     */
    public function cancelPayment(Request $request, $paymentId): JsonResponse
    {
        try {
            $paymentRecord = DB::table('payments')
                ->where('payment_id', $paymentId)
                ->first();

            if (!$paymentRecord) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment not found'
                ], 404);
            }

            if ($paymentRecord->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment cannot be cancelled'
                ], 400);
            }

            // Update payment status to cancelled
            DB::table('payments')
                ->where('payment_id', $paymentId)
                ->update([
                    'status' => 'cancelled',
                    'updated_at' => now()
                ]);

            Log::info('Payment cancelled', [
                'payment_id' => $paymentId
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment cancelled successfully',
                'data' => [
                    'payment_id' => $paymentId,
                    'status' => 'CANCELLED',
                    'cancelled_at' => now()->toISOString()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Payment cancellation failed', [
                'payment_id' => $paymentId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel payment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Webhook handler for payment notifications from Xendit
     */
    public function handleWebhook(Request $request): JsonResponse
    {
        try {
            Log::info('Payment webhook received', [
                'headers' => $request->headers->all(),
                'body' => $request->all(),
                'method' => $request->method(),
                'url' => $request->fullUrl()
            ]);

            // Verify webhook token
            $webhookToken = $request->header('X-CALLBACK-TOKEN')
                         ?? $request->header('x-callback-token')
                         ?? $request->header('X-Callback-Token');

            $expectedToken = config('xendit.webhook_verification_token');

            if (config('app.env') === 'production' && !empty($expectedToken) && $webhookToken !== $expectedToken) {
                Log::warning('Invalid webhook token', [
                    'received_token' => $webhookToken ? substr($webhookToken, 0, 10) . '...' : 'null',
                    'expected_token' => substr($expectedToken, 0, 10) . '...'
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            $data = $request->all();

            if (empty($data)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Empty webhook data'
                ], 400);
            }

            // Process webhook data
            $invoiceId = $data['id'] ?? null;
            $status = $data['status'] ?? 'unknown';
            $externalId = $data['external_id'] ?? null;
            $amount = $data['amount'] ?? 0;
            $paidAt = $data['paid_at'] ?? null;

            if ($externalId) {
                $internalStatus = $this->mapXenditStatus($status);

                $updateData = [
                    'status' => $internalStatus,
                    'updated_at' => now(),
                    'xendit_response' => json_encode($data)
                ];

                if ($internalStatus === 'paid' && $paidAt) {
                    $updateData['paid_at'] = Carbon::parse($paidAt);
                }

                $updated = DB::table('payments')
                    ->where('external_id', $externalId)
                    ->update($updateData);

                if ($updated) {
                    Log::info('Payment status updated via webhook', [
                        'external_id' => $externalId,
                        'invoice_id' => $invoiceId,
                        'new_status' => $internalStatus,
                        'amount' => $amount
                    ]);
                } else {
                    Log::warning('Payment not found for webhook update', [
                        'external_id' => $externalId,
                        'invoice_id' => $invoiceId
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Webhook processed successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Webhook processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Webhook processing failed'
            ], 500);
        }
    }

    /**
     * Get payment configuration for frontend
     */
    public function getPaymentConfig(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'public_key' => config('xendit.public_key'),
                'environment' => config('app.env'),
                'currency' => 'IDR',
                'webhook_url' => config('app.url') . '/api/webhooks/xendit',
                'supported_methods' => [
                    'bank_transfer',
                    'e_wallet',
                    'retail',
                    'qris',
                    'credit_card'
                ],
                'payment_timeout_hours' => 24,
                'minimum_amount' => 1000
            ]
        ]);
    }
}

