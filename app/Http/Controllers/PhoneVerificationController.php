<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\SmsService;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class PhoneVerificationController extends Controller
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function index()
    {
        $user = auth()->user();

        if ($user->hasVerifiedPhone()) {
            return redirect()->route('dashboard');
        }

        // Generate code if not exists or expired
        if (!$user->phone_verification_code || ($user->phone_verification_code_expired_at && Carbon::parse($user->phone_verification_code_expired_at)->isPast())) {
            $this->sendVerificationCode($user);
        }

        return view('auth.phone-verification');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $user = auth()->user();

        if ($user->phone_verification_code !== $request->code) {
            throw ValidationException::withMessages([
                'code' => ['The verification code is incorrect.'],
            ]);
        }

        if (Carbon::parse($user->phone_verification_code_expired_at)->isPast()) {
            throw ValidationException::withMessages([
                'code' => ['The verification code has expired.'],
            ]);
        }

        $user->markPhoneAsVerified();
        $user->forceFill([
            'phone_verification_code' => null,
            'phone_verification_code_expired_at' => null,
        ])->save();

        return redirect()->route('dashboard')->with('status', 'Phone number verified successfully.');
    }

    public function resend(Request $request)
    {
        $this->sendVerificationCode(auth()->user());

        return back()->with('status', 'A new verification code has been sent to your phone.');
    }

    protected function sendVerificationCode($user)
    {
        if (!$user->phone) {
            return;
        }

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user->forceFill([
            'phone_verification_code' => $code,
            'phone_verification_code_expired_at' => now()->addMinutes(10),
        ])->save();

        $this->smsService->send($user->phone, "Your verification code is: {$code}");
    }
}
