<x-emails.layouts.app>
    <h2 style="margin-top: 0; color: #2d3748; font-size: 20px; font-weight: 600;">Hello, {{ $user->name }}!</h2>
    <p>Welcome to <strong>{{ config('app.name') }}</strong>. We're excited to have you on board!</p>
    <p>Your account has been successfully created. You can now log in and start exploring our platform.</p>

    <div style="margin: 30px 0; text-align: center;">
        <a href="{{ config('app.url') }}/login"
            style="background-color: #3b82f6; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: 600; display: inline-block;">Login
            to Dashboard</a>
    </div>

    <p>If you have any questions, feel free to reply to this email.</p>
    <p>Best regards,<br>The {{ config('app.name') }} Team</p>
</x-emails.layouts.app>