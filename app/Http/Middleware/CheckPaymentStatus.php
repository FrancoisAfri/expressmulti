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

        //get logged in client vat
        $vat = 'get vat ';
        $paymentAmount = Patient::getPaymentStatus();

        // If the payment amount is zero, redirect to 'please-pay' route
        if ($paymentAmount === 0) {
            return redirect()->route('please.pay');
        }

        return $next($request);
    }

    /**
     * Get the payment amount for the user.
     *
     * @return float
     */
    protected function getPaymentAmount()
    {
        // This method should return the payment amount for the authenticated user
        // You might need to replace this with the actual logic to get the payment amount
        return Auth::user()->payment_amount ?? 0;
    }
}
