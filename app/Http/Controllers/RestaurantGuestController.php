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
use App\Models\CloseRequests;
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
		
		// get table last scanned

		$scanned = TableScans::where('table_id', $table->id)->where('status', 1)->orderBy('id', 'desc')->first();

		if (!empty($scanned->status)  &&  $scanned->status === 1)
		{
			//  Restaurant ordering page
			$localName = (!empty($scanned['nickname'])) ? $scanned['nickname'] : '';
			// data to display on views
			$data = $this->breadcrumb(
				"Welcome $localName",
				"",
				'restaurant_details',
				'Menu Page',
				'Restaurant Menu & Services'
			);
			// get restaurant Manager
			$user =  DB::table('users')->select('users.*', 'model_has_roles.*')
				->leftJoin('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
				->where('model_has_roles.role_id', 3)
				->first();
			
			$manager = HRPerson::where('user_id',$user ->id)->first();
			// get orders and service history
			
			$data['avatar'] = $this->companyIdentityService->getAvatar($user->id);
			$data['menus'] = Menu::getMenus();
			$data['manager'] = $manager;
			$data['localName'] = $localName;
			$data['services'] = ServiceType::getServices();
			return view('guest.index')->with($data);
		}
		else
		{
			$scanned = TableScans::create([
					'ip_address' => '',
					'table_id' => $table->id,
					'scan_time' => time(),
					'status' => 1,
				]);
			$localName = '';
			Alert::toast('This table have been closed !!! Please scan a qr code', 'warning');
			return back();
			//return redirect('/patients/booking_calender');
			//eturn redirect()->route("seating.plan", $table->id);
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
    public function rateService(Request $request, TableScans $scan)
    {
		$comment = !empty($request['comments']) ? $request['comments'] : '';

		$scan->comment = $comment;
		$scan->update();
		
		Alert::toast('Thank you for your comment. We looking for to your next visist', 'success');
		activity()->log('Client Added Comment');
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
	// close request
	public function closeTableRequest(Tables $table, ServiceType $service)
    {
        //
		$newRequest = $this->RestaurantService->requestService($table, $service);
		
        alert()->success('SuccessAlert', "Your: $service->name request have been submitted, It been atttended to");
        activity()->log("New Service Request Added: $service->name ");

		return redirect()->back();
    }
	
	public function saveName(Request $request, TableScans $scan)
    {
		
		$nickname = !empty($request['nickname']) ? $request['nickname'] : '';
		
		$scan->nickname = $nickname;
		$scan->update();
		Alert::toast('Thank you!!! You may continue.', 'success');
        activity()->log('Table Closed Successfully');
		return back();
		
    }
}