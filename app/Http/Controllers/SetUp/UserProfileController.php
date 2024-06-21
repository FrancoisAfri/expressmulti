<?php

namespace App\Http\Controllers\SetUp;

use App\Http\Controllers\Controller;
use App\Models\HRPerson;
use App\Models\Province;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Services\CompanyIdentityService;
use App\Traits\BreadCrumpTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class UserProfileController extends Controller
{
    use BreadCrumpTrait;

    /**
     * @var CompanyIdentityService
     */
    private $companyIdentityService;

    public function __construct(CompanyIdentityService $companyIdentityService)
    {
        $this->companyIdentityService = $companyIdentityService;
    }

        /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {

        $data = $this->breadcrumb(
            'Settings Modules',
            'User Profile',
            'User Profile',
            'Settings',
            'User Profile'
        );

        $user = Auth::user()->load('person');

		$data['roles'] = Role::orderBy('id', 'DESC')->paginate(5);
        $data['avatar'] = $this->companyIdentityService->getAvatar(Auth::id());
        $data['userDetails'] = HRPerson::getDetailsOfLoggedUser();
        $data['user'] =  Auth::user()->load('person');
        $data['user_role'] =$user->roles->pluck('id')->implode(',');
        return view('security.user-profile.index')->with($data);
    }
	// edit profile by admin users
	public function profile(User $user)
    {

        $data = $this->breadcrumb(
            'Settings Modules',
            'User Profile',
            'User Profile',
            'Settings',
            'User Profile'
        );

        $user = $user->load('person');
		$role =  DB::table('model_has_roles')->select('model_has_roles.role_id')
				->where('model_has_roles.model_id', $user->id)
				->first();
				//return $role;
		$data['roles'] = Role::select('id', 'name')->get();
        $data['avatar'] = $this->companyIdentityService->getAvatar($user->id);
        $data['userDetails'] = HRPerson::getDetailsOfLoggedUser();
        $data['user'] =  $user;
        $data['user_role'] =  !empty($role->role_id) ? $role->role_id : 0;
        return view('security.user-profile.index')->with($data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {

        $request->validate([
            'first_name' => 'required|max:255',
            'surname' => 'required|max:255',
            'initial' => 'required|max:255',
            'cell_number' => 'required|max:255',
            'email' => 'required',
        ]);

        $this->companyIdentityService->createOrUpdateUser($request);
        alert()->success('SuccessAlert', 'Your changes have been saved successfully');
        activity()->log('Updated User Information');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $data = $this->breadcrumb(
            'Settings Modules',
            'User Profile',
            'User Profile',
            'Settings',
            'User Profile'
        );

        $userDetails = HRPerson::where('uuid',$id)->first();

        $data['avatar'] = $this->companyIdentityService->getAvatar($userDetails->user_id);
        $data['userDetails'] = HRPerson::getDetailsOfLoggedUser();
        $data['user'] =  User::where('id',$userDetails->user_id )->with('person')->first();
        $data['provinces'] = Province::getAllProvinces();
        return view('security.user-profile.index')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
