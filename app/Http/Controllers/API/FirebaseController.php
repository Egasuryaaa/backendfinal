<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FirebaseController extends Controller
{
    /**
     * Generate custom token for Firebase authentication
     * Note: This is a simplified version. For production, install kreait/firebase-php
     */
    public function generateCustomToken(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated',
                ], 401);
            }

            // For development: generate a simple token
            // In production, use Firebase Admin SDK to create proper custom tokens
            $customToken = base64_encode(json_encode([
                'user_id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'role' => $this->getUserRole($user),
                'created_at' => now()->timestamp,
                'expires_at' => now()->addHour()->timestamp,
            ]));

            return response()->json([
                'success' => true,
                'message' => 'Custom token generated successfully',
                'custom_token' => $customToken,
                'user_id' => $user->id,
                'expires_in' => 3600, // 1 hour
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate custom token: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get Firebase configuration for client
     */
    public function getConfig(Request $request)
    {
        // Return sanitized Firebase config for client-side use
        return response()->json([
            'success' => true,
            'config' => [
                'project_id' => env('FIREBASE_PROJECT_ID', 'iwakmart-chat'),
                'database_url' => env('FIREBASE_DATABASE_URL', 'https://iwakmart-chat-default-rtdb.asia-southeast1.firebasedatabase.app'),
                'storage_bucket' => env('FIREBASE_STORAGE_BUCKET', 'iwakmart-chat.appspot.com'),
                'api_key' => env('FIREBASE_API_KEY'),
                'auth_domain' => env('FIREBASE_AUTH_DOMAIN', 'iwakmart-chat.firebaseapp.com'),
                'messaging_sender_id' => env('FIREBASE_MESSAGING_SENDER_ID'),
                'app_id' => env('FIREBASE_APP_ID'),
            ],
        ]);
    }

    /**
     * Initialize user for Firebase
     */
    public function initializeUser(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated',
                ], 401);
            }

            // Return user data for Firebase initialization
            return response()->json([
                'success' => true,
                'message' => 'User data for Firebase initialization',
                'user_data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar' => $user->avatar ? asset('storage/' . $user->avatar) : null,
                    'role' => $this->getUserRole($user),
                    'online' => true,
                    'lastSeen' => now()->timestamp * 1000,
                    'createdAt' => now()->timestamp * 1000,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get user data: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update user presence (logged to Laravel for analytics)
     */
    public function updatePresence(Request $request)
    {
        $request->validate([
            'online' => 'required|boolean',
        ]);

        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated',
                ], 401);
            }

            // Log presence update for analytics
            // You could store this in a user_activity table
            Log::info('User presence update', [
                'user_id' => $user->id,
                'online' => $request->input('online'),
                'timestamp' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Presence updated successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update presence: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get user role helper method
     */
    private function getUserRole($user)
    {
        // Check if user has seller role using a simple method
        // Adjust this based on your user role implementation
        if (method_exists($user, 'hasRole')) {
            return $user->isSeller() ? 'seller' : 'buyer';
        }

        // Alternative: check for seller-related fields
        if (isset($user->seller_id) || isset($user->is_seller)) {
            return 'seller';
        }

        return 'buyer';
    }

    /**
     * Sync message to Firebase (webhook endpoint)
     */
    public function syncMessage(Request $request)
    {
        $request->validate([
            'message_id' => 'required|integer',
            'sender_id' => 'required|integer',
            'recipient_id' => 'required|integer',
            'content' => 'required|string',
            'type' => 'required|string',
        ]);

        try {
            // Log message for Firebase sync
            Log::info('Message sync to Firebase', [
                'message_id' => $request->input('message_id'),
                'sender_id' => $request->input('sender_id'),
                'recipient_id' => $request->input('recipient_id'),
                'type' => $request->input('type'),
                'timestamp' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Message sync logged successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to sync message: ' . $e->getMessage(),
            ], 500);
        }
    }
}

