<?php

namespace App\Services\Providers;

use App\Contracts\SmsProviderInterface;
use Illuminate\Support\Facades\Http;

class TermiiService implements SmsProviderInterface
{
    public function send(string $to, string $message): array
    {
        $response = Http::post(config('services.termii.base_url') . '/api/sms/send', [
            "api_key" => config('services.termii.api_key'),
            "to" => $to,
            "from" => "talert",
            "sms" => $message,
            "type" => "plain",
            "channel" => "generic",
        ]);

        if ($response->failed()) {
            throw new \Exception($response->body());
        }

        return $response->json();
    }
}