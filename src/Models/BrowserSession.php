<?php

namespace Lumby\BetterBrowserSessions\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class BrowserSession extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    protected $primaryKey = 'uuid';

    public $incrementing = false;

    protected $keyType = 'string';

    public function refresh()
    {
        $this->update([
            'last_activity' => Carbon::now()->getTimestamp()
        ]);
    }
}
