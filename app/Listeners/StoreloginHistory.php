<?php

namespace App\Listeners;

use App\Events\UserLoginHistory;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreloginHistory
{
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
     * @param  \App\Events\UserLoginHistory  $event
     * @return void
     */
    public function handle(UserLoginHistory $event)
    {
        $loginTime = Carbon::now()->timestamp;
        $userDetails = $event->user;

        dd('test');
      //  $hhd =  \Request::ip();

        $input['name'] = $userDetails->name;
        $input['email'] = $userDetails->email;
        $input['login_time'] = $loginTime;

        return UserLoginHistory::create($input);
    }
}
