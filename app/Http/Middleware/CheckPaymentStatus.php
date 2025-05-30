<?php

namespace App\Http\Middleware;

use App\Models\Patient;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckPaymentStatus
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		// get central domain
        $centralDomains = env('CENTRAL_DOMAINS');
        // get host url
        $host = request()->getHost();
		if ($host !== $centralDomains)
        {
			$client = Patient::latest()->first();
			if (!empty($client))
			{
				$status = $client->load('packages','contacts');

				//get logged in client vat
				$vat = 'get vat ';
				//$paymentAmount = Patient::getPaymentStatus();
				$paymentAmount = 1;
				
				// If the payment amount is zero, redirect to 'please-pay' route
				//if ($paymentAmount === 0) {
				//    return redirect()->route('please.pay');
				if (!$status->is_active) {
					return redirect()->route('editCompany');
				}
			}
		}
        
		return $next($request);
		//}

	}
}