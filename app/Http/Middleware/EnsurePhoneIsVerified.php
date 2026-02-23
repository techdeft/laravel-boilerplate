<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePhoneIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (
            !$request->user() ||
            !$request->user()->hasVerifiedPhone()
        ) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Your phone number is not verified.'], 403)
                : redirect()->route('verification.phone.notice');
        }

        return $next($request);
    }

}
