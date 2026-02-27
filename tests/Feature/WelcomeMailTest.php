<?php

namespace Tests\Feature;

use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class WelcomeMailTest extends TestCase
{
    use RefreshDatabase;

    public function test_welcome_email_is_sent_on_registration(): void
    {
        Mail::fake();

        $user = User::factory()->create([
            'email' => 'test@example.com',
            'name' => 'Test User',
        ]);

        event(new Registered($user));

        Mail::assertSent(WelcomeMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email) && $mail->user->id === $user->id;
        });
    }

    public function test_welcome_email_has_personalization(): void
    {
        $user = User::factory()->make([
            'name' => 'John Doe',
        ]);

        $mailable = new WelcomeMail($user);

        $mailable->assertSeeInHtml('Hello John Doe');
        $mailable->assertSeeInHtml(config('app.name'));
        $mailable->assertHasSubject('Welcome to ' . config('app.name') . '!');
    }
}
