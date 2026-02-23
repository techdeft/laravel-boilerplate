<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to {{ config('app.name') }}</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #f4f7fa;
            color: #1a202c;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .header {
            background-color: #4f46e5;
            padding: 40px 20px;
            text-align: center;
        }

        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -0.025em;
        }

        .content {
            padding: 40px;
        }

        .greeting {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #111827;
        }

        .message {
            margin-bottom: 30px;
            color: #4b5563;
        }

        .button-container {
            text-align: center;
            margin: 40px 0;
        }

        .button {
            background-color: #4f46e5;
            color: #ffffff;
            padding: 14px 32px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            display: inline-block;
            transition: background-color 0.2s;
        }

        .footer {
            padding: 30px;
            text-align: center;
            font-size: 14px;
            color: #9ca3af;
            background-color: #f9fafb;
        }

        .footer p {
            margin: 5px 0;
        }

        .footer a {
            color: #4f46e5;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>{{ config('app.name') }}</h1>
        </div>
        <div class="content">
            <div class="greeting">Hi {{ $user->name }},</div>
            <div class="message">
                <p>Welcome to <strong>{{ config('app.name') }}</strong>! We're thrilled to have you join our community.
                </p>
                <p>Our goal is to provide you with the best experience possible. To get started, click the button below
                    to visit your dashboard.</p>
            </div>
            <div class="button-container">
                <a href="{{ route('dashboard') }}" class="button">Go to Dashboard</a>
            </div>
            <div class="message">
                <p>If you have any questions, feel free to reply to this email our support team is always here to help.
                </p>
                <p>Cheers,<br>The {{ config('app.name') }} Team</p>
            </div>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p>You received this email because you recently created an account on our platform.</p>
            <p><a href="{{ config('app.url') }}">Visit our website</a></p>
        </div>
    </div>
</body>

</html>