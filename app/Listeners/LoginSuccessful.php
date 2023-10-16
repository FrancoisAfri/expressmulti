<?php

namespace App\Listeners;

use App\Events\UserloginHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Support\Facades\Session;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Activitylog\Models\Activity;

class LoginSuccessful
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }


    public function handle(Login $event)
    {
		$user = Auth::user()->load('person');
		$firstName = !empty($user->person->first_name) ? ucfirst($user->person->first_name) : '';
        $event->subject = 'login';
        $event->description = 'Login successful';

        Session::flash('login-success', 'Hi ' . $firstName . ', welcome back !');
        Alert::toast('Hi' . ' ' . $firstName . ', welcome back ! ', 'success');

        $event->subject = 'login';
        $event->description = 'Login successful';
//        Session::flash('login-success', 'Hello' . $event->user->name . ', welcome back !');
        activity($event->subject)
            ->by($event->user)
            ->log($event->description);
    }
}
