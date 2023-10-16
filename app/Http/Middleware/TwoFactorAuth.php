<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;


class TwoFactorAuth
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return RedirectResponse|mixed
     */
    public function handle(Request $request, Closure $next)
    {

        /*if(!Session::has('user_2fa')){
            return redirect()->route('2fa.index');
        }*/

        return $next($request);
    }
}
