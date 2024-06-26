<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthLockMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //dd(Auth::user()->hasLockedTime());
        // If the user does not have this feature enabled, then just return next.
        if (!Auth::user()->hasLockedTime()) {
            // Check if previous session was set, if so, remove it because we don't need it here.
            if (session('lock-expires-at')) {
                session()->forget('lock-expires-at');
            }

            return $next($request);
        }

        if ($lockExpiresAt = session('lock-expires-at')) {

            if ($lockExpiresAt < now())
			{
				//get current url
                $this->getCurrentUrl();
                return redirect()->route('login.locked');
            }
        }

        $request->user();
        session(['lock-expires-at' => now()->addMinutes(Auth::user()->getLockoutTime())]);

        return $next($request);
    }
	private function getCurrentUrl()
    {
        $currentUrl =  \url()->current();
        return session()->put('previous_url', $currentUrl);
    }
}
