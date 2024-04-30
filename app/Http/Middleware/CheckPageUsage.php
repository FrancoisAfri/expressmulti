<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;								
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\User;
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
		$currentUser = !empty(Auth::user()->id) ? Auth::user()->id : 0;
        // Check if the page is currently being used
        if (Cache::has($pageKey)) {
            // If the page is being used, check if the current session is the one that started it
            $oldUser = Cache::get($pageKey);

            if ($oldUser !== $currentUser) {
                //return response('This page is currently being used. Only one user can access it at a time.', 403);
				// If another user is using the page, display their name and provide an option to log them out
                $oldUserDetails = User::where('id', $oldUser)->first(); // Assuming you are using Laravel's built-in authentication
                $oldUserDetails->load('person');
				//$oldUsername = $oldUserDetails->person->first_name;
				return response()->view('restaurant.page-in-use', ['otherUser' => $oldUserDetails, 'page_key' => $pageKey]);
            }
        }

        // Mark the page as being used by the current session
        Cache::put($pageKey, $currentUser, now()->addMinutes(5));

        // Proceed to the next middleware or controller
        return $next($request);
    }
}
