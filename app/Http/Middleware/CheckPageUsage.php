<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class CheckPageUsage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $pageKey = 'page_' . $request->getPathInfo();

        // Check if the page is currently being used
        if (Cache::has($pageKey)) {
            // If the page is being used, check if the current session is the one that started it
            $currentPageUser = Cache::get($pageKey);
            $currentUser = Session::getId();

            if ($currentPageUser !== $currentUser) {
                return response('This page is currently being used. Only one user can access it at a time.', 403);
            }
        }

        // Mark the page as being used by the current session
        Cache::put($pageKey, Session::getId(), now()->addMinutes(5));

        // Proceed to the next middleware or controller
        return $next($request);
    }
}
