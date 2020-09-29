<?php

namespace Lumby\BetterBrowserSessions;

use Carbon\Carbon;
use Lumby\BetterBrowserSessions\Models\BrowserSession;
use Str;

class SessionTracker
{

    public static function start($user, $remember = false) : BrowserSession
    {
        $sessionId = Str::uuid();

        $payload = [
            'uuid' => $sessionId,
            'last_activity' => Carbon::now()->getTimestamp(),
            'ip_address' => request()->ip(),
            'user_agent' => substr((string) request()->header('User-Agent'), 0, 500),
            'user_id' => $user->id,
            'remember' => $remember
        ];

        $session = BrowserSession::create($payload);

        cookie()->queue(
            cookie()->forever('auth_session', $session->uuid)
        );

        return $session;
    }

    public static function stop($sessionId)
    {
        cookie()->queue(
            cookie()->forget('auth_session')
        );

        BrowserSession::where('uuid', $sessionId)->delete();
    }

    public static function refresh(BrowserSession $session)
    {
        $session->refresh();
    }

    public static function gc($lifetime)
    {
        BrowserSession::where('last_activity', '<=', Carbon::now()->getTimestamp() - $lifetime)
            ->where('remember', false)
            ->delete();
    }
}
