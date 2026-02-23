<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\UserContextService;
use Illuminate\Http\Request;

class LogSuccessfulRegistration
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
    public function handle(Registered $event): void
    {
        // Skip for tests or console if no request is available
        if (!$this->request->route()) {
            return;
        }

        $context = $this->userContextService->getLoginContext($this->request);

        $user = $event->user;
        $user->registered_ip = $context['ip_address'];
        $user->registered_country = $context['country'];
        $user->device = $context['device'];
        $user->save();
    }
}
