<?php

namespace Lumby\BetterBrowserSessions\Listeners;

use Lumby\BetterBrowserSessions\SessionTracker;
use Illuminate\Support\InteractsWithTime;

class StopTrackingBrowserSession
{
    use InteractsWithTime;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        if($sessionId = request()->cookies->get('auth_session')) {
            SessionTracker::stop($sessionId);
        }
    }
}
