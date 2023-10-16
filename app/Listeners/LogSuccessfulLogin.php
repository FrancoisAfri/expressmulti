<?php

namespace App\Listeners;

use App\Models\User;
use App\Models\UserLoginHistory;
use Carbon\Carbon;

use \Illuminate\Http\Request;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use RealRashid\SweetAlert\Facades\Alert;

class LogSuccessfulLogin
{


    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    public function handle(Login $event)
    {

        $Details = User::where('id', Auth::user()->load('person')->id)->first();

        $t = time();
        UserLoginHistory::create(
            [
                'userId' => $Details->id,
                'email' => $Details->email,
                'ip_address' => $this->request->ip(),
                'login_time' => Carbon::now()->toDateTimeString(),
            ]
        );


    }
}
