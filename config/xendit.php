<?php

return [
    'secret_key' => env('XENDIT_SECRET_KEY', 'xnd_development_JvW3YNMk761D4Gt3w9yQLmah1DNaakJWJXMApKkfsTHgsfSdPVSrPVM1K7Csgds'),
    'public_key' => env('XENDIT_PUBLIC_KEY', 'xnd_public_development_hKjyzPHI61EyKMVUOq6WoR8o79c5ROGB596YgHm8dioYwosXRUkSXCluUa_xBkp'),
    'webhook_verification_token' => env('XENDIT_WEBHOOK_VERIFICATION_TOKEN', 'pDc830zh8sB9ZVb8aX6W2RWe3C0WBpVJspCKlzhVyjMn5GG2'),
    'callback_url' => env('XENDIT_CALLBACK_URL', env('APP_URL') . '/api/xendit/webhook'),
    
    // Xendit Environment
    'environment' => env('APP_ENV') === 'production' ? 'https://api.xendit.co' : 'https://api.xendit.co',
    
    // Payment methods yang didukung
    'payment_methods' => [
        'bank_transfer' => [
            'BCA', 'BRI', 'BNI', 'MANDIRI', 'BSI', 'CIMB', 'PERMATA'
        ],
        'e_wallet' => [
            'OVO', 'DANA', 'LINKAJA', 'SHOPEEPAY'
        ],
        'retail' => [
            'ALFAMART', 'INDOMARET'
        ],
        'credit_card' => true,
        'qris' => true,
    ],
    
    // Expiry time untuk invoice (dalam detik)
    'invoice_expiry' => 24 * 60 * 60, // 24 jam
    
    // Currency
    'currency' => 'IDR',
];
