# Browser Sessions

## Install

Add to `app/Http/Kernel.php` `protected $middleware` array:

 `\Lumby\BetterBrowserSessions\Http\Middleware\TrackSessions::class,`
