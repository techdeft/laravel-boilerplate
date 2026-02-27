<?php

namespace App\Jobs\Email;

use App\Models\EmailLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $to,
        public Mailable $mailable,
        public ?int $emailLogId = null
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $log = null;
        if ($this->emailLogId) {
            $log = EmailLog::find($this->emailLogId);
        }

        try {
            Mail::to($this->to)->send($this->mailable);

            if ($log) {
                $log->update([
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error("Failed to send email to {$this->to}: " . $e->getMessage());

            if ($log) {
                $log->update([
                    'status' => 'failed',
                    'error' => $e->getMessage(),
                ]);
            }

            throw $e;
        }
    }
}
