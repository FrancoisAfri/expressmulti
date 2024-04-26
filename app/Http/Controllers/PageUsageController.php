<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PageUsageController extends Controller
{
    public function logoutOtherUser(Request $request)
	{
		$loggedUserId = $request->input('logged_user_id');
		$pageKey = $request->input('page_key');
		
		// Remove the cache entry for the old user
		//$pageKey = 'page_' . $request->getPathInfo();
		Cache::forget($pageKey);

		// Mark the page as being used by the current user
		Cache::put($pageKey, Auth::user()->id, now()->addMinutes(5));

		// Redirect the user back to the page or wherever appropriate
		return redirect('/restaurant/terminal');
	}
}
