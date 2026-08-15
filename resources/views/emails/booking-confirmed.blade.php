<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f9fafb;
            margin: 0;
            padding: 0;
            color: #111827;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .header {
            background: #2b1770;
            padding: 40px;
            text-align: center;
            color: #ffffff;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.025em;
        }

        .content {
            padding: 40px;
        }

        .booking-details {
            background: #f3f4f6;
            border-radius: 12px;
            padding: 24px;
            margin: 24px 0;
        }

        .booking-details h2 {
            margin-top: 0;
            font-size: 18px;
            font-weight: 700;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 12px;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 14px;
        }

        .detail-label {
            color: #6b7280;
            font-weight: 500;
        }

        .detail-value {
            font-weight: 700;
            color: #111827;
        }

        .footer {
            padding: 24px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            border-top: 1px solid #f3f4f6;
        }

        .button {
            display: inline-block;
            background: #2b1770;
            color: #ffffff;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 700;
            margin-top: 24px;
        }

        .meet-link {
            background: #ecfdf5;
            border: 1px solid #10b981;
            color: #065f46;
            padding: 16px;
            border-radius: 8px;
            margin-top: 24px;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Booking Confirmed!</h1>
            <p>Your consultation is scheduled</p>
        </div>
        <div class="content">
            <p>Hi {{ $booking->name }},</p>
            <p>Thank you for choosing Medmall. Your consultation payment has been verified, and your booking is now
                confirmed.</p>

            <div class="booking-details">
                <h2>Booking Information</h2>
                <div class="detail-item">
                    <span class="detail-label">Booking ID</span>
                    <span class="detail-value">#{{ $booking->id }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Consultation Type</span>
                    <span
                        class="detail-value text-capitalize">{{ str_replace('_', ' ', $booking->booking_type) }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Date</span>
                    <span
                        class="detail-value">{{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Time</span>
                    <span
                        class="detail-value">{{ \Carbon\Carbon::parse($booking->booking_time)->format('h:i A') }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Contact Method</span>
                    <span class="detail-value text-capitalize">{{ $booking->contact_method }}</span>
                </div>
            </div>

            @if($booking->meet_link)
                <div class="meet-link">
                    <strong>Meeting Link:</strong> Your online consultation will take place on Google Meet.
                    <a href="{{ $booking->meet_link }}" style="color: #059669; font-weight: 700;">Join here</a> at the
                    scheduled time.
                </div>
            @endif

            <p>Our pharmacist will contact you via {{ $booking->contact_method }} as scheduled. Please ensure you are
                available at the chosen time.</p>

            <p style="margin-top: 32px;">If you need to reschedule or have any questions, please contact our support
                team.</p>
            <p>Best regards,<br>The {{ config('app.name') }} Team</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>

</html>