<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function handleGatewayCallback(Request $request, CartService $cartService, \App\Services\PaymentService $paymentService, \App\Services\Email\EmailService $emailService)
    {
        // Paystack uses 'reference' in query
        // Monnify uses 'paymentReference' or 'transactionReference' in query
        $reference = $request->query('reference') ?? $request->query('transactionReference') ?? $request->query('paymentReference');

        if (!$reference) {
            return redirect()->route('customer.cart')->with('error', 'No payment reference found.');
        }

        try {
            $paymentData = null;
            $gateway = 'paystack';

            // Reliable gateway detection based on query parameters
            if ($request->has('transactionReference') || $request->has('paymentReference')) {
                $gateway = 'monnify';
                $paymentData = $paymentService->verifyMonnify($reference);
            } else {
                $gateway = 'paystack';
                $paymentData = $paymentService->verifyPaystack($reference);
            }

            if ($paymentData) {
                // Check metadata to determine type
                $type = 'order';
                $modelId = null;

                if ($gateway === 'paystack') {
                    $type = $paymentData['metadata']['type'] ?? 'order';
                    $modelId = $paymentData['metadata']['order_id'] ?? $paymentData['metadata']['booking_id'] ?? null;
                } else {
                    $type = $paymentData['metaData']['type'] ?? $paymentData['metadata']['type'] ?? 'order';
                    $modelId = $paymentData['metaData']['order_id'] ?? $paymentData['metadata']['order_id'] ?? null;
                }

                Log::info('Payment validation successful', [
                    'gateway' => $gateway,
                    'reference' => $reference,
                    'type' => $type,
                    'model_id' => $modelId
                ]);

                if ($type === 'booking') {
                    $booking = \App\Models\Booking::where('payment_id', $reference)
                        ->orWhere('id', $modelId)
                        ->first();

                    if ($booking) {
                        $booking->update([
                            'payment_status' => 'paid',
                            'booking_status' => 'confirmed'
                        ]);

                        // Send Booking Confirmation Email
                        try {
                            $emailService->send($booking->email, new \App\Mail\BookingConfirmationMail($booking));
                            Log::info('Booking confirmation email queued', ['booking_id' => $booking->id]);
                        } catch (\Exception $e) {
                            Log::error('Failed to send booking confirmation email', [
                                'booking_id' => $booking->id,
                                'error' => $e->getMessage()
                            ]);
                        }

                        return redirect()->route('home')->with('success', 'Consultation booked successfully! We will contact you with the meeting details.');
                    }
                } else {
                    $order = Order::find($modelId);
                    
                    if (!$order) {
                        $order = Order::where('gateway_reference', $reference)
                            ->orWhere('order_number', $reference)
                            ->first();
                    }

                    // Last resort: extract order number from composite reference (ORD-XXXX-TIME-TIME)
                    if (!$order && str_contains($reference, '-')) {
                        $parts = explode('-', $reference);
                        if (count($parts) >= 3) {
                            $potentialOrderNumber = $parts[0] . '-' . $parts[1] . '-' . $parts[2];
                            $order = Order::where('order_number', $potentialOrderNumber)->first();
                        }
                    }

                    if ($order) {
                        $order->update([
                            'payment_status' => 'paid',
                            'status' => 'processing'
                        ]);

                        $cartService->clear();

                        // Send Order Confirmation Email
                        try {
                            $emailService->send($order->user->email, new \App\Mail\OrderPaidMail($order));
                            Log::info('Order confirmation email queued', ['order_id' => $order->id]);
                        } catch (\Exception $e) {
                            Log::error('Failed to send order confirmation email', [
                                'order_id' => $order->id,
                                'error' => $e->getMessage()
                            ]);
                        }

                        return redirect()->route('customer.order-success', $order->order_number)
                            ->with('success', 'Payment successful!');
                    }
                }
            }

            Log::error($gateway . ' validation failed', ['reference' => $reference]);

            if (str_starts_with($reference, 'BKG-')) {
                return redirect()->route('booking.create')->with('error', 'Payment verification failed or timed out. Please try again or contact support.');
            }

            return redirect()->route('customer.checkout')->with('error', 'Payment verification failed.');

        } catch (\Exception $e) {
            Log::error('Payment callback error: ' . $e->getMessage());

            if (isset($reference) && str_starts_with($reference, 'BKG-')) {
                return redirect()->route('booking.create')->with('error', 'An error occurred during payment verification.');
            }

            return redirect()->route('customer.checkout')->with('error', 'An error occurred during payment verification.');
        }
    }
}
