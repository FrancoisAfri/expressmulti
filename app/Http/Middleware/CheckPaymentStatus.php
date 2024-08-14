<?php

namespace App\Http\Middleware;

use App\Models\Patient;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $client = Patient::latest()->first();
        $status = $client->load('packages','contacts');

        if ($status->payment_status === 0) {
            return redirect()->route('editCompany');
        }

        return $next($request);
    }

}
