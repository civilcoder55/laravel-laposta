<?php

namespace App\Listeners;

use App\Notifications\LoginNotification;
use Carbon\Carbon;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use WhichBrowser\Parser;

class SuccessfulLoginListener
{

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle(Login $event)
    {
        //if new registered user , don't send notification
        if ($event->user->created_at->timestamp + 1 >= Carbon::now()->timestamp) {
            return;
        }
        $agent = new Parser($this->request->server('HTTP_USER_AGENT'));
        $from = $agent->browser->toString();
        $on = $agent->os->toString();
        $event->user->notify(new LoginNotification($from, $on)); // fire LoginNotification which store notification in database and brodcast it

    }
}
