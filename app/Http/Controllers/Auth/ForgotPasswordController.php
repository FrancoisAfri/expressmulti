<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\CompanyIdentity;
use App\Models\OldPasswords;
use App\Models\PasswordHistory;
use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    public function showBackgroundImage(): string
    {
        $companyDetails = CompanyIdentity::first();
        return (!empty($companyDetails['login_background_image'])) ?
            asset('uploads/' . $companyDetails['login_background_image']) : asset('images/bg-auth.jpg');
    }

    /**
     * @return Application|Factory|View
     */
    public function showLinkRequestForm()
    {
        $loginBackground = $this->showBackgroundImage();
        return view('auth.passwords.email')->with(
            [
                'loginBackground' => $loginBackground,
            ]
        );
    }


    public function showForgetPasswordForm()
    {
        $companyDetails = CompanyIdentity::getCompanyDetails();
        return view('auth.passwords.forgetPassword', compact('companyDetails'));

    }

    public function submitForgetPasswordForm(Request $request): RedirectResponse
    {

        $request->validate([
            'email' => 'required|email|exists:users',
        ]);


        $token = str::random(60);
        //Create Password Reset Token
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        $userSchema = User::getUserDetails($request->email);

        try {
            $userSchema->sendPasswordResetNotification($token);
            return redirect()->back()->with("success", "A reset link has been sent to your email address.");
        } catch (\Exception $e) {
            echo 'Error - ' . $e;
            return redirect()->back()->with("error", "A Network Error occurred. Please try again.");
        }

    }

    public function sendResetDetails(Request $request, $password, $user)
    {

//        dd($user);

        $token = str::random(60);
        //Create Password Reset Token
        DB::table('password_resets')->insert([
            'email' => $request['email'],
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        //Get the token just created above

        $userSchema = User::where('email', $request['email'])
            ->select('id', 'name', 'email')
            ->first();

        $temp_password = $password;

        //Here send the link with CURL with an external email API return true;
        $userSchema->sendNewUserPasswordResetNotification($token, $temp_password, $user);
    } 
	
	public function sendResetEmail($email, $password, $user, $domain)
    {
        $token = str::random(60);
        //Create Password Reset Token
        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        //Get the token just created above

        $userSchema = User::where('email', $email)
            ->select('id', 'name', 'email')
            ->first();

        $temp_password = $password;

        //Here send the link with CURL with an external email API return true;
        $userSchema->sendNewUserPasswordResetNotification($token, $temp_password, $user, $domain);
    }


    public function showResetPasswordForm($token)
    {
		
        $email = db::table('password_resets')
            ->where('token', $token)
            ->first();

        $loginBackground = $this->showBackgroundImage();

           return view('auth.passwords.forgotPasswordReset', [
               'loginBackground' => $loginBackground,
               'token' => $token,
               'email' => $email->email ?? ''
           ]);

    }



    /**
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse|Redirector
     */
    public function submitResetPasswordForm(Request $request)
    {

        $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:users',
                'password' => 'required',
                'password_confirmation' => 'required|string|min:6|confirm'
            ]
        );


//        if (!(Hash::check($request->get('password'), Auth::user()->password))) {
//            // The passwords matches
//            return redirect()->back()->with("error", "Your current password does not matches with the password you provided. Please try again.");
//        }

        if (strcmp($request->get('password'), $request->get('password_confirmation')) == 0) {
            //Current password and new password are same
            return redirect()->back()->with("error", "Please Enter a correct Temporary Password.");
        }

        //Validate input


        //CHECK IF password has been used before


        $passwordHistories = PasswordHistory::select('password')->get();
        foreach ($passwordHistories as $passwordHistory) {

            if (Hash::check($request->get('password_confirmation'), $passwordHistory->password)) {
                // The passwords matches
                return redirect()->back()->with("error", "Your new password can not be same as any of your recent passwords. Please choose a new password.");
            }
        }


        // Validate the token
        $tokenData = DB::table('password_resets')
            ->where('token', $request->token)->first();//
        // Redirect the user back to the password reset request form if the token is invalid
        if (!$tokenData) return redirect()->back()->with("error", "Expired Session, please Contact the Admin for a new Reset Link.");

        $user = User::where('email', $tokenData->email)->first();


        // Redirect the user back if the email is invalid
        if (!$user) return redirect()->back()->withErrors(['email' => 'Email not found']);
        //Hash and update the new password
        $user->password = Hash::make($request->password);
        $user->update(); //or $user->save();

        $passwordHistory = PasswordHistory::create([
            'user_id' => $user->id,
            'password' => bcrypt($request->get('new-password'))
        ]);

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

    }


}
