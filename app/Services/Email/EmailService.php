<?php

namespace App\Services\Email;

use App\Jobs\Email\SendEmailJob;
use App\Models\EmailLog;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;

class EmailService
{
    /**
     * Send an email.
     *
     * @param string $to
     * @param Mailable $mailable
     * @param string|null $templateName
     * @return void
     */
    public function send(string $to, Mailable $mailable, ?string $templateName = null): void
    {
        // Validate email
        $validator = \Illuminate\Support\Facades\Validator::make(['email' => $to], [
            'email' => 'required|email:rfc,dns'
        ]);

        if ($validator->fails()) {
            Log::error("Invalid email address: {$to}. Errors: " . json_encode($validator->errors()->all()));
            return;
        }

        try {
            // Get version if available
            $version = method_exists($mailable, 'getVersion') ? $mailable->getVersion() : 'v1';

            // Log the email attempt
            $log = EmailLog::create([
                'to' => $to,
                'template' => $templateName ?? get_class($mailable),
                'version' => $version,
                'subject' => $mailable->envelope()->subject,
                'status' => 'pending',
            ]);

            // Dispatch the job
            dispatch(new SendEmailJob($to, $mailable, $log->id));

        } catch (\Exception $e) {
            Log::error("Failed to initiate email sending: " . $e->getMessage());
        }
    }
}
