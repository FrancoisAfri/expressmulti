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
