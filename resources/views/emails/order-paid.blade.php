<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f9fafb; margin: 0; padding: 0; color: #111827; }
        .container { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }
        .header { background: #1e3a8a; padding: 40px; text-align: center; color: #ffffff; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 800; letter-spacing: -0.025em; }
        .content { padding: 40px; }
        .order-summary { background: #f3f4f6; border-radius: 12px; padding: 24px; margin: 24px 0; }
        .order-summary h2 { margin-top: 0; font-size: 18px; font-weight: 700; border-bottom: 2px solid #e5e7eb; padding-bottom: 12px; }
        .item { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px; }
        .total { margin-top: 16px; padding-top: 16px; border-top: 2px solid #e5e7eb; display: flex; justify-content: space-between; font-weight: 800; font-size: 18px; color: #1e3a8a; }
        .footer { padding: 24px; text-align: center; font-size: 12px; color: #6b7280; border-top: 1px solid #f3f4f6; }
        .button { display: inline-block; background: #1e3a8a; color: #ffffff; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 700; margin-top: 24px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Order Confirmed!</h1>
            <p>Thank you for your purchase, {{ $order->user->name }}</p>
        </div>
        <div class="content">
            <p>Hi {{ $order->user->name }},</p>
            <p>We've received your payment for order <strong>#{{ $order->order_number }}</strong>. Our team is now processing your request and preparing your items for delivery.</p>
            
            <div class="order-summary">
                <h2>Order Details</h2>
                @foreach($order->items as $item)
                    <div class="item">
                        <span>{{ $item->product->name }} x {{ $item->quantity }}</span>
                        <span>₦{{ number_format($item->price * $item->quantity, 2) }}</span>
                    </div>
                @endforeach
                <div class="total">
                    <span>Total Amount</span>
                    <span>₦{{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>

            <p>You can track your order status by clicking the button below:</p>
            <div style="text-align: center;">
                <a href="{{ route('customer.order-details', $order->order_number) }}" class="button">View Order Details</a>
            </div>

            <p style="margin-top: 32px;">If you have any questions, feel free to reply to this email or contact our support team.</p>
            <p>Best regards,<br>The {{ config('app.name') }} Team</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
