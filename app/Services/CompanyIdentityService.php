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
        /*$CompanyIdentity = CompanyIdentity::updateOrCreate([
            'company_name' => $request['company_name'],
            'full_company_name' => $request['full_company_name'],
            'mailing_name' => $request['mailing_name'],
            'mailing_address' => $request['mailing_address'],
            'support_email' => $request['support_email'],
            'password_expiring_month' => $request['password_expiring_month'],
            'company_website' => $request['company_website'],
            'header_name_bold' => $request['header_name_bold'],
            'header_acronym_bold' => $request['header_acronym_bold'],
            'header_name_regular' => $request['header_name_regular'],
            'header_acronym_regular' => $request['header_acronym_regular'],
            'address' => $request['address'],
            'suburb' => $request['suburb'],
            'city' => $request['city'],
            'postal_code' => $request['postal_code'],
            'monthly_revenue_target' => $request['monthly_revenue_target'],
            'daily_revenue_target' => $request['daily_revenue_target'],
        ]);*/
        /*
         * company_logo
         */
        $this->uploadImage($request, 'company_logo', 'companyIdentity', $CompanyIdentity);

        /**
         * save login image
         */

        $this->uploadImage($request, 'login_background_image', 'companyIdentity', $CompanyIdentity);

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

        $userDetails = HRPerson::updateOrCreate(
            [
                'user_id' => $request->get('user_id'),
            ],
            [
                'first_name' => $request->get('first_name'),
                'surname' => $request->get('surname'),
                'initial' => $request->get('initial'),
                'cell_number' => $mobile,
                'id_number' => $request->get('id_number'),
                'date_of_birth' => $person['date_of_birth'],
                'gender' => $request->get('gender'),
                'marital_status' => $request->get('marital_status'),
                'ethnicity' => $request->get('ethnicity'),
                'bio' => $request->get('bio'),
                'passport_number' => $request->get('passport_number'),
                'res_address' => $request['res_address'],
                'res_suburb' => $request->get('res_suburb'),
                'res_city' => $request->get('res_city'),
                'res_postal_code' => $request->get('res_postal_code'),
                'res_province_id' => $request->get('res_province_id'),
            ],
        );

        /*
        * avatar
        */
        $this->uploadImage($request, 'profile_pic', 'profile_pic', $userDetails);

        return $userDetails;
    }

    public function getAvatar($id)
    {

        $user =  User::where('id', $id)->with('person')->first();

        $defaultAvatar = ($user->person->gender === 0) ? asset('images/m-silhouette.jpg') : asset('images/f-silhouette.jpg');
        $avatar = $user->person->profile_pic;
        return (!empty($avatar)) ? asset('uploads/' . $user->person->profile_pic) : $defaultAvatar;

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
        $originalPath = public_path().'/images/';
        $interventionImage->save($originalPath.$imageName);

    }
}
