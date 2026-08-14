<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    /**
     * Initialize Paystack Payment
     */
    public function initializePaystack(Order $order)
    {
        try {
            $response = Http::withoutVerifying()
                ->withToken(config('services.paystack.secret_key'))
                ->post(config('services.paystack.payment_url') . '/transaction/initialize', [
                    'amount' => $order->total_amount * 100,
                    'email' => $order->user->email,
                    'reference' => $order->order_number . '-' . time(),
                    'callback_url' => route('payment.callback'),
                    'metadata' => [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                    ]
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $order->update(['gateway_reference' => $data['data']['reference']]);
                return ['success' => true, 'url' => $data['data']['authorization_url']];
            }

            $error = $response->json()['message'] ?? 'Unknown Paystack error';
            Log::error('Paystack Initialization Failed', ['response' => $response->json()]);
            return ['success' => false, 'error' => $error];
        } catch (\Exception $e) {
            Log::error('Paystack Service Error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Initialize Paystack Payment for Bookings
     */
    public function initializeBookingPaystack(\App\Models\Booking $booking)
    {
        try {
            $reference = 'BKG-' . time() . '-' . $booking->id;

            $response = Http::withoutVerifying()
                ->withToken(config('services.paystack.secret_key'))
                ->post(config('services.paystack.payment_url') . '/transaction/initialize', [
                    'amount' => $booking->payment_amount * 100,
                    'email' => $booking->email,
                    'reference' => $reference,
                    'callback_url' => route('payment.callback'),
                    'metadata' => [
                        'type' => 'booking',
                        'booking_id' => $booking->id,
                    ]
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $booking->update(['payment_id' => $data['data']['reference']]);
                return ['success' => true, 'url' => $data['data']['authorization_url']];
            }

            $error = $response->json()['message'] ?? 'Unknown Paystack error';
            Log::error('Paystack Booking Init Failed', ['response' => $response->json()]);
            return ['success' => false, 'error' => $error];
        } catch (\Exception $e) {
            Log::error('Paystack Booking Service Error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Initialize Monnify Payment
     */
    public function initializeMonnify(Order $order)
    {
        try {
            // Monnify requires a Bearer token which we get using API Key and Secret
            $token = $this->getMonnifyAccessToken();
            if (!$token)
                return ['success' => false, 'error' => 'Failed to authenticate with Monnify'];

            $response = Http::withoutVerifying()
                ->withToken($token)
                ->post(config('services.monnify.base_url') . '/api/v1/merchant/transactions/init-transaction', [
                    'amount' => $order->total_amount,
                    'customerName' => $order->user->name,
                    'customerEmail' => $order->user->email,
                    'paymentReference' => $order->order_number . '-' . time(),
                    'paymentDescription' => 'Order ' . $order->order_number,
                    'currencyCode' => 'NGN',
                    'contractCode' => config('services.monnify.contract_code'),
                    'redirectUrl' => route('payment.callback'),
                    'paymentMethods' => ['CARD', 'ACCOUNT_TRANSFER'],
                    'metadata' => [
                        'order_id' => $order->id,
                    ]
                ]);

            if ($response->successful()) {
                $data = $response->json();
                if ($data['requestSuccessful']) {
                    $order->update(['gateway_reference' => $data['responseBody']['transactionReference']]);
                    return ['success' => true, 'url' => $data['responseBody']['checkoutUrl']];
                }
            }

            $error = $response->json()['responseMessage'] ?? 'Unknown Monnify error';
            Log::error('Monnify Initialization Failed', [
                'status' => $response->status(),
                'response' => $response->json(),
                'url' => config('services.monnify.base_url') . '/api/v1/merchant/transactions/init-transaction'
            ]);
            return ['success' => false, 'error' => $error];
        } catch (\Exception $e) {
            Log::error('Monnify Service Error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Get Monnify Access Token
     */
    private function getMonnifyAccessToken()
    {
        try {
            $apiKey = config('services.monnify.api_key');
            $secretKey = config('services.monnify.secret_key');
            $auth = base64_encode($apiKey . ':' . $secretKey);

            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Authorization' => 'Basic ' . $auth
                ])->post(config('services.monnify.base_url') . '/api/v1/auth/login');

            if ($response->successful()) {
                return $response->json()['responseBody']['accessToken'];
            }

            Log::error('Monnify Auth Failed', [
                'status' => $response->status(),
                'response' => $response->json(),
                'url' => config('services.monnify.base_url') . '/api/v1/auth/login'
            ]);
            return null;
        } catch (\Exception $e) {
            Log::error('Monnify Auth Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Verify Paystack Payment
     */
    public function verifyPaystack($reference)
    {
        try {
            $response = Http::withoutVerifying()
                ->withToken(config('services.paystack.secret_key'))
                ->get(config('services.paystack.payment_url') . '/transaction/verify/' . $reference);

            if ($response->successful()) {
                $data = $response->json();
                if ($data['data']['status'] === 'success') {
                    return $data['data'];
                }
            }
            return false;
        } catch (\Exception $e) {
            Log::error('Paystack Verification Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Verify Monnify Payment
     */
    public function verifyMonnify($reference)
    {
        try {
            $token = $this->getMonnifyAccessToken();
            if (!$token)
                return false;

            $response = Http::withoutVerifying()
                ->withToken($token)
                ->get(config('services.monnify.base_url') . '/api/v1/merchant/transactions/query?transactionReference=' . urlencode($reference));

            if ($response->successful()) {
                $data = $response->json();
                if ($data['requestSuccessful'] && $data['responseBody']['paymentStatus'] === 'PAID') {
                    return $data['responseBody'];
                }
            }
            return false;
        } catch (\Exception $e) {
            Log::error('Monnify Verification Error: ' . $e->getMessage());
            return false;
        }
    }
}
