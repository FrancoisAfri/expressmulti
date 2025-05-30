<?php

namespace App\Services;

use App\Models\HRPerson;
use App\Models\CompanyIdentity;
use App\Models\modules;
use App\Models\PublicHolidays;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\FileUpload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class CompanyIdentityService
{
    use FileUpload;

    /**
     * @param Request $request
     * @return CompanyIdentity
     */
    public function createOrUpdateCompanyIdentity(Request $request): CompanyIdentity
    {
        // check if table is not empty
        $CompanyIdentity = CompanyIdentity::first();
        // update/ save
        //$compDetails = (Schema::hasTable('company_identities')) ? CompanyIdentity::first() : null;
        if (!empty($CompanyIdentity)) { //update
            $CompanyIdentity->update($request->all());

        } else { //insert
            $CompanyIdentity = new CompanyIdentity($request->all());
            $CompanyIdentity->save();
        }

        /*
         * company_logo
         */
        //Upload Image picture
        if ($request->hasFile('company_logo')) {
            $fileExt = $request->file('company_logo')->extension();
            if (in_array($fileExt, ['jpg', 'jpeg', 'png']) && $request->file('company_logo')->isValid()) {
                $fileName = time() . "company_logo." . $fileExt;
                $request->file('company_logo')->storeAs('uploads', $fileName);
                //Update file name in the database
                $CompanyIdentity->company_logo = $fileName;
                $CompanyIdentity->update();
            }
        }
        //$this->uploadImage($request, 'company_logo', 'companyIdentity', $CompanyIdentity);

        /**
         * save login image
         */
        //Upload Image picture
        if ($request->hasFile('login_background_image')) {
            $fileExt = $request->file('login_background_image')->extension();
            if (in_array($fileExt, ['jpg', 'jpeg', 'png']) && $request->file('login_background_image')->isValid()) {
                $fileName = time() . "login_background_image." . $fileExt;
                $request->file('login_background_image')->storeAs('uploads', $fileName);
                //Update file name in the database
                $CompanyIdentity->login_background_image = $fileName;
                $CompanyIdentity->update();
            }
        }
		//Upload debit order form
        if ($request->hasFile('debit_order_form')) {
            $fileExt = $request->file('debit_order_form')->extension();
            if (in_array($fileExt, ['pdf']) && $request->file('debit_order_form')->isValid()) {
                $fileName = time() . "debit_order_form." . $fileExt;
                $request->file('debit_order_form')->storeAs('uploads', $fileName);
                //Update file name in the database
                $CompanyIdentity->debit_order_form = $fileName;
                $CompanyIdentity->update();
            }
        }
        //$this->uploadImage($request, 'login_background_image', 'companyIdentity', $CompanyIdentity);

        /*
            Write Code Here for
            Store $imageName name in DATABASE from HERE
        */

        return $CompanyIdentity;
    }

    /**
     * @return mixed
     */
    public function ViewComponyIdenties()
    {

        return CompanyIdentity::first();

    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function createOrUpdateUser(Request $request)
    {
        $mobile = str_replace(['(', ')', ' ', '-'], ['', '', '', ''], $request->post('cell_number'));
        $request->merge(array('cell_number' => $mobile));

        $person = $request->all();
        if (isset($person['date_of_birth'])) {
            $person['date_of_birth'] = str_replace('/', '-', $person['date_of_birth']);
            $person['date_of_birth'] = strtotime($person['date_of_birth']);
        }

        $userDetails = $this->getUserDetails($request, $mobile);

        //update updateLockoutTime
        $this->updateLockoutTime($request);
        /*
        * avatar
        */
        $this->extracted($request, $userDetails);
        // assign role

        list($user, $role) = $this->assignRole($request);

        if (!empty($role->role_id))
            $user->removeRole($role->role_id);
        $user->assignRole($request->get('roles'));
        return $userDetails;
    }

    private static function updateLockoutTime(Request $request)
    {
        $user = auth()->user();
        $lockoutTime = $request->input('lockout_time');
        $user->lockout_time = $lockoutTime;
        return $user->save();
    }
	
	private static function updateEmail(Request $request)
    {
        $user = auth()->user();
        $lockoutTime = $request->input('lockout_time');
        $user->lockout_time = $lockoutTime;
        return $user->save();
    }
	
    public function getAvatar($id)
    {

        $user = User::where('id', $id)->with('person')->first();

        $defaultAvatar = ($user->person->gender === 0) ? asset('images/m-silhouette.jpg') : asset('images/f-silhouette.jpg');
        $avatar = $user->person->profile_pic;
        return (!empty($avatar)) ? asset('uploads/' . $user->person->profile_pic) : $defaultAvatar;

    }

    public function getAvatarID($id)
    {

        $hrUser = HRPerson::where('id', $id)->first();
        $defaultAvatar = ($hrUser->gender === 0) ? asset('images/m-silhouette.jpg') : asset('images/f-silhouette.jpg');
        $avatar = $hrUser->profile_pic;
        return (!empty($avatar)) ? asset('uploads/' . $hrUser->profile_pic) : $defaultAvatar;

    }

    public function persistHolidays($request)
    {

        $person = $request->all();
        if (isset($person['day'])) {
            $person['day'] = str_replace('/', '-', $person['day']);
            $person['day'] = strtotime($person['day']);
        }

        return PublicHolidays::create([
            'name' => $request['name'],
            'day' => $person['day'],
            'country_id' => $request['country_id'],
        ]);
    }


    public function editHolidays($request, $holiday)
    {

        $holiday = PublicHolidays::find($holiday);

        $person = $request->all();
        if (isset($person['day'])) {
            $person['day'] = str_replace('/', '-', $person['day']);
            $person['day'] = strtotime($person['day']);
        }

        $holiday->update([
            'name' => $request['name'],
            'day' => $person['day'],
            'country_id' => $request['country_id'],
        ]);
    }

    public function encrypt($string)
    {
        return Crypt::encryptString($string);
    }


    public function decrypt($string)
    {
        return Crypt::decryptString($string);
    }

    /**
     * @param Request $request
     * @param $userDetails
     * @return void
     */
    public function extracted(Request $request, $userDetails): void
    {
        if ($request->hasFile('profile_pic')) {
            $fileExt = $request->file('profile_pic')->extension();
            if (in_array($fileExt, ['jpg', 'jpeg', 'png']) && $request->file('profile_pic')->isValid()) {
                $fileName = time() . "image." . $fileExt;
                $request->file('profile_pic')->storeAs('uploads', $fileName);
                //Update file name in the database
                $userDetails->profile_pic = $fileName;
                $userDetails->update();
            }
        }
    }

    /**
     * @param Request $request
     * @return array
     */
    public function assignRole(Request $request): array
    {
        $user = User::where('id', $request->get('user_id'))->first();
        $role = DB::table('model_has_roles')->select('model_has_roles.role_id')
            ->where('model_has_roles.model_id', $user->id)
            ->first();
        return array($user, $role);
    }

    /**
     * @param Request $request
     * @param $mobile
     * @return mixed
     */
    public function getUserDetails(Request $request, $mobile)
    {
		
		//return $request;
		//echo $request->get('employee_number');
		//die();
        $userDetails = HRPerson::updateOrCreate(
            [
                'user_id' => $request->get('user_id'),
            ],
            [
                'first_name' => $request->get('first_name'),
                'surname' => $request->get('surname'),
                'initial' => $request->get('initial'),
                'cell_number' => $mobile,
                'email' => $request->get('email'),
                'employee_number' => $request->get('employee_number'),
            ],
        );
		$LoginDetails = User::updateOrCreate(
            [
                'id' => $request->get('user_id'),
            ],
            [
                'email' => $request->get('email'),
            ],
        );
        return $userDetails;
    }

    private function saveLoginImage($request)
    {
        $request->validate([
            'login_background_image' =>
                'required|
                image|
                mimes:jpeg,
                png,
                jpg,
                gif,
                svg|max:2048',
        ]);

        $interventionImage = Image::make(
            $request->file('login_background_image'))
            ->encode(
                "jpg",
                100
            );
        $imageName = 'bg-auth' . '.' . 'jpg';
        $originalPath = public_path() . '/images/';
        $interventionImage->save($originalPath . $imageName);

    }
}
