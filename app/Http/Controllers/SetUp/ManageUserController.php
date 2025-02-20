<?php

namespace App\Http\Controllers\SetUp;

use App\Http\Controllers\Controller;

use App\Models\CompanyIdentity;
use App\Models\HRPerson;
use App\Models\User;
use App\Traits\BreadCrumpTrait;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Role;
use App\Notifications\NewUserNotification;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Collection;

class ManageUserController extends Controller
{
    use BreadCrumpTrait;

    /**
     * @var AuthService
     */
    private $authService;


    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		
        $data = $this->breadcrumb(
            'Settings Users',
            'Admin page for security related settings',
            'manage',
            'Settings',
            'Manage Users'
        );

		//$user = User::with('person')->get();
		//return $user;
        $data['roles'] = Role::select('id', 'name')->get();
        $data['users'] = User::with('person')->get();
        $data['defaultAvatar'] = User::defaultAvatar();
        return view('security.manage-users.index')->with($data);
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


    public function store(Request $request)
    {
        $validator = validator($request->all(), [
            'first_name' => 'required',
            'surname' => 'required',
            'email' => 'required|email|unique:users',
            'cell_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:hr_people',
			'employee_number' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $this->authService->createUser($request);
        alert()->success('SuccessAlert', 'New User Created Successfully');
        return response()->json();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $user = User::where('id', $id)->first();
        $user->delete();

        DB::table('hr_people')->where('user_id',$id)->delete();

        Alert::toast('Record Status Deleted Successfully ', 'success');
        return back();
    }

    /*
     * Activate users
     */
    public function activateUsers($manage)
    {
        $this->authService->ManageUsers($manage);
        Alert::toast('Record Status changed Successfully ', 'success');
        return back();
    }
}
