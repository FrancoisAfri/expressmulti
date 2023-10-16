<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserCode;
use App\Services\CommunicationService;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use function GuzzleHttp\Promise\all;

class TwoFactorAuthController extends Controller
{
    /**
     * Write code on Method
     * @return response()
     */
    public function index()
    {

        $value = session()->get('user_locked_out');
        $phone = User::getUserNumber($value);
        $phoneNumber = $phone->phone_number;

        return view('auth.Verify2FactAuth', compact('phoneNumber'));
    }


    /**
     * Write code on Method
     * @return response()
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required',
        ]);

        $userId =  session()->get('user_locked_out');

        $user = User::getUserById($userId);


        $verificationCode = UserCode::getOtpCode($request->code);


        $find = UserCode::getCode($userId);

        if (!is_null($find) && $verificationCode) {

            // Expire The OTP
            $verificationCode->update([
                'expire_at' => Carbon::now()
            ]);

            Auth::login(User::getUserById($userId));

            Session::put('user_2fa',$userId);


            Session::forget('user_locked_out');

            $user->update([
                'last_login_at' => Carbon::now()->toDateTimeString(),
                'last_login_ip' => $request->getClientIp()
            ]);

            return redirect()->route('home');
        }
        return back()->with('error', 'You entered wrong code.');

    }

    /**
     * Write code on Method
     * @return response()
     */
    public function resend(CommunicationService $communicationService)
    {
        $communicationService->generateOtp();
        return back()->with('success', 'We sent you code on your mobile number.');
    }




}
