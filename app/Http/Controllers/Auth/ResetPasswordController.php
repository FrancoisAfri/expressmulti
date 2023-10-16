<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\CompanyIdentity;
use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Override the show password reset trait
     * @param Request $request
     * @return Application|Factory|View
     */
    public function showResetForm(Request $request)
    {
        $companyDetails = CompanyIdentity::getCompanyDetails();
        $token = $request->route()->parameter('token');
        $loginBackground = $this->showBackgroundImage();

        return view('auth.passwords.reset')->with(
            [
                'companyDetails' => $companyDetails,
                'loginBackground' => $loginBackground,
                'token' => $token,
                'email' => $request->email
            ]
        );
    }

    public function showBackgroundImage(): string
    {
        $companyDetails = CompanyIdentity::first();
        return (!empty($companyDetails['login_background_image'])) ?
            asset('uploads/'.$companyDetails['login_background_image'] ) : asset('images/bg-auth.jpg');
    }

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
}
