<?php

namespace Tests\Feature;

use App\Jobs\Email\SendEmailJob;
use App\Mail\WelcomeMail;
use App\Models\EmailLog;
use App\Models\User;
use App\Services\Email\EmailService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class EmailServiceTest extends TestCase
{
    use RefreshDatabase;

    protected EmailService $emailService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->emailService = new EmailService();
    }

    public function test_it_logs_email_with_version_and_dispatches_job()
    {
        Bus::fake();
        $user = User::factory()->create();
        $mailable = new WelcomeMail($user);

        $this->emailService->send($user->email, $mailable, 'Test Template');

        $this->assertDatabaseHas('email_logs', [
            'to' => $user->email,
            'template' => 'Test Template',
            'version' => 'v1',
            'status' => 'pending',
        ]);

        Bus::assertDispatched(SendEmailJob::class, function ($job) use ($user) {
            return $job->to === $user->email;
        });
    }

    public function test_it_fails_validation_for_invalid_email()
    {
        Bus::fake();
        Log::shouldReceive('error')->once()->withArgs(fn($msg) => str_contains($msg, 'Invalid email address'));

        $user = User::factory()->create();
        $mailable = new WelcomeMail($user);

        $this->emailService->send('invalid-email', $mailable, 'Test Template');

        $this->assertDatabaseMissing('email_logs', [
            'to' => 'invalid-email',
        ]);

        Bus::assertNotDispatched(SendEmailJob::class);
    }

    public function test_password_reset_mail_rendering()
    {
        $user = User::factory()->create(['name' => 'John Doe']);
        $resetUrl = 'http://localhost/reset-password/token';
        $mailable = new \App\Mail\PasswordResetMail($user, $resetUrl);

        $mailable->assertSeeInHtml('Reset Your Password');
        $mailable->assertSeeInHtml('Hello John Doe');
        $mailable->assertSeeInHtml($resetUrl);
    }

    public function test_send_email_job_sends_email_and_updates_log()
    {
        Mail::fake();
        $user = User::factory()->create();
        $mailable = new WelcomeMail($user);
        $log = EmailLog::create([
            'to' => $user->email,
            'template' => 'Test Template',
            'version' => 'v1',
            'subject' => 'Test Subject',
            'status' => 'pending',
        ]);

        $job = new SendEmailJob($user->email, $mailable, $log->id);
        $job->handle();

        Mail::assertSent(WelcomeMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });

        $this->assertEquals('sent', $log->fresh()->status);
        $this->assertNotNull($log->fresh()->sent_at);
    }
}
