<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\UserLogins;
use App\Services\UserContextService;
use Illuminate\Http\Request;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     */
    public function __construct(protected Request $request, protected UserContextService $userContextService)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        // Skip for tests or console if no request is available
        if (!$this->request->route()) {
            return;
        }

        $context = $this->userContextService->getLoginContext($this->request);

        UserLogins::create([
            'user_id' => $event->user->id,
            'ip_address' => $context['ip_address'],
            'country' => $context['country'],
            'device' => $context['device'],
            'logged_in_at' => now(),
        ]);
    }
}
