<?php

namespace App\Services;

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Models\CompanyIdentity;
use App\Models\HRPerson;
use App\Models\PasswordHistory;
use App\Models\PasswordSecurity;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class AuthService
{


    public function loginWithEmailOrPhone()
    {

        $login = request()->input('username');

        if(is_numeric($login)){
            $field = 'phone_number';
        } elseif (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
        } else {
            $field = 'username';
        }

        return $field;

    }

    public function Authentcated(Request $request, $user){

        $request->session()->forget('password_expired_id');

        $password_updated_at = $user->passwordSecurity->password_updated_at;
        $password_expiry_days = $user->passwordSecurity->password_expiry_days;
        $password_expiry_at = Carbon::parse($password_updated_at)->addDays($password_expiry_days);
        if($password_expiry_at->lessThan(Carbon::now())){
            $request->session()->put('password_expired_id',$user->id);
            auth()->logout();
            return redirect('/forget-password')->with('message', "Your Password is expired, You need to change your password.");
        }

        return redirect()->intended($this->redirectPath());
    }

    /**
     * @return Application|RedirectResponse|Redirector|string
     */
    public function locked()
    {

        $user = Auth::user()->load('person');
        $defaultAvatar = ($user->person->gender === 0) ? asset('images/m-silhouette.jpg') : asset('images/f-silhouette.jpg');
        $avatar = $user->person->profile_pic;

        $companyDetails = CompanyIdentity::first();

        if(empty($companyDetails->login_background_image)){
            $Background =  asset('images/bg-auth.jpg');
        }else{
            $Background =  asset('uploads/'.$companyDetails->login_background_image);
        }

        $loginBackground = $Background;

        return (!empty($avatar)) ? asset('uploads/' . $user->person->profile_pic) : $defaultAvatar;
    }

    /**
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function unlock(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        if (!Hash::check($request->input('password'), Auth::user()->password)) {
            return redirect()->route('login.locked')->withErrors([
                'password' => 'Your password does not match your profile.'
            ]);
        }

        $lockoutTime = Auth::user()->getLockoutTime();

        session(['lock-expires-at' => now()->addMinutes($lockoutTime)]);

        return redirect('/');
        //return redirect()->intended($this->previousUrl());
    }

    /**
     * @param $request
     * @return void
     */
    public function createUser($request)
    {

        $mobile = str_replace(['(', ')', ' ', '-'], ['', '', '', ''], $request->post('cell_number'));
        $request->merge(array('cell_number' => $mobile));


        $random_pass = Str::random(10);
        $password = Hash::make($random_pass);

        $user = User::create(
            [
                'name' => $request['first_name'],
                'email' => $request['email'],
                'password' => $password,
                'phone_number' => $mobile,
                'lockout_time' => 50,
                'type' => 0,
                'status' => 1,
            ]
        );

        $person = new HRPerson();
        $person->first_name = $request['first_name'];
        $person->surname = $request['surname'];
        $person->email = $request['email'];
        $person->cell_number = $request['cell_number'];
        $person->status = 1;
        $user->addPerson($person);


        PasswordHistory::createPassword($user->id ,$password);

        $token = str::random(60);
        //Create Password Reset Token
//        DB::table('password_resets')->insert([
//            'email' => $request['email'],
//            'token' => $token,
//            'created_at' => Carbon::now()
//        ]);

        //Assign roles
        $user->assignRole($request->input('roles'));
		  //
        PasswordSecurity::addExpiryDate($user->id);
		// if role is waiter save and database is not the main one save in the main with their database
		$centralDomains = env('CENTRAL_DOMAINS');
		$host = request()->getHost();
		if ($host !== $centralDomains && $request->input('roles') == 4)
		{
			$currentDatabaseName = DB::connection()->getDatabaseName();
			// tenant database configuration
			$tenantDatabaseConfig = [
				'driver'    => 'pgsql',
				'host'      => env('DB_HOST', '127.0.0.1'),
				'database'  => $currentDatabaseName,
				'username'  => env('DB_USERNAME'),
				'password'  => env('DB_PASSWORD'),
				'charset' => 'utf8',
				'prefix' => '',
				'prefix_indexes' => true,
				'schema' => 'public',
				'sslmode' => 'prefer',
			];
			// Temporarily switch to another database
			$DB_HOST = env('DB_HOST');
			$DB_PORT = env('DB_PORT');
			$DB_DATABASE = env('DB_DATABASE');
			$DB_USERNAME = env('DB_USERNAME');
			$DB_PASSWORD = env('DB_PASSWORD');

			\Config::set('database.connections.'.$DB_DATABASE, [
				'driver'    => env('DB_CONNECTION'),
				'host'      => env('DB_HOST'),
				'database'  => env('DB_DATABASE'),
				'username'  => env('DB_USERNAME'),
				'password'  => env('DB_PASSWORD'),
			]);

			DB::purge($DB_DATABASE);
			DB::reconnect($DB_DATABASE);

			// Set the connection to the other database
			DB::setDefaultConnection($DB_DATABASE);
			$currentDatabaseNames = DB::connection()->getDatabaseName();
			// create user in the main database
			$user = User::create(
				[
					'name' => $request['first_name'],
					'email' => $request['email'],
					'password' => $password,
					'phone_number' => $mobile,
					'database_name' => $currentDatabaseName,
					'lockout_time' => 50,
					'type' => 0,
					'status' => 1,
				]
			);

			$person = new HRPerson();
			$person->first_name = $request['first_name'];
			$person->surname = $request['surname'];
			$person->email = $request['email'];
			$person->cell_number = $request['cell_number'];
			$person->status = 1;
			$user->addPerson($person);
			PasswordHistory::createPassword($user->id ,$password);
			
			// disconnect database
			DB::disconnect($DB_DATABASE);
			// reconnect to database
			\Config::set("database.connections.$currentDatabaseName", $tenantDatabaseConfig);
			DB::purge($currentDatabaseName);
			DB::reconnect($currentDatabaseName);
			DB::setDefaultConnection($currentDatabaseName);
		}
      
        //Get the token just created above
        $forgotPassword =  new ForgotPasswordController();
        $forgotPassword->sendResetDetails($request , $random_pass ,$user);
    }


    /**
     * @param $manage
     * @return void
     */
    public function ManageUsers($manage){


        $user = User::where('id', $manage)->first();
        $user['status'] == 1 ? $status = 0 : $status = 1;
        $user['status'] = $status;
        $user->update();

        DB::table('hr_people')->where('user_id',$manage)->update(array(
            'status'=>$status,
        ));
    }

    private function previousUrl()
    {
        return session('previous_url');
    }





}
