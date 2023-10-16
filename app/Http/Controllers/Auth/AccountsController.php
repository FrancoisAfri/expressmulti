<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AccountsController extends Controller
{

    public function resetPassword(Request $request)
    {


        //Validate input
        $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:users,email',
                'password' => 'required|confirmed',
                'token' => 'required'
            ]
        );

        //check if payload is valid before moving on
        if ($validator->fails()) {
            return redirect()->back()->withErrors(
                [
                    'email' => 'Oops something went wrong, Try again'
                ]
            );
        }



        $password = $request->password;// Validate the token

        $tokenData = DB::table('password_resets')
            ->where('token', $request->token)->first();//
        // Redirect the user back to the password reset request form if the token is invalid
        if (!$tokenData) return view('auth.passwords.email');



        $user = User::where('email', $tokenData->email)->first();
// Redirect the user back if the email is invalid
        if (!$user) return redirect()->back()->withErrors(['email' => 'Email not found']);
        //Hash and update the new password
        $user->password = \Hash::make($password);
        $user->update(); //or $user->save();

        //login the user immediately they change password successfully
        Auth::login($user);


        Session::put('user_2fa', auth()->user()->id);

        $user->update([
            'last_login_at' => Carbon::now()->toDateTimeString(),
            'last_login_ip' => $request->getClientIp()
        ]);

        //Delete the token
        DB::table('password_resets')->where('email', $user->email)
            ->delete();

        return redirect('/')->with("success", "Password changed successfully !");

        //Send Email Reset Success Email
//        if ($this->sendSuccessEmail($tokenData->email)) {
//            return view('index');
//        } else {
//            return redirect()->back()->withErrors(
//                [
//                    'email' => trans('A Network Error occurred. Please try again.')
//                ]);
//        }

    }

    public function validatePasswordRequest(Request $request): \Illuminate\Http\RedirectResponse
    {

//        $user = DB::table('users')->where('email', '=', $request->email)
//            ->first();//Check if the user exists

        $user = User::where('email', $request->email)->first();

        if (!!empty($user)) {
            return redirect()->back()->withErrors(['email' => trans('User does not exist')]);
        }

        $token = str::random(60);

        //Create Password Reset Token
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);


        //Get the token just created above
        $tokenData = DB::table('password_resets')
            ->where('email', $request->email)->first();



        try {
            $user->sendPasswordResetNotification($token);
            return redirect()->back()->with("success", "A reset link has been sent to your email address.");
        } catch (\Exception $e) {
            echo 'Error - ' . $e;
            return redirect()->back()->with("error", "A Network Error occurred. Please try again.");
        }

    }

    private function sendResetEmail($email, $token)
    {//Retrieve the user from the database
        $user = User::where('email', $email)
            ->select('name', 'email')
            ->first();


        //Generate, the password reset link. The token generated is embedded in the link$link = config('base_url') .
        // 'password/reset/' . $token . '?email=' . urlencode($user->email);

        try {
            //Here send the link with CURL with an external email API         return true;
        } catch (\Exception $e) {
            return false;
        }
    }


}
