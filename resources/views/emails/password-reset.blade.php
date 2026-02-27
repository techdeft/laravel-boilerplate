<x-emails.layouts.app>
    <div style="padding: 20px; color: #374151; font-family: 'Inter', sans-serif; line-height: 1.6;">
        <h2 style="color: #111827; font-size: 24px; font-weight: 700; margin-bottom: 16px;">
            Reset Your Password
        </h2>

        <p style="margin-bottom: 24px;">
            Hello {{ $user->name }},
        </p>

        <p style="margin-bottom: 24px;">
            You are receiving this email because we received a password reset request for your account. If you did not
            request a password reset, no further action is required.
        </p>

        <div style="text-align: center; margin-bottom: 32px;">
            <a href="{{ $resetUrl }}"
                style="display: inline-block; padding: 12px 24px; background-color: #2563eb; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 16px; transition: background-color 0.2s;">
                Reset Password
            </a>
        </div>

        <p style="margin-bottom: 16px; font-size: 14px; color: #6b7280;">
            This password reset link will expire in 60 minutes.
        </p>

        <hr style="border: 0; border-top: 1px solid #e5e7eb; margin: 32px 0;">

        <p style="font-size: 12px; color: #9ca3af; line-height: 1.4;">
            If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web
            browser:
            <br>
            <a href="{{ $resetUrl }}" style="color: #2563eb; word-break: break-all;">{{ $resetUrl }}</a>
        </p>
    </div>
</x-emails.layouts.app>