<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CompanyIdentity;
use App\Services\RestaurantService;
use App\Traits\BreadCrumpTrait;
use App\Traits\CompanyIdentityTrait;
use App\Services\CompanyIdentityService;
use App\Models\Menu;
use App\Models\Tables;
use App\Models\Orders;
use App\Models\OrdersServices;
use App\Models\ServiceType;
use App\Models\HRPerson;
use App\Models\TableScans;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Storage;
use Spatie\PdfToText\Pdf;
use Illuminate\Support\Facades\DB;

class RestaurantGuestController extends Controller
{	
	use BreadCrumpTrait, CompanyIdentityTrait;
	
	/**
     * @var PatientService
     */
    private $restaurantService;
	private $companyIdentityService;
	
	public function __construct(RestaurantService $restaurantService, CompanyIdentityService $companyIdentityService)
    {
        $this->RestaurantService = $restaurantService;
		$this->companyIdentityService = $companyIdentityService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Tables $table)
    {
		// data to display on views
		$data = $this->breadcrumb(
            'Restaurant ',
            'Restaurant ordering page',
            'restaurant_details',
            'Menu Page',
            'Restaurant Menu & Services'
        );
		// get table last scanned
		$scanned = TableScans::where('table_id', $table->id)->orderBy('id', 'desc')->first();

		if (!empty($scanned->status)  &&  $scanned->status === 1)
		{
			// get restaurant Manager
			$user =  DB::table('users')->select('users.*', 'model_has_roles.*')
				->leftJoin('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
				->where('model_has_roles.role_id', 3)
				->first();
			
			$manager = HRPerson::where('user_id',$user ->id)->first();
			//return $manager;
			// get orders and service history
			
			
			$data['avatar'] = $this->companyIdentityService->getAvatar($user->id);
			$data['menus'] = Menu::getMenus();
			$data['manager'] = $manager;
			$data['services'] = ServiceType::getServices();
			return view('guest.index')->with($data);
		}
		else
		{
			Alert::toast('This table have been closed !!! Please scan a qr code', 'warning');
			return redirect()->route("/restaurant/seating_plan/$table->id");
		}
		
		
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function serviceRequest(Tables $table, ServiceType $service)
    {
        //
		$newRequest = $this->RestaurantService->requestService($table, $service);
		
        alert()->success('SuccessAlert', "Your: $service->name request have been submitted, It been atttended to");
        activity()->log("New Service Request Added: $service->name ");

		return redirect()->back();
    }
	
	// order for meal
	public function orderRequest(Tables $table, ServiceType $service)
    {
        //
		$newRequest = $this->RestaurantService->requestService($table, $service);
		
        alert()->success('SuccessAlert', "Your: $service->name request have been submitted, It been atttended to");
        activity()->log("New Service Request Added: $service->name ");

		return redirect()->back();
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
