# Browser Sessions

## Install

Publish & migrate

`php artisan vendor:publish --provider="Lumby\BetterBrowserSessions\SessionsServiceProvider"`

`php artisan migrate`

Add to `app/Http/Kernel.php` `protected $middleware` array:

 `\Lumby\BetterBrowserSessions\Http\Middleware\TrackSessions::class,`
