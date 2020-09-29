<?php

namespace Lumby\BetterBrowserSessions\Http\Middleware;

use Lumby\BetterBrowserSessions\Models\BrowserSession;
use Lumby\BetterBrowserSessions\SessionTracker;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Http\Request;
use Illuminate\Support\InteractsWithTime;
use Closure;

class TrackSessions
{
    use InteractsWithTime;

    /**
     * The guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(AuthFactory $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if($this->auth->check()) {
            if($sessionId = $request->cookies->get('auth_session')) {

                $session = BrowserSession::where('uuid', $sessionId)->first();

                if($session) {
                    SessionTracker::refresh($session);
                } else {
                    $this->logout($request);
                }
            } else {
                $this->logout($request);
            }
        }
        $this->collectGarbage();

        return $next($request);
    }

    protected function logout(Request $request)
    {
        // dd($this->auth->user());
        auth('web')->logout();

        $request->session()->flush();

        throw new AuthenticationException('Unauthenticated.', [
            $this->auth->getDefaultDriver()
        ]);
    }

    /**
     * Remove the garbage from the session if necessary.
     * From Middleware/StartSession.php
     *
     * @return void
     */
    protected function collectGarbage()
    {
        $config = config('session');

        // Here we will see if this request hits the garbage collection lottery by hitting
        // the odds needed to perform garbage collection on any given request. If we do
        // hit it, we'll call this handler to let it delete all the expired sessions.
        if ($this->configHitsLottery($config)) {
            SessionTracker::gc(($config['lifetime'] ?? null) * 60);
        }
    }

    /**
     * Determine if the configuration odds hit the lottery.
     *
     * @param  array  $config
     * @return bool
     */
    protected function configHitsLottery(array $config)
    {
        return random_int(1, $config['lottery'][1]) <= $config['lottery'][0];
    }

}
