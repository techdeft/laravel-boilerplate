<?php

namespace App\Services;

use App\Contracts\SmsProviderInterface;

class SmsService
{
    protected SmsProviderInterface $provider;

    public function __construct(SmsProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    public function send(string $to, string $message): array
    {
        // you can add logging, formatting, validation here
        return $this->provider->send($to, $message);
    }
}