<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\CompanyIdentity;
use App\Models\HRPerson;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\AuthService;
use App\Services\CommunicationService;
use Exception;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
//    protected $redirectTo = RouteServiceProvider::HOME;
    /**
     * @var CommunicationService
     */
    private $communicationService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CommunicationService $communicationService)
    {
        $this->communicationService = $communicationService;

        $this->middleware('guest')->except(
            [
                'logout',
                'locked',
                'unlock'
            ],
        );
    }

    protected $maxLoginAttempts = 3; // Amount of bad attempts user can make
    protected $lockoutTime = 300;


    /**
     * @return string
     */
    public function phone()
    {
        return 'phone_number';
    }

    /**
     * @param Request $request
     * @return mixed
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            'username' => [trans('auth.failed')],
        ]);
    }


    /**
     * @throws ValidationException
     */
    public function username()
    {

        $login = request()->input('username');

        if (is_numeric($login)) {
            $field = 'phone_number';
        } elseif (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
        } else {
            $field = 'username';
        }

        request()->merge([$field => $login]);

        return $field;

    }

    /**
     * @param Request $request
     * @return array
     */

    public function locked()
    {

        $companyDetails = CompanyIdentity::first();
        $val = new AuthService();
        // get name of user logged in
        $user = Auth::user()->load('person');
        $firstName = !empty($user->person->first_name) ? ucfirst($user->person->first_name) : '';
        $data['avatar'] = $val->locked();
        $data['companyDetails'] = $companyDetails;
        $data['firstName'] = $firstName;
        $data['loginBackground'] = $this->showBackgroundImage();
        return view('auth.locked')->with($data);
    }

    public function unlock(Request $request)
    {
        $Drop = new AuthService();

        return $Drop->unlock($request);

    }

    public function showLoginForm()
    {
        $companyDetails = CompanyIdentity::first();

        $loginBackground = $this->showBackgroundImage();
        return view('auth.login', compact('companyDetails','loginBackground'));
    }

    public function showBackgroundImage(): string
    {
        $companyDetails = CompanyIdentity::first();
        return (!empty($companyDetails['login_background_image'])) ?
            asset('uploads/'.$companyDetails['login_background_image'] ) : asset('images/bg-auth.jpg');
    }

    public function authenticated(Request $request, $user)
    {
        $role = $user->roles->first()->id;
        switch ($role) {
            case 1:
                return redirect(RouteServiceProvider::HOME);
                break;
            case 3:
                return redirect('/billing/accounts');
                break;
            case 4:
//                return 'fuck y';
                return redirect('/patients/booking_calender');
//                return '/patients/booking_calender';
                break;
            case 5:
//                return '/home';
                return redirect(RouteServiceProvider::HOME);
                break;
            default:
                return '/login';
                break;
        }


        $request->session()->forget('password_expired_id');

        $password_updated_at = $user->passwordSecurity->password_updated_at;
        $password_expiry_days = $user->passwordSecurity->password_expiry_days;
        $password_expiry_at = Carbon::parse($password_updated_at)->addDays($password_expiry_days);
        if ($password_expiry_at->lessThan(Carbon::now())) {
            $request->session()->put('password_expired_id', $user->id);
            auth()->logout();
            return redirect('/forget-password')->with('message', "Your Password is expired, You need to change your password.");
        }

        // check if user logged in using diffrent device
        /* $userIp = $request->getClientIp();
         $userDetIp = isset($user['last_login_ip']);

         if (!$userDetIp || $userIp == $user['last_login_ip']) {
             $user->update([
                 'last_login_at' => Carbon::now()->toDateTimeString(),
                 'last_login_ip' => $request->getClientIp()
             ]);

             Session::put('user_2fa', auth()->user()->id);
             return redirect()->intended($this->redirectPath());
         }else{
             if ($userIp != $user['last_login_ip']) {
                 Session::put('user_locked_out', auth()->user()->id);
                 $this->communicationService->generateOtp();
                 return redirect()->route('2fa.index');
             }
         }*/


    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }


    public function handleGoogleCallback()
    {
        try {
            //create a user using socialite driver google
            $user = Socialite::driver('google')->user();
            // if the user exits, use that user and login
            $finduser = User::where('google_id', $user->id)->first();
            if ($finduser) {
                //if the user exists, login and show dashboard
                Auth::login($finduser);
                return redirect('/');
            } else {
                //user is not yet created, so create first


                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id' => $user->id,
                    'password' => encrypt('12345678')
                ]);

                HRPerson::create([
                    'first_name' => $user->name,
                    'surname' => $user->given_name,
                    'email' => $user->email,
                    'user_id' => $newUser->id,
                    'status' => 1
                ]);


                //every user needs a team for dashboard/jetstream to work.

                // save the team and add the team to the user.
//                $newTeam->save();
//                $newUser->current_team_id = $newTeam->id;
//                $newUser->save();
                //login as the new user
                Auth::login($newUser);
                // go to the dashboard
                return redirect('/');
            }
            //catch exceptions
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }


    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();

    }


    public function handleFacebookCallback()
    {

        try {
            $user = Socialite::driver('facebook')->user();

            $saveUser = User::updateOrCreate([
                'facebook_id' => $user->getId(),
            ], [
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'password' => Hash::make($user->getName() . '@' . $user->getId())
            ]);

            Auth::loginUsingId($saveUser->id);

            return redirect()->route('home');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
