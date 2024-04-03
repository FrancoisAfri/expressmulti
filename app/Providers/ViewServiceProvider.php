<?php

namespace App\Providers;

use App\Models\BookingNotification;
use App\Models\CompanyIdentity;
use App\Models\module_access;
use App\Models\module_ribbons;
use App\Models\modules;
use App\Models\HRPerson;
use App\Models\Notification;
use App\Models\ServiceType;
use App\Models\Tables;
use App\Models\TableScans;
use App\Models\Orders;
use App\Models\OrdersServices;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $companyDetails = CompanyIdentity::systemSettings();

        view()->composer('vendor.mail.html.message', function ($view) use ($companyDetails) {

            $data['logo'] = (!empty($companyDetails['company_logo_url'])) ? asset('uploads/'.$companyDetails['company_logo_url'] ) : asset('images/logo_default.png');
            $data['system_background_image_url'] = $companyDetails['system_background_image_url'];
            $data['login_background_image_url'] = $companyDetails['login_background_image_url'];

            $view->with($data);
        });
		view()->composer('layouts.main-guest', function ($view) use ( $companyDetails) {

			$logo = (!empty($companyDetails['company_logo_url'])) ? asset('uploads/'.$companyDetails['company_logo_url'] ) : asset('images/logo_default.png');

            $data['logo'] = $logo;

            $view->with($data);
        }); 
		
        view()->composer('layouts.partials.top-bar', function ($view) use ( $companyDetails) {

          $id = Auth::user()->id;
            $user = User::with('person')
                ->findOrFail($id);

            $defaultAvatar = ($user->person->gender === 0) ?  asset('images/m-silhouette.jpg')  :  asset('images/f-silhouette.jpg') ;

            $avatar = $user->person->profile_pic;
//            $position = (!empty($user->person->position)) ? DB::table('hr_positions')->find($user->person->position)->name : '';
            $logo = (!empty($companyDetails['company_logo_url'])) ? asset('uploads/'.$companyDetails['company_logo_url'] ) : asset('images/logo_default.png');

            $headerNameBold = $companyDetails['header_name_bold'];
            $headerNameRegular = $companyDetails['header_name_regular'];
            $headerAcronymBold = $companyDetails['header_acronym_bold'];
            $headerAcronymRegular = $companyDetails['header_acronym_regular'];

            $data['notifications'] = BookingNotification::getAllUnreadNotifications();
            $data['notificationsCount'] = BookingNotification::countNotifications();
            $data['user'] = $user;
            $data['full_name'] = $user->person->first_name . " " . $user->person->surname;
            $data['avatar'] =  (!empty($avatar)) ? asset('uploads/'.$user->person->profile_pic) : $defaultAvatar;

            $data['headerNameBold'] = $headerNameBold;
            $data['defaultAvatar'] = $defaultAvatar;
            $data['logo'] = $logo;
            $data['headerNameRegular'] = $headerNameRegular;
            $data['headerAcronymBold'] = $headerAcronymBold;
            $data['headerAcronymRegular'] = $headerAcronymRegular;

            $view->with($data);
        });

        //Compose sub bar
        view()->composer('layouts.partials.sub-bar', function ($view) use ($companyDetails) {
            $user = Auth::user();
            $modulesAccess = modules::whereHas('moduleRibbon', function ($query) {
                $query->where('active', 1);
            })->where('active', 1)
                ->whereIn('id', module_access::select('module_id')->where(function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                    $query->whereNotNull('access_level');
                    $query->where('access_level', '>', 0);
                })->get())
                ->with(['moduleRibbon' => function ($query) use ($user) {
                    $query->whereIn('id', module_ribbons::select('security_module_ribbons.id')->join('security_module_access', function ($join) use ($user) {
                        $join->on('security_module_ribbons.module_id', '=', 'security_module_access.module_id');
                        $join->on('security_module_access.user_id', '=', DB::raw($user->id));
                        $join->on('security_module_ribbons.access_level', '<=', 'security_module_access.access_level');
                    })->get());
                    $query->orderBy('sort_order', 'ASC');
                }])
                ->orderBy('name', 'ASC')->get();

            $data['company_logo'] = $companyDetails['company_logo_url'];
            $data['modulesAccess'] = $modulesAccess;
            $view->with($data);
        });

        view()->composer('layouts.partials.head', function ($view) use ( $companyDetails) {

            $logo = (!empty($companyDetails['company_logo_url'])) ? asset('uploads/'.$companyDetails['company_logo_url'] ) : asset('images/logo_default.png');

            $data['logo'] = $logo;
            $view->with($data);
        });

        view()->composer('auth.passwords.email', function ($view) use ( $companyDetails) {


            $logo = (!empty($companyDetails['company_logo_url'])) ? asset('uploads/'.$companyDetails['company_logo_url'] ) : asset('images/logo_default.png');
            $Background = (!empty($companyDetails['login_background_image_url'])) ? asset('uploads/'.$companyDetails['login_background_image_url'] ) : asset('images/bg-auth.jpg');

            $data['logo'] = $logo;
            $data['Background'] = $Background;
            $view->with($data);
        });
		
		view()->composer('layouts.main-guest', function ($view) use ( $companyDetails) {

			//
			$url = url()->current();
			$urlData = explode('/',$url);
			$tableID = !empty($urlData[5]) ? $urlData[5] : 0;
			// get table last scanned
			$scanned = TableScans::where('table_id', $tableID)->where('status', 1)->orderBy('id', 'desc')->first();

			$data['localName'] = (!empty($scanned['nickname'])) ? $scanned['nickname'] : '';
			$data['tableID'] = $tableID;
			$data['tableDetails'] = Tables::where('id', $tableID)->first();
			$data['services'] = ServiceType::getServices();
            $view->with($data);
        });
		view()->composer('guest.index', function ($view) use ( $companyDetails) {

			$url = url()->current();
			$urlData = explode('/',$url);
			$tableID = !empty($urlData[5]) ? $urlData[5] : 0;
			
			// get table details;
			$tableDetails = Tables::where('id', $tableID)->first();
			// get table last scanned
			$scanned = TableScans::where('table_id', $tableID)->where('status', 1)->orderBy('id', 'desc')->first();

			if (empty($scanned->status))
			{
				$scanned = TableScans::create([
					'ip_address' => '',
					'table_id' => $tableID,
					'waiter' => $tableDetails->employee_id,
					'scan_time' => time(),
					'status' => 1,
				]); 
			}
			$scannedTime = !empty($scanned->scan_time) ? strtotime($scanned->scan_time) : 0;
			
			// get avatar
			if (!empty($tableDetails->employee_id))
			{
				$hrUser = HRPerson::where('id', $tableDetails->employee_id)->first();
				$defaultAvatar = ($hrUser->gender === 0) ? asset('images/m-silhouette.jpg') : asset('images/f-silhouette.jpg');
				$profilePic = (!empty( $hrUser->profile_pic)) ? asset('uploads/' . $hrUser->profile_pic) : $defaultAvatar;
			}
			else $profilePic = '';
			$data['scanned'] = $scanned;
			$data['scannedTime'] = $scannedTime;
			$data['profilePic'] = $profilePic;
			$data['tableDetails'] = $tableDetails;
			$data['services'] = ServiceType::getServices();
            $view->with($data);
        });
    }
}
